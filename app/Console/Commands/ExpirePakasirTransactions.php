<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembelian;
use App\Enums\PembelianStatus;
use App\Services\Gateways\PaymentGatewayFactory;
use App\Services\PaymentProcessingService;
use Illuminate\Support\Facades\Log;

class ExpirePakasirTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pakasir:expire-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending Pakasir transactions that have passed their reserved_until time';

    protected $paymentProcessor;

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
    public function handle()
    {
        $this->info('Starting Pakasir expiration job...');

        $expiredPurchases = Pembelian::where('payment_gateway', 'pakasir')
            ->where('status', PembelianStatus::PENDING)
            ->where('reserved_until', '<', now())
            ->get();

        if ($expiredPurchases->isEmpty()) {
            $this->info('No pending expired Pakasir transactions found.');
            return Command::SUCCESS;
        }

        $gateway = PaymentGatewayFactory::make('pakasir');

        foreach ($expiredPurchases as $pembelian) {
            $orderId = $pembelian->order_id;
            $amount = (int) $pembelian->harga_saat_beli;
            
            $this->info("Processing Order ID: {$orderId}");

            // 1. Attempt to cancel on Pakasir
            $isCancelled = $gateway->cancelTransaction($orderId, $amount);

            if ($isCancelled) {
                // Successfully cancelled on Pakasir, safe to expire locally
                $this->paymentProcessor->markAsFailed($pembelian, 'expire', 'pakasir');
                $this->info("Order ID: {$orderId} successfully cancelled and marked as EXPIRED.");
            } else {
                Log::warning("Failed to cancel Pakasir transaction. Cross-checking status.", ['order_id' => $orderId]);
                $this->warn("Failed to cancel Order ID: {$orderId}. Cross-checking status.");
                
                // 2. Cross-check status via verifyStatus
                try {
                    $statusData = $gateway->verifyStatus($orderId, $amount);
                    
                    if ($statusData['status'] === PembelianStatus::SUCCESS) {
                        // Turned out it was actually paid!
                        $this->paymentProcessor->markAsSuccess($pembelian, [
                            'payment_type' => $statusData['payment_type'] ?? 'pakasir',
                            'payment_gateway' => 'pakasir',
                            'gross_amount' => $statusData['gross_amount'] ?? $amount,
                            'transaction_id' => $statusData['transaction_id'] ?? null,
                        ]);
                        $this->info("Order ID: {$orderId} was actually SUCCESS. Processed accordingly.");
                    } else {
                        // Status is pending/failed/expired on Pakasir, safe to expire locally
                        $this->paymentProcessor->markAsFailed($pembelian, 'expire', 'pakasir');
                        $this->info("Order ID: {$orderId} verified as not success. Marked as EXPIRED locally.");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to verify Pakasir transaction status during expiration check.", [
                        'order_id' => $orderId,
                        'error' => $e->getMessage()
                    ]);
                    $this->error("Failed to verify Order ID: {$orderId}. Forcing local EXPIRE.");
                    // Force expire locally since time is up and we can't reach API to verify.
                    $this->paymentProcessor->markAsFailed($pembelian, 'expire', 'pakasir');
                }
            }
        }

        $this->info('Pakasir expiration job completed.');
        return Command::SUCCESS;
    }
}
