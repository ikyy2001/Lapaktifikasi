<?php

namespace App\Console\Commands;

use App\Enums\PembelianStatus;
use App\Models\Pembelian;
use App\Services\Gateways\PaymentGatewayFactory;
use App\Services\PaymentProcessingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReconcileTriPayTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tripay:reconcile-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile and sync pending TriPay transactions with the TriPay server';

    protected PaymentProcessingService $paymentProcessor;

    /**
     * Create a new command instance.
     */
    public function __construct(PaymentProcessingService $paymentProcessor)
    {
        parent::__construct();
        $this->paymentProcessor = $paymentProcessor;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting TriPay transaction reconciliation...');

        $pendingPurchases = Pembelian::where('payment_gateway', 'tripay')
            ->where('status', PembelianStatus::PENDING)
            ->get();

        if ($pendingPurchases->isEmpty()) {
            $this->info('No pending TriPay transactions found.');
            return Command::SUCCESS;
        }

        $gateway = PaymentGatewayFactory::make('tripay');

        foreach ($pendingPurchases as $pembelian) {
            $orderId = $pembelian->order_id;
            $amount = (int) $pembelian->harga_saat_beli;

            try {
                $statusData = $gateway->verifyStatus($orderId, $amount);

                if ($statusData['status'] === PembelianStatus::SUCCESS) {
                    $this->paymentProcessor->markAsSuccess($pembelian, [
                        'payment_type' => $statusData['payment_type'] ?? 'tripay',
                        'payment_gateway' => 'tripay',
                        'gross_amount' => $statusData['gross_amount'] ?? $amount,
                        'transaction_id' => $statusData['transaction_id'] ?? $pembelian->gateway_reference,
                    ]);

                    $this->info("Order {$orderId} marked as SUCCESS.");
                } elseif (in_array($statusData['status'], [PembelianStatus::EXPIRED, PembelianStatus::FAILED], true)) {
                    $this->paymentProcessor->markAsFailed($pembelian, $statusData['status']->value ?? 'failed', 'tripay');
                    $this->info("Order {$orderId} marked as {$statusData['status']->value}.");
                } elseif ($pembelian->reserved_until && $pembelian->reserved_until < now()) {
                    // Timeout fallback if TriPay status is still unpaid
                    $this->paymentProcessor->markAsFailed($pembelian, 'expire', 'tripay');
                    $this->info("Order {$orderId} reservation expired.");
                }
            } catch (\Exception $e) {
                Log::error('TriPay reconciliation error for order', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Failed to check status for {$orderId}: " . $e->getMessage());
            }
        }

        $this->info('TriPay reconciliation completed.');
        return Command::SUCCESS;
    }
}
