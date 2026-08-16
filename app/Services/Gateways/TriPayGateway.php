<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PembelianStatus;
use App\Models\Pembelian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TriPayGateway implements PaymentGatewayInterface
{
    protected string $mode;
    protected bool $isProduction;
    protected string $apiKey;
    protected string $privateKey;
    protected string $merchantCode;
    protected string $baseUrl;
    protected int $channelCacheTtl;

    public function __construct()
    {
        $this->mode = config('tripay.mode', 'sandbox');
        $this->isProduction = (bool) config('tripay.is_production', false);
        $this->apiKey = (string) config('tripay.api_key', '');
        $this->privateKey = (string) config('tripay.private_key', '');
        $this->merchantCode = (string) config('tripay.merchant_code', '');
        $this->baseUrl = rtrim(config('tripay.base_url', 'https://tripay.co.id/api-sandbox/'), '/') . '/';
        $this->channelCacheTtl = (int) config('tripay.channel_cache_ttl', 3600 * 6);
    }

    /**
     * Generate HMAC-SHA256 signature for TriPay requests.
     */
    public function generateSignature(string $merchantRef, int $amount): string
    {
        return hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);
    }

    /**
     * Fetch active payment channels from TriPay with caching.
     */
    public function getPaymentChannels(bool $fresh = false): array
    {
        $cacheKey = 'tripay_payment_channels_' . $this->mode;

        if (!$fresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey, []);
        }

        try {
            $url = "{$this->baseUrl}merchant/payment-channel";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(8)->connectTimeout(4)->get($url);

            if ($response->successful()) {
                $json = $response->json();
                $channels = $json['data'] ?? [];
                
                Cache::put($cacheKey, $channels, $this->channelCacheTtl);
                return $channels;
            }

            Log::warning('TriPay getPaymentChannels non-successful response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return Cache::get($cacheKey, []);
        } catch (\Exception $e) {
            Log::error('TriPay getPaymentChannels exception', [
                'error' => $e->getMessage(),
            ]);
            return Cache::get($cacheKey, []);
        }
    }

    /**
     * Create Closed Payment Transaction on TriPay.
     */
    public function createTransaction(Pembelian $pembelian, string $method = 'QRIS'): array
    {
        if (empty($this->apiKey) || empty($this->privateKey) || empty($this->merchantCode)) {
            throw new \Exception('Kredensial TriPay belum dikonfigurasi lengkap.');
        }

        try {
            $amount = (int) $pembelian->harga_saat_beli;
            $merchantRef = $pembelian->order_id;
            $signature = $this->generateSignature($merchantRef, $amount);

            $customer = $pembelian->customer;
            $user = $customer?->user;
            $customerName = trim($user?->name ?? '') ?: 'Customer';
            $customerEmail = trim($user?->email ?? '') ?: 'customer@lapaktifikasi.com';
            $customerPhone = trim($customer?->nomor_telepon ?? '') ?: '08123456789';

            $varian = $pembelian->varianLayanan;
            $tipe = $varian?->tipeLayanan;
            $produk = $tipe?->produk;
            $itemName = trim(($produk?->nama_produk ?? 'Akun Premium') . ' - ' . ($tipe?->nama_tipe ?? '') . ' (' . ($varian?->nama_varian ?? '') . ')');
            $sku = (string) ($varian?->id_varian ?? $pembelian->id_varian);

            $orderItems = [
                [
                    'sku' => $sku,
                    'name' => $itemName,
                    'price' => $amount,
                    'quantity' => 1,
                    'subtotal' => $amount,
                ],
            ];

            $expiredTime = $pembelian->reserved_until 
                ? $pembelian->reserved_until->timestamp 
                : (time() + (15 * 60));

            $payload = [
                'method' => strtoupper($method),
                'merchant_ref' => $merchantRef,
                'amount' => $amount,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'order_items' => $orderItems,
                'return_url' => route('bukti_pembayaran.status', ['order_id' => $merchantRef]),
                'expired_time' => $expiredTime,
                'signature' => $signature,
            ];

            $url = "{$this->baseUrl}transaction/create";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(10)->connectTimeout(5)->post($url, $payload);

            if ($response->successful()) {
                $json = $response->json();
                $data = $json['data'] ?? [];

                $reference = $data['reference'] ?? null;
                $expiredTimestamp = $data['expired_time'] ?? null;

                $updateData = [
                    'payment_gateway' => 'tripay',
                    'gateway_reference' => $reference,
                ];

                if ($expiredTimestamp) {
                    try {
                        $updateData['reserved_until'] = Carbon::createFromTimestamp($expiredTimestamp)->setTimezone(config('app.timezone'));
                    } catch (\Exception $e) {
                        Log::warning('Failed to parse TriPay expired_time', ['error' => $e->getMessage()]);
                    }
                }

                $pembelian->update($updateData);

                return [
                    'type' => 'tripay',
                    'reference' => $reference,
                    'merchant_ref' => $data['merchant_ref'] ?? $merchantRef,
                    'payment_method' => $data['payment_method'] ?? $method,
                    'payment_name' => $data['payment_name'] ?? null,
                    'pay_code' => $data['pay_code'] ?? null,
                    'pay_url' => $data['pay_url'] ?? null,
                    'checkout_url' => $data['checkout_url'] ?? null,
                    'qr_string' => $data['qr_string'] ?? null,
                    'qr_url' => $data['qr_url'] ?? null,
                    'total_amount' => $data['amount'] ?? $amount,
                    'fee_customer' => $data['fee_customer'] ?? 0,
                    'total_fee' => $data['total_fee'] ?? 0,
                    'expired_at' => isset($data['expired_time']) ? date('Y-m-d H:i:s', $data['expired_time']) : null,
                    'instructions' => $data['instructions'] ?? [],
                    'raw_response' => $data,
                ];
            }

            Log::error('TriPay createTransaction failed', [
                'order_id' => $merchantRef,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            $errorMsg = $response->json('message') ?? ('HTTP Status: ' . $response->status());
            throw new \Exception('Gagal membuat transaksi TriPay: ' . $errorMsg);
        } catch (\Exception $e) {
            Log::error('TriPay createTransaction Exception', [
                'order_id' => $pembelian->order_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify transaction status with TriPay.
     */
    public function verifyStatus(string $orderId, int $amount = 0): array
    {
        try {
            $pembelian = null;
            try {
                $pembelian = Pembelian::where('order_id', $orderId)->first();
            } catch (\Throwable $e) {
                // Ignore DB error during unit testing
            }
            $reference = $pembelian?->gateway_reference ?? (str_starts_with($orderId, 'DEV-T') || str_starts_with($orderId, 'T') ? $orderId : null);
            $transactionData = null;

            // 1. If we have gateway_reference (TriPay reference code), query transaction/detail
            if (!empty($reference)) {
                $url = "{$this->baseUrl}transaction/detail";
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])->timeout(8)->connectTimeout(4)->get($url, [
                    'reference' => $reference,
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $transactionData = $json['data'] ?? null;
                }
            }

            // 2. If reference was not available or detail call failed, lookup by merchant_ref
            if (empty($transactionData)) {
                $listUrl = "{$this->baseUrl}merchant/transactions";
                $listResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])->timeout(8)->connectTimeout(4)->get($listUrl, [
                    'merchant_ref' => $orderId,
                    'per_page' => 1,
                ]);

                if ($listResponse->successful()) {
                    $listJson = $listResponse->json();
                    $items = $listJson['data'] ?? [];
                    if (!empty($items)) {
                        $transactionData = $items[0];
                    }
                }
            }

            if (!$transactionData) {
                Log::warning('TriPay verifyStatus: transaction not found on TriPay server', [
                    'order_id' => $orderId,
                    'reference' => $reference,
                ]);

                return [
                    'status' => PembelianStatus::PENDING,
                    'raw_status' => 'UNPAID',
                    'payment_type' => 'tripay',
                    'transaction_id' => $reference,
                    'gross_amount' => $amount,
                    'fraud_status' => null,
                    'raw_response' => null,
                ];
            }

            $rawStatus = strtoupper($transactionData['status'] ?? 'UNPAID');
            $mappedStatus = PembelianStatus::PENDING;

            if ($rawStatus === 'PAID') {
                $mappedStatus = PembelianStatus::SUCCESS;
            } elseif ($rawStatus === 'EXPIRED') {
                $mappedStatus = PembelianStatus::EXPIRED;
            } elseif (in_array($rawStatus, ['FAILED', 'REFUND'], true)) {
                $mappedStatus = PembelianStatus::FAILED;
            }

            return [
                'status' => $mappedStatus,
                'raw_status' => $rawStatus,
                'payment_type' => $transactionData['payment_method'] ?? 'tripay',
                'transaction_id' => $transactionData['reference'] ?? $reference,
                'gross_amount' => (int) ($transactionData['amount'] ?? $amount),
                'fraud_status' => null,
                'raw_response' => $transactionData,
            ];
        } catch (\Exception $e) {
            Log::error('TriPay verifyStatus Exception', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Cancel a transaction.
     * Note: TriPay closed transactions auto-expire on server when expired_time is reached.
     */
    public function cancelTransaction(string $orderId, int $amount = 0): bool
    {
        return true;
    }
}
