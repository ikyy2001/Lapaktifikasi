<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PembelianStatus;
use App\Models\Pembelian;

class MidtransGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
    }

    public function createTransaction(Pembelian $pembelian, string $method = 'qris'): array
    {
        $varian = $pembelian->varianLayanan;
        $tipe = $varian->tipeLayanan;
        $produk = $tipe->produk;
        
        $customer = $pembelian->customer;
        $user = $customer->user;

        $items = [
            [
                'id' => $varian->id_varian,
                'price' => $pembelian->harga_saat_beli,
                'quantity' => 1,
                'name' => $produk->nama_produk . ' - ' . $tipe->nama_tipe . ' (' . $varian->nama_varian . ')'
            ]
        ];

        $params = [
            'item_details' => $items,
            'transaction_details' => [
                'order_id' => $pembelian->order_id,
                'gross_amount' => $pembelian->harga_saat_beli,
            ],
            'customer_details' => [
                'first_name' => $user->name ?? '',
                'phone' => $customer->nomor_telepon ?? '',
            ],
            'callbacks' => [
                'finish' => route('bukti_pembayaran.status', ['order_id' => $pembelian->order_id])
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return ['type' => 'snap', 'token' => $snapToken];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function verifyStatus(string $orderId, int $amount = 0): array
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            $mappedStatus = PembelianStatus::PENDING;

            if ($status->transaction_status == 'settlement' || ($status->transaction_status == 'capture' && $status->fraud_status == 'accept')) {
                $mappedStatus = PembelianStatus::SUCCESS;
            } elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                if ($status->transaction_status == 'expire') {
                    $mappedStatus = PembelianStatus::EXPIRED;
                } else {
                    $mappedStatus = PembelianStatus::FAILED;
                }
            }

            return [
                'status' => $mappedStatus,
                'raw_status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? 'midtrans',
                'transaction_id' => $status->transaction_id ?? null,
                'gross_amount' => $status->gross_amount ?? $amount,
                'fraud_status' => $status->fraud_status ?? null
            ];
        } catch (\Exception $e) {
            // Re-throw so callers can handle exceptions (like Midtrans API errors)
            throw $e;
        }
    }

    public function cancelTransaction(string $orderId, int $amount = 0): bool
    {
        try {
            \Midtrans\Transaction::cancel($orderId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
