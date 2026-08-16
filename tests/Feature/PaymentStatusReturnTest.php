<?php

namespace Tests\Feature;

use App\Enums\PembelianStatus;
use App\Models\Pembelian;
use App\Models\User;
use App\Models\CustomerModel;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PaymentStatusReturnTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('tbl_pembelian_log');
        Schema::dropIfExists('tbl_pembelian');
        Schema::dropIfExists('tbl_varian_layanan');
        Schema::dropIfExists('tbl_tipe_layanan');
        Schema::dropIfExists('tbl_produk');
        Schema::dropIfExists('tbl_customer');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('role')->default('customer');
            $table->timestamps();
        });

        Schema::create('tbl_customer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nomor_telepon')->nullable();
            $table->string('kode_referral')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk');
            $table->string('tipe_produk')->default('premium');
            $table->timestamps();
        });

        Schema::create('tbl_tipe_layanan', function (Blueprint $table) {
            $table->id('id_tipe');
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_tipe');
            $table->timestamps();
        });

        Schema::create('tbl_varian_layanan', function (Blueprint $table) {
            $table->id('id_varian');
            $table->unsignedBigInteger('id_tipe');
            $table->string('nama_varian');
            $table->timestamps();
        });

        Schema::create('tbl_pembelian', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->string('order_id', 30)->unique();
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->unsignedBigInteger('id_varian')->nullable();
            $table->unsignedBigInteger('id_stok')->nullable();
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
    }

    public function test_status_page_renders_without_undefined_variable_error()
    {
        $user = User::create(['name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'customer']);
        $customer = CustomerModel::create(['user_id' => $user->id, 'nomor_telepon' => '08123456789']);

        $produk = Produk::create(['nama_produk' => 'Netflix 1 Bulan']);
        $tipe = TipeLayanan::create(['id_produk' => $produk->id_produk, 'nama_tipe' => 'Private']);
        $varian = VarianLayanan::create(['id_tipe' => $tipe->id_tipe, 'nama_varian' => '1 Profile']);

        $pembelian = Pembelian::create([
            'order_id' => '01M04AEYZJA4Z4BKRRX1DGXRYY',
            'id_customer' => $customer->id,
            'id_varian' => $varian->id_varian,
            'harga_saat_beli' => 50000,
            'status' => 'pending',
            'payment_gateway' => 'tripay',
            'reserved_until' => now()->addMinutes(10),
        ]);

        $this->actingAs($user);
        session(['id' => $user->id]);

        $view = view('customer.status_pembayaran', [
            'type' => 'premium',
            'order' => $pembelian,
            'orderId' => $pembelian->order_id,
            'status' => 'success',
        ]);

        $rendered = $view->render();
        $this->assertStringContainsString('01M04AEYZJA4Z4BKRRX1DGXRYY', $rendered);
        $this->assertStringContainsString('PEMBAYARAN BERHASIL', strtoupper($rendered));
    }

    public function test_riwayat_renders_transaksi_dibatalkan_when_order_expired()
    {
        $user = User::create(['name' => 'Jane Doe', 'email' => 'jane@example.com', 'role' => 'customer']);
        $customer = CustomerModel::create(['user_id' => $user->id, 'nomor_telepon' => '08987654321']);

        $produk = Produk::create(['nama_produk' => 'Spotify 1 Bulan']);
        $tipe = TipeLayanan::create(['id_produk' => $produk->id_produk, 'nama_tipe' => 'Individual']);
        $varian = VarianLayanan::create(['id_tipe' => $tipe->id_tipe, 'nama_varian' => 'Plan A']);

        $expiredPembelian = Pembelian::create([
            'order_id' => '01M04EXP1234567890ABCDEFGH',
            'id_customer' => $customer->id,
            'id_varian' => $varian->id_varian,
            'harga_saat_beli' => 30000,
            'status' => 'pending',
            'payment_gateway' => 'tripay',
            'reserved_until' => now()->subMinutes(10), // expired!
        ]);

        $this->actingAs($user);
        session(['id' => $user->id]);

        $pembelian = collect([$expiredPembelian]);
        $view = view('premium_customer.riwayat', compact('pembelian'));

        $rendered = $view->render();
        $this->assertStringContainsString('Transaksi Dibatalkan', $rendered);
        $this->assertStringNotContainsString('Selesaikan Pembayaran', $rendered);
    }
}
