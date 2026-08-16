<?php

namespace Tests\Unit;

use App\Enums\PembelianStatus;
use App\Models\CustomerModel;
use App\Models\Pembelian;
use App\Models\User;
use App\Services\Gateways\TriPayGateway;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TriPayGatewayTest extends TestCase
{
    protected TriPayGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('tripay.mode', 'sandbox');
        Config::set('tripay.api_key', 'TEST_API_KEY');
        Config::set('tripay.private_key', 'TEST_PRIVATE_KEY');
        Config::set('tripay.merchant_code', 'TEST_MERCHANT');
        Config::set('tripay.sandbox_base_url', 'https://tripay.co.id/api-sandbox/');

        $this->gateway = new TriPayGateway();
    }

    public function test_signature_generation(): void
    {
        $merchantRef = 'ORDER-12345';
        $amount = 150000;
        $privateKey = 'TEST_PRIVATE_KEY';
        $merchantCode = 'TEST_MERCHANT';

        $expected = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);
        $actual = $this->gateway->generateSignature($merchantRef, $amount);

        $this->assertSame($expected, $actual);
    }

    public function test_get_payment_channels_successful(): void
    {
        Http::fake([
            'https://tripay.co.id/api-sandbox/merchant/payment-channel*' => Http::response([
                'success' => true,
                'message' => 'Success',
                'data' => [
                    [
                        'group' => 'Virtual Account',
                        'code' => 'BRIVA',
                        'name' => 'BRI Virtual Account',
                        'type' => 'direct',
                        'active' => true,
                    ],
                    [
                        'group' => 'QRIS',
                        'code' => 'QRIS',
                        'name' => 'QRIS',
                        'type' => 'direct',
                        'active' => true,
                    ],
                ],
            ], 200),
        ]);

        $channels = $this->gateway->getPaymentChannels();

        $this->assertCount(2, $channels);
        $this->assertSame('BRIVA', $channels[0]['code']);
        $this->assertSame('QRIS', $channels[1]['code']);
    }

    public function test_create_transaction_valid_closed_payment(): void
    {
        Http::fake([
            'https://tripay.co.id/api-sandbox/transaction/create' => Http::response([
                'success' => true,
                'message' => 'Transaction created',
                'data' => [
                    'reference' => 'DEV-T1234567890',
                    'merchant_ref' => '01J6A1B2C3D4E5F6G7H8J9K0L1',
                    'payment_selection_type' => 'static',
                    'payment_method' => 'BRIVA',
                    'payment_name' => 'BRI Virtual Account',
                    'pay_code' => '88880123456789',
                    'checkout_url' => 'https://tripay.co.id/checkout/DEV-T1234567890',
                    'status' => 'UNPAID',
                    'expired_time' => time() + 900,
                    'instructions' => [
                        [
                            'title' => 'ATM BRI',
                            'steps' => ['Masukkan kartu ATM', 'Pilih menu Pembayaran', 'Masukkan nomor VA'],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $produk = new \App\Models\Produk(['nama_produk' => 'Netflix Premium']);
        $tipe = new \App\Models\TipeLayanan(['nama_tipe' => '1 Bulan']);
        $tipe->setRelation('produk', $produk);
        $varian = new \App\Models\VarianLayanan(['nama_varian' => 'Sharing']);
        $varian->setRelation('tipeLayanan', $tipe);

        $user = new User(['id' => 1, 'name' => 'Test Customer', 'email' => 'customer@test.com']);
        $customer = new CustomerModel(['id' => 1, 'user_id' => 1, 'nomor_telepon' => '081234567890']);
        $customer->setRelation('user', $user);

        $pembelian = new Pembelian([
            'order_id' => '01J6A1B2C3D4E5F6G7H8J9K0L1',
            'harga_saat_beli' => 50000,
            'id_customer' => 1,
            'id_varian' => 1,
        ]);
        $pembelian->setRelation('customer', $customer);
        $pembelian->setRelation('varianLayanan', $varian);

        $result = $this->gateway->createTransaction($pembelian, 'BRIVA');

        $this->assertSame('DEV-T1234567890', $result['reference']);
        $this->assertSame('88880123456789', $result['pay_code']);
        $this->assertSame('BRIVA', $result['payment_method']);
    }

    public function test_create_transaction_handles_api_failure(): void
    {
        Http::fake([
            'https://tripay.co.id/api-sandbox/transaction/create' => Http::response([
                'success' => false,
                'message' => 'Payment channel is currently unavailable',
            ], 400),
        ]);

        $user = new User(['id' => 1, 'name' => 'Test Customer', 'email' => 'customer@test.com']);
        $customer = new CustomerModel(['id' => 1, 'user_id' => 1, 'nomor_telepon' => '081234567890']);
        $customer->setRelation('user', $user);

        $pembelian = new Pembelian([
            'order_id' => '01J6A1B2C3D4E5F6G7H8J9K0L1',
            'harga_saat_beli' => 50000,
            'id_customer' => 1,
        ]);
        $pembelian->setRelation('customer', $customer);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Payment channel is currently unavailable');

        $this->gateway->createTransaction($pembelian, 'INVALID_CHANNEL');
    }

    public function test_verify_status_paid_maps_to_success(): void
    {
        Http::fake([
            'https://tripay.co.id/api-sandbox/transaction/detail*' => Http::response([
                'success' => true,
                'data' => [
                    'reference' => 'DEV-T1234567890',
                    'merchant_ref' => '01J6A1B2C3D4E5F6G7H8J9K0L1',
                    'status' => 'PAID',
                    'payment_method' => 'BCA Virtual Account',
                    'total_amount' => 100000,
                ],
            ], 200),
        ]);

        $result = $this->gateway->verifyStatus('DEV-T1234567890', 100000);

        $this->assertSame(PembelianStatus::SUCCESS, $result['status']);
        $this->assertSame('PAID', $result['raw_status']);
        $this->assertSame(100000, $result['gross_amount']);
    }

    public function test_verify_status_expired_maps_to_expired(): void
    {
        Http::fake([
            'https://tripay.co.id/api-sandbox/transaction/detail*' => Http::response([
                'success' => true,
                'data' => [
                    'reference' => 'DEV-T1234567890',
                    'merchant_ref' => '01J6A1B2C3D4E5F6G7H8J9K0L1',
                    'status' => 'EXPIRED',
                    'payment_method' => 'QRIS',
                    'total_amount' => 50000,
                ],
            ], 200),
        ]);

        $result = $this->gateway->verifyStatus('DEV-T1234567890', 50000);

        $this->assertSame(PembelianStatus::EXPIRED, $result['status']);
        $this->assertSame('EXPIRED', $result['raw_status']);
    }

    public function test_verify_status_failed_maps_to_failed(): void
    {
        Http::fake([
            'https://tripay.co.id/api-sandbox/transaction/detail*' => Http::response([
                'success' => true,
                'data' => [
                    'reference' => 'DEV-T1234567890',
                    'merchant_ref' => '01J6A1B2C3D4E5F6G7H8J9K0L1',
                    'status' => 'FAILED',
                    'payment_method' => 'OVO',
                    'total_amount' => 25000,
                ],
            ], 200),
        ]);

        $result = $this->gateway->verifyStatus('DEV-T1234567890', 25000);

        $this->assertSame(PembelianStatus::FAILED, $result['status']);
    }
}
