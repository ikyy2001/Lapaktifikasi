<?php

namespace Tests\Feature;

use App\Models\CustomerModel;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\SettingWebsite;
use App\Models\TipeLayanan;
use App\Models\User;
use App\Models\VarianLayanan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PaymentGatewaySettingTest extends TestCase
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
        Schema::dropIfExists('setting_websites');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('role')->default('admin');
            $table->unsignedBigInteger('role_id')->default(1);
            $table->timestamps();
        });

        Schema::create('tbl_customer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nomor_telepon')->nullable();
            $table->string('kode_referral')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_setting_komisi', function (Blueprint $table) {
            $table->id('id_setting');
            $table->decimal('persen_komisi', 5, 2)->default(5);
            $table->boolean('is_maintenance')->default(false);
            $table->timestamps();
        });

        Schema::create('setting_websites', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Lapaktifikasi');
            $table->text('site_description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('auth_hero_path')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_midtrans_active')->default(true);
            $table->boolean('is_tripay_active')->default(true);
            $table->boolean('is_pakasir_active')->default(true);
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

    public function test_admin_can_update_payment_gateway_settings()
    {
        $this->withoutExceptionHandling();

        $admin = User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin', 'role_id' => 1]);
        $this->actingAs($admin);
        session(['id' => $admin->id]);

        $settings = SettingWebsite::create([
            'site_name' => 'Lapaktifikasi',
            'is_midtrans_active' => true,
            'is_tripay_active' => true,
            'is_pakasir_active' => true,
        ]);

        $response = $this->post(route('admin.setting_website.update'), [
            'site_name' => 'Lapaktifikasi New',
            'is_midtrans_active' => '1',
            'is_tripay_active' => '1',
            // pakasir not included (disabled)
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $settings->refresh();
        $this->assertTrue($settings->is_midtrans_active);
        $this->assertTrue($settings->is_tripay_active);
        $this->assertFalse($settings->is_pakasir_active);

        $this->assertTrue(SettingWebsite::isGatewayActive('midtrans'));
        $this->assertTrue(SettingWebsite::isGatewayActive('tripay'));
        $this->assertFalse(SettingWebsite::isGatewayActive('pakasir'));
    }

    public function test_admin_cannot_disable_all_payment_gateways()
    {
        $admin = User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin', 'role_id' => 1]);
        $this->actingAs($admin);
        session(['id' => $admin->id]);

        SettingWebsite::create([
            'site_name' => 'Lapaktifikasi',
            'is_midtrans_active' => true,
            'is_tripay_active' => true,
            'is_pakasir_active' => true,
        ]);

        $response = $this->from(route('admin.setting_website'))->post(route('admin.setting_website.update'), [
            'site_name' => 'Lapaktifikasi New',
            // No payment gateways submitted
        ]);

        $response->assertRedirect(route('admin.setting_website'));
        $response->assertSessionHasErrors('payment_gateways');
    }

    public function test_checkout_dropdown_only_renders_active_gateways()
    {
        $user = User::create(['name' => 'Customer User', 'email' => 'cust@example.com', 'role' => 'customer', 'role_id' => 2]);
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

        // Only TriPay is active
        SettingWebsite::create([
            'site_name' => 'Lapaktifikasi',
            'is_midtrans_active' => false,
            'is_tripay_active' => true,
            'is_pakasir_active' => false,
        ]);

        $this->actingAs($user);
        session(['id' => $user->id]);

        $view = view('pembayaran.metode_pembayaran', [
            'produk' => $produk,
            'pathId' => $pembelian->order_id,
            'orderIdProduk' => $pembelian->order_id,
            'user' => $user,
            'nomorTeleponCustomer' => $customer,
            'pembelian' => $pembelian,
            'varian' => $varian,
            'reserved_expired_at' => $pembelian->reserved_until,
            'hasActiveTransaction' => false,
            'tripayActiveDetail' => null,
            'tripayChannels' => [],
        ]);

        $rendered = $view->render();
        $this->assertStringContainsString('value="tripay"', $rendered);
        $this->assertStringNotContainsString('value="midtrans"', $rendered);
        $this->assertStringNotContainsString('value="pakasir"', $rendered);
    }

    public function test_generate_transaksi_rejects_disabled_gateway()
    {
        $user = User::create(['name' => 'Customer User', 'email' => 'cust@example.com', 'role' => 'customer', 'role_id' => 2]);
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

        // Midtrans disabled, TriPay active
        SettingWebsite::create([
            'site_name' => 'Lapaktifikasi',
            'is_midtrans_active' => false,
            'is_tripay_active' => true,
            'is_pakasir_active' => false,
        ]);

        $this->actingAs($user);
        session(['id' => $user->id]);

        $response = $this->postJson(route('metode_pembayaran.generate', $pembelian->order_id), [
            'gateway' => 'midtrans',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
        $this->assertStringContainsString('MIDTRANS', $response->json('error'));
    }
}
