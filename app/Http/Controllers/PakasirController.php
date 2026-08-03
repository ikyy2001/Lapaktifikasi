<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use App\Models\StokAkun;
use App\Enums\PembelianStatus;
use App\Enums\StokStatus;
use App\Services\Gateways\PaymentGatewayFactory;
use App\Services\PaymentProcessingService;

class PakasirController extends Controller
{
    protected $paymentProcessor;

    public function __construct(PaymentProcessingService $paymentProcessor)
    {
        $this->paymentProcessor = $paymentProcessor;
    }

    public function callback(Request $request)
    {
        $payload = $request->all();
        $orderId = $request->input('order_id');
        $amount = (int) $request->input('amount');
        $rawStatus = $request->input('status');

        // 1. Simpan payload mentah (raw log untuk audit)
        $logId = DB::table('pakasir_webhook_logs')->insertGetId([
            'order_id' => $orderId,
            'amount' => $amount,
            'status' => $rawStatus,
            'payload' => json_encode($payload),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            // Validasi keberadaan Pembelian
            $pembelian = Pembelian::where('order_id', $orderId)->first();
            
            if (!$pembelian) {
                Log::warning('Pakasir webhook suspicious: order_id not found', ['order_id' => $orderId]);
                // 7. Return HTTP 200 to avoid retries
                return response()->json(['message' => 'Order not found'], 200);
            }

            if ((int)$pembelian->harga_saat_beli !== $amount) {
                Log::warning('Pakasir webhook suspicious: amount mismatch', [
                    'order_id' => $orderId,
                    'webhook_amount' => $amount,
                    'db_amount' => $pembelian->harga_saat_beli
                ]);
                return response()->json(['message' => 'Amount mismatch'], 200);
            }

            // 4. Cek idempotensi
            if ($pembelian->status === PembelianStatus::SUCCESS) {
                return response()->json(['message' => 'already processed'], 200);
            }

            // 3. Verifikasi status asli ke Pakasir API
            $gateway = PaymentGatewayFactory::make('pakasir');
            $verificationResult = $gateway->verifyStatus($orderId, $amount);
            
            // 6. Update verified_at
            DB::table('pakasir_webhook_logs')
                ->where('id', $logId)
                ->update(['verified_at' => now()]);

            // 5. Lanjut processing logic jika SUCCESS
            if ($verificationResult['status'] === PembelianStatus::SUCCESS) {
                $this->paymentProcessor->markAsSuccess($pembelian, [
                    'payment_type' => $verificationResult['payment_type'] ?? 'pakasir',
                    'payment_gateway' => 'pakasir',
                    'gross_amount' => $verificationResult['gross_amount'] ?? $pembelian->harga_saat_beli,
                    'transaction_id' => $verificationResult['transaction_id'] ?? null,
                ]);
            } elseif (in_array($verificationResult['status'], [PembelianStatus::EXPIRED, PembelianStatus::FAILED], true)) {
                $this->paymentProcessor->markAsFailed($pembelian, $verificationResult['status']->value ?? 'failed', 'pakasir');
            }

            // 7. Return HTTP 200
            return response()->json(['message' => 'processed'], 200);
            
        } catch (\Exception $e) {
            Log::error('Pakasir webhook error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // 7. Return HTTP 200 terlepas dari hasil agar Pakasir tidak retry terus
            return response()->json(['message' => 'error processed', 'error' => $e->getMessage()], 200);
        }
    }


}
