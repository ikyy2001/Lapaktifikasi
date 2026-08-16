<?php

namespace Tests\Feature;

use App\Enums\PembelianStatus;
use App\Models\Pembelian;
use App\Models\TripayWebhookLog;
use App\Services\PaymentProcessingService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class TriPayCallbackTest extends TestCase
{
    protected string $privateKey = 'TEST_PRIVATE_KEY';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('tripay.private_key', $this->privateKey);

        Schema::dropIfExists('tripay_webhook_logs');
        Schema::dropIfExists('tbl_pembelian_log');
        Schema::dropIfExists('tbl_pembelian');

        Schema::create('tbl_pembelian', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->string('order_id', 30)->unique();
            $table->decimal('harga_saat_beli', 12, 2);
            $table->string('status', 50)->default('pending');
            $table->string('payment_gateway', 50)->default('tripay');
            $table->string('gateway_reference')->nullable();
            $table->timestamp('reserved_until')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_pembelian_log', function (Blueprint $table) {
            $table->id('id_pembelian_log');
            $table->unsignedBigInteger('id_pembelian');
            $table->string('status_lama')->nullable();
            $table->string('status_baru')->nullable();
            $table->string('sumber_perubahan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('tripay_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->string('merchant_ref')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->string('status')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function test_callback_rejected_when_signature_missing(): void
    {
        $payload = ['status' => 'PAID'];

        $response = $this->postJson('/api/callback/tripay', $payload);

        $response->assertStatus(403);
        $response->assertJson(['success' => false, 'message' => 'Invalid signature']);
    }

    public function test_callback_rejected_when_signature_invalid(): void
    {
        $payload = ['status' => 'PAID', 'merchant_ref' => 'TEST-ORDER'];

        $response = $this->postJson('/api/callback/tripay', $payload, [
            'X-Callback-Signature' => 'invalid_signature_hash',
            'X-Callback-Event' => 'payment_status',
        ]);

        $response->assertStatus(403);
        $response->assertJson(['success' => false, 'message' => 'Invalid signature']);
    }

    public function test_callback_ignores_non_payment_status_event(): void
    {
        $payload = ['event' => 'custom_event'];
        $rawJson = json_encode($payload);
        $signature = hash_hmac('sha256', $rawJson, $this->privateKey);

        $response = $this->call('POST', '/api/callback/tripay', [], [], [], [
            'HTTP_X-Callback-Signature' => $signature,
            'HTTP_X-Callback-Event' => 'custom_event',
            'CONTENT_TYPE' => 'application/json',
        ], $rawJson);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'message' => 'Event ignored']);
    }

    public function test_callback_rejected_when_order_not_found(): void
    {
        $payload = [
            'reference' => 'DEV-T999999',
            'merchant_ref' => 'NON_EXISTENT_ORDER_ID',
            'status' => 'PAID',
            'total_amount' => 50000,
        ];
        $rawJson = json_encode($payload);
        $signature = hash_hmac('sha256', $rawJson, $this->privateKey);

        $response = $this->call('POST', '/api/callback/tripay', [], [], [], [
            'HTTP_X-Callback-Signature' => $signature,
            'HTTP_X-Callback-Event' => 'payment_status',
            'CONTENT_TYPE' => 'application/json',
        ], $rawJson);

        $response->assertStatus(404);
        $response->assertJson(['success' => false, 'message' => 'Order not found']);
    }

    public function test_callback_paid_marks_order_as_success_and_is_idempotent(): void
    {
        $orderId = '01J6A1B2C3D4E5F6G7H8J9K0L1';
        $pembelian = Pembelian::create([
            'order_id' => $orderId,
            'harga_saat_beli' => 75000,
            'status' => 'pending',
            'payment_gateway' => 'tripay',
            'gateway_reference' => 'DEV-T1234567890',
        ]);

        $mockProcessor = Mockery::mock(PaymentProcessingService::class);
        $mockProcessor->shouldReceive('markAsSuccess')
            ->once()
            ->andReturnUsing(function ($order) {
                $order->status = PembelianStatus::SUCCESS;
                $order->save();
            });

        $this->app->instance(PaymentProcessingService::class, $mockProcessor);

        $payload = [
            'reference' => 'DEV-T1234567890',
            'merchant_ref' => $orderId,
            'payment_method' => 'BCA Virtual Account',
            'payment_method_code' => 'BCAVA',
            'total_amount' => 75000,
            'status' => 'PAID',
        ];
        $rawJson = json_encode($payload);
        $signature = hash_hmac('sha256', $rawJson, $this->privateKey);

        // First callback
        $response1 = $this->call('POST', '/api/callback/tripay', [], [], [], [
            'HTTP_X-Callback-Signature' => $signature,
            'HTTP_X-Callback-Event' => 'payment_status',
            'CONTENT_TYPE' => 'application/json',
        ], $rawJson);

        $response1->assertStatus(200);
        $response1->assertJson(['success' => true]);

        // Verify webhook log recorded
        $this->assertDatabaseHas('tripay_webhook_logs', [
            'reference' => 'DEV-T1234567890',
            'merchant_ref' => $orderId,
            'status' => 'PAID',
        ]);

        // Second duplicate callback (Idempotency test)
        $response2 = $this->call('POST', '/api/callback/tripay', [], [], [], [
            'HTTP_X-Callback-Signature' => $signature,
            'HTTP_X-Callback-Event' => 'payment_status',
            'CONTENT_TYPE' => 'application/json',
        ], $rawJson);

        $response2->assertStatus(200);
        $response2->assertJson(['success' => true, 'message' => 'already processed']);
    }

    public function test_callback_expired_marks_order_as_failed(): void
    {
        $orderId = '01J6A1B2C3D4E5F6G7H8J9K0L2';
        Pembelian::create([
            'order_id' => $orderId,
            'harga_saat_beli' => 50000,
            'status' => 'pending',
            'payment_gateway' => 'tripay',
            'gateway_reference' => 'DEV-T1234567892',
        ]);

        $mockProcessor = Mockery::mock(PaymentProcessingService::class);
        $mockProcessor->shouldReceive('markAsFailed')
            ->once()
            ->with(Mockery::type(Pembelian::class), 'expire', 'tripay');

        $this->app->instance(PaymentProcessingService::class, $mockProcessor);

        $payload = [
            'reference' => 'DEV-T1234567892',
            'merchant_ref' => $orderId,
            'status' => 'EXPIRED',
            'total_amount' => 50000,
        ];
        $rawJson = json_encode($payload);
        $signature = hash_hmac('sha256', $rawJson, $this->privateKey);

        $response = $this->call('POST', '/api/callback/tripay', [], [], [], [
            'HTTP_X-Callback-Signature' => $signature,
            'HTTP_X-Callback-Event' => 'payment_status',
            'CONTENT_TYPE' => 'application/json',
        ], $rawJson);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
