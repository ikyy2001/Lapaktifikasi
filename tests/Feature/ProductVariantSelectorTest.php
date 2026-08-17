<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\ProductVariantSelector;
use App\Services\ProductVariantService;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Enums\StokStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductVariantSelectorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tbl_stok_akun');
        Schema::dropIfExists('tbl_varian_layanan');
        Schema::dropIfExists('tbl_tipe_layanan');
        Schema::dropIfExists('tbl_produk');
        Schema::dropIfExists('tbl_toko');
        Schema::enableForeignKeyConstraints();

        Schema::create('tbl_toko', function (Blueprint $table) {
            $table->id('id_toko');
            $table->string('nama_toko')->default('Toko Test');
            $table->string('slug')->default('toko-test');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('tbl_produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->unsignedBigInteger('id_toko')->nullable();
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('status')->default('aktif');
            $table->string('tipe_produk')->default('premium');
            $table->string('kategori')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_tipe_layanan', function (Blueprint $table) {
            $table->increments('id_tipe');
            $table->unsignedInteger('id_produk');
            $table->string('nama_tipe');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('tbl_varian_layanan', function (Blueprint $table) {
            $table->increments('id_varian');
            $table->unsignedInteger('id_tipe');
            $table->string('nama_varian');
            $table->unsignedInteger('durasi_hari')->default(30);
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('aktif');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        Schema::create('tbl_stok_akun', function (Blueprint $table) {
            $table->bigIncrements('id_stok');
            $table->unsignedInteger('id_varian');
            $table->string('email_username')->nullable();
            $table->text('password_encrypted')->nullable();
            $table->text('catatan')->nullable();
            $table->string('status')->default('tersedia');
            $table->unsignedBigInteger('id_pembelian')->nullable();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('reserved_expired_at')->nullable();
            $table->timestamp('tanggal_terjual')->nullable();
            $table->timestamps();
        });
    }

    public function test_livewire_component_mounts_and_selects_first_available_variant()
    {
        $produk = Produk::create([
            'nama_produk' => 'Netflix Premium 4K',
            'tipe_produk' => 'premium',
            'status' => 'aktif',
        ]);

        $tipe = TipeLayanan::create([
            'id_produk' => $produk->id_produk,
            'nama_tipe' => 'Private 1 Profil',
            'status' => 'aktif',
        ]);

        $v1 = VarianLayanan::create([
            'id_tipe' => $tipe->id_tipe,
            'nama_varian' => '1 Bulan',
            'durasi_hari' => 30,
            'harga' => 35000,
            'status' => 'aktif',
        ]);

        $v2 = VarianLayanan::create([
            'id_tipe' => $tipe->id_tipe,
            'nama_varian' => '3 Bulan',
            'durasi_hari' => 90,
            'harga' => 95000,
            'status' => 'aktif',
        ]);

        // Beri stok untuk varian 1
        StokAkun::create([
            'id_varian' => $v1->id_varian,
            'email_username' => 'acc1@mail.com',
            'status' => StokStatus::TERSEDIA->value,
        ]);
        StokAkun::create([
            'id_varian' => $v1->id_varian,
            'email_username' => 'acc2@mail.com',
            'status' => StokStatus::TERSEDIA->value,
        ]);

        $produk = Produk::with(['tipeLayanan.varianLayanan'])->find($produk->id_produk);

        Livewire::test(ProductVariantSelector::class, ['product' => $produk])
            ->assertSet('selectedVariantId', $v1->id_varian)
            ->assertSet('selectedPrice', 35000.0)
            ->assertSet('formattedPrice', 'Rp 35.000')
            ->assertSet('selectedStock', 2)
            ->assertSet('isAvailable', true)
            ->assertSet('qty', 1)
            ->assertSee('Rp 35.000')
            ->assertSee('1 Bulan')
            ->assertSee('Stok Tersedia (Stok: 2)');
    }

    public function test_switching_variant_updates_price_stock_and_dispatches_event()
    {
        $produk = Produk::create([
            'nama_produk' => 'Spotify Premium',
            'tipe_produk' => 'premium',
            'status' => 'aktif',
        ]);

        $tipe = TipeLayanan::create([
            'id_produk' => $produk->id_produk,
            'nama_tipe' => 'Individual Plan',
            'status' => 'aktif',
        ]);

        $v1 = VarianLayanan::create([
            'id_tipe' => $tipe->id_tipe,
            'nama_varian' => '1 Bulan',
            'harga' => 20000,
            'status' => 'aktif',
        ]);

        $v2 = VarianLayanan::create([
            'id_tipe' => $tipe->id_tipe,
            'nama_varian' => '1 Tahun',
            'harga' => 200000,
            'status' => 'aktif',
        ]);

        for ($i = 0; $i < 5; $i++) {
            StokAkun::create([
                'id_varian' => $v2->id_varian,
                'email_username' => "user{$i}@mail.com",
                'status' => StokStatus::TERSEDIA->value,
            ]);
        }

        $produk = Produk::with(['tipeLayanan.varianLayanan'])->find($produk->id_produk);

        Livewire::test(ProductVariantSelector::class, ['product' => $produk])
            ->call('selectVariant', $v2->id_varian)
            ->assertSet('selectedVariantId', $v2->id_varian)
            ->assertSet('selectedPrice', 200000.0)
            ->assertSet('formattedPrice', 'Rp 200.000')
            ->assertSet('selectedStock', 5)
            ->assertSet('isAvailable', true)
            ->assertDispatched('variant-changed')
            ->assertSee('Rp 200.000')
            ->assertSee('1 Tahun');
    }

    public function test_multi_attribute_variant_switching_validates_combination()
    {
        $produk = Produk::create([
            'nama_produk' => 'YouTube Premium',
            'tipe_produk' => 'premium',
            'status' => 'aktif',
        ]);

        // Tipe 1: Akun Pribadi
        $tipe1 = TipeLayanan::create([
            'id_produk' => $produk->id_produk,
            'nama_tipe' => 'Akun Pribadi',
            'status' => 'aktif',
        ]);
        $v1_1 = VarianLayanan::create([
            'id_tipe' => $tipe1->id_tipe,
            'nama_varian' => '1 Bulan (Pribadi)',
            'harga' => 25000,
            'status' => 'aktif',
        ]);

        // Tipe 2: Akun Family
        $tipe2 = TipeLayanan::create([
            'id_produk' => $produk->id_produk,
            'nama_tipe' => 'Akun Family',
            'status' => 'aktif',
        ]);
        $v2_1 = VarianLayanan::create([
            'id_tipe' => $tipe2->id_tipe,
            'nama_varian' => '1 Bulan (Family)',
            'harga' => 45000,
            'status' => 'aktif',
        ]);

        StokAkun::create(['id_varian' => $v1_1->id_varian, 'status' => StokStatus::TERSEDIA->value]);
        StokAkun::create(['id_varian' => $v2_1->id_varian, 'status' => StokStatus::TERSEDIA->value]);

        $produk = Produk::with(['tipeLayanan.varianLayanan'])->find($produk->id_produk);

        Livewire::test(ProductVariantSelector::class, ['product' => $produk])
            ->assertSet('selectedTipeId', $tipe1->id_tipe)
            ->assertSet('selectedVariantId', $v1_1->id_varian)
            ->call('selectTipe', $tipe2->id_tipe)
            // Harus otomatis beralih ke varian valid dari Tipe 2
            ->assertSet('selectedTipeId', $tipe2->id_tipe)
            ->assertSet('selectedVariantId', $v2_1->id_varian)
            ->assertSet('selectedPrice', 45000.0)
            ->assertSet('formattedPrice', 'Rp 45.000');
    }

    public function test_out_of_stock_variant_disables_actions_and_shows_badge()
    {
        $produk = Produk::create([
            'nama_produk' => 'Disney+ Hotstar',
            'tipe_produk' => 'premium',
            'status' => 'aktif',
        ]);

        $tipe = TipeLayanan::create([
            'id_produk' => $produk->id_produk,
            'nama_tipe' => 'Sharing Device',
            'status' => 'aktif',
        ]);

        $v1 = VarianLayanan::create([
            'id_tipe' => $tipe->id_tipe,
            'nama_varian' => '1 Bulan',
            'harga' => 25000,
            'status' => 'aktif',
        ]);

        // Tidak ada stok (stok = 0)
        $produk = Produk::with(['tipeLayanan.varianLayanan'])->find($produk->id_produk);

        Livewire::test(ProductVariantSelector::class, ['product' => $produk])
            ->assertSet('selectedVariantId', $v1->id_varian)
            ->assertSet('selectedStock', 0)
            ->assertSet('isAvailable', false)
            ->assertSee('Stok Habis')
            ->assertSee('disabled');
    }

    public function test_quantity_stepper_increments_and_decrements_within_stock_limit()
    {
        $produk = Produk::create([
            'nama_produk' => 'Canva Pro',
            'tipe_produk' => 'premium',
            'status' => 'aktif',
        ]);

        $tipe = TipeLayanan::create([
            'id_produk' => $produk->id_produk,
            'nama_tipe' => 'Invite Member',
            'status' => 'aktif',
        ]);

        $v1 = VarianLayanan::create([
            'id_tipe' => $tipe->id_tipe,
            'nama_varian' => '1 Bulan',
            'harga' => 15000,
            'status' => 'aktif',
        ]);

        StokAkun::create(['id_varian' => $v1->id_varian, 'status' => StokStatus::TERSEDIA->value]);
        StokAkun::create(['id_varian' => $v1->id_varian, 'status' => StokStatus::TERSEDIA->value]);

        $produk = Produk::with(['tipeLayanan.varianLayanan'])->find($produk->id_produk);

        Livewire::test(ProductVariantSelector::class, ['product' => $produk])
            ->call('incrementQty')
            ->assertSet('qty', 2)
            ->call('incrementQty') // melebihi batas stok (2)
            ->assertSet('qty', 2)
            ->call('decrementQty')
            ->assertSet('qty', 1)
            ->call('decrementQty') // tidak boleh kurang dari 1
            ->assertSet('qty', 1);
    }
}
