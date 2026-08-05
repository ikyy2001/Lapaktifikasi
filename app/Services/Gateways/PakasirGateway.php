<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PembelianStatus;
use App\Models\Pembelian;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirGateway implements PaymentGatewayInterface
{
    protected $projectSlug;
    protected $apiKey;
    protected $baseUrl;
    protected $sandboxMode;

    public function __construct()
    {
        $this->projectSlug = config('pakasir.project_slug');
        $this->apiKey = config('pakasir.api_key');
        $this->baseUrl = rtrim(config('pakasir.base_url', 'https://app.pakasir.com'), '/');
        $this->sandboxMode = config('pakasir.sandbox_mode', false);
    }

    /**
     * Supported methods:
     * qris, bca_va, bni_va, bri_va, mandiri_va, permata_va, cimb_va, bsi_va, bjb_va, 
     * dana, shopeepay, linkaja, gopay, ovo, alfa, indomaret
     */
    public function createTransaction(Pembelian $pembelian, string $method = 'qris'): array
    {
        try {
            $url = "{$this->baseUrl}/api/transactioncreate/{$method}";
            
            $response = Http::timeout(6)->connectTimeout(3)->post($url, [
                'project' => $this->projectSlug,
                'order_id' => $pembelian->order_id,
                'amount' => (int) $pembelian->harga_saat_beli,
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $paymentData = $data['payment'] ?? [];
                
                $paymentNumber = $paymentData['payment_number'] ?? null;
                $expiredAt = $paymentData['expired_at'] ?? null;

                if ($expiredAt) {
                    try {
                        $parsedExpiredAt = \Carbon\Carbon::parse($expiredAt)->setTimezone(config('app.timezone'));
                        $pembelian->update(['reserved_until' => $parsedExpiredAt]);
                    } catch (\Exception $e) {
                        Log::warning('Failed to parse Pakasir expired_at', ['error' => $e->getMessage()]);
                    }
                }

                return [
                    'type' => $method === 'qris' ? 'qr_string' : 'va_number',
                    'payment_number' => $paymentNumber,
                    'expired_at' => $expiredAt,
                    'total_payment' => $paymentData['total_payment'] ?? null,
                    'fee' => $paymentData['fee'] ?? null,
                    'raw_response' => $data
                ];
            }

            Log::error('Pakasir createTransaction failed', [
                'order_id' => $pembelian->order_id,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new \Exception('Gagal membuat transaksi Pakasir. HTTP Status: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Pakasir createTransaction Exception', [
                'order_id' => $pembelian->order_id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function verifyStatus(string $orderId, int $amount = 0): array
    {
        try {
            $url = "{$this->baseUrl}/api/transactiondetail";
            
            $response = Http::timeout(6)->connectTimeout(3)->get($url, [
                'project' => $this->projectSlug,
                'amount' => $amount,
                'order_id' => $orderId,
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $transactionData = $data['transaction'] ?? [];
                $rawStatus = $transactionData['status'] ?? 'unknown';

                $mappedStatus = PembelianStatus::PENDING;
                if ($rawStatus === 'completed') {
                    $mappedStatus = PembelianStatus::SUCCESS;
                } else {
                    Log::info('Pakasir verifyStatus non-completed status', [
                        'order_id' => $orderId,
                        'status' => $rawStatus,
                        'response' => $data
                    ]);
                }

                return [
                    'status' => $mappedStatus,
                    'raw_status' => $rawStatus,
                    'payment_type' => $transactionData['payment_method'] ?? 'pakasir',
                    'transaction_id' => $transactionData['transaction_id'] ?? null,
                    'gross_amount' => $amount, // We use original amount based on instruction
                    'fraud_status' => null
                ];
            }

            Log::error('Pakasir verifyStatus failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new \Exception('Gagal memverifikasi status Pakasir. HTTP Status: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Pakasir verifyStatus Exception', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function cancelTransaction(string $orderId, int $amount = 0): bool
    {
        try {
            $url = "{$this->baseUrl}/api/transactioncancel";
            
            $response = Http::timeout(6)->connectTimeout(3)->post($url, [
                'project' => $this->projectSlug,
                'order_id' => $orderId,
                'amount' => $amount,
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Pakasir cancelTransaction failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Pakasir cancelTransaction Exception', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
