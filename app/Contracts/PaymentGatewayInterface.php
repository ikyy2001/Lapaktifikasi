<?php

namespace App\Contracts;

use App\Models\Pembelian;

interface PaymentGatewayInterface
{
    /**
     * Create a new transaction and return data needed by frontend.
     * Example return: ['type' => 'snap', 'token' => '...'] or ['type' => 'redirect', 'url' => '...']
     */
    public function createTransaction(Pembelian $pembelian, string $method = 'qris'): array;

    /**
     * Verify the status of an existing transaction.
     * Return normalized status: ['status' => App\Enums\PembelianStatus::*, 'raw_status' => '...']
     */
    public function verifyStatus(string $orderId, int $amount): array;

    /**
     * Cancel a transaction.
     */
    public function cancelTransaction(string $orderId, int $amount): bool;
}
