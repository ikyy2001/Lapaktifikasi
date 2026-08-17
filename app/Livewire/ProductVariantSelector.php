<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Services\ProductVariantService;

class ProductVariantSelector extends Component
{
    public Produk $product;

    // State Kombinasi Varian
    public ?int $selectedTipeId = null;
    public ?string $selectedTipeName = null;
    public ?int $selectedVariantId = null;
    public ?string $selectedVariantName = null;
    public ?int $selectedDurasiHari = null;

    // State Harga & Stok
    public float $selectedPrice = 0;
    public string $formattedPrice = '';
    public int $selectedStock = 0;
    public bool $isAvailable = false;
    public int $qty = 1;

    // Cache State & Range
    public array $stockMap = [];
    public array $priceRange = [];

    /**
     * Mencegah N+1 Query saat Livewire rehidrasi state di request berikutnya.
     */
    public function hydrate(): void
    {
        $this->ensureEagerLoaded();
    }

    public function mount(Produk $product, ?ProductVariantService $service = null): void
    {
        $service = $service ?? app(ProductVariantService::class);
        $this->product = $product;
        $this->ensureEagerLoaded();

        // 1. Ambil Stock Map (Batch Query - Bebas N+1)
        $this->stockMap = $service->getStockMap($this->product);
        $this->priceRange = $service->getPriceRange($this->product);

        // 2. Tentukan Kombinasi Awal (Tipe + Varian yang tersedia)
        $initial = $service->resolveInitialSelection($this->product, $this->stockMap);

        if ($initial['tipe'] && $initial['varian']) {
            $this->applySelection($initial['tipe'], $initial['varian'], $service);
        }
    }

    /**
     * User memilih Tipe Layanan (Level 1, misal: Akun Private vs Sharing).
     * Memvalidasi kombinasi agar varian yang terpilih otomatis disinkronkan ke varian valid dari tipe ini.
     */
    public function selectTipe(int $tipeId, ?ProductVariantService $service = null): void
    {
        $service = $service ?? app(ProductVariantService::class);
        $this->ensureEagerLoaded();

        $resolved = $service->validateAndResolveCombination(
            $this->product,
            $tipeId,
            $this->selectedVariantId,
            $this->stockMap
        );

        if ($resolved['tipe'] && $resolved['varian']) {
            $this->applySelection($resolved['tipe'], $resolved['varian'], $service);
        }
    }

    /**
     * User memilih Varian Layanan langsung (Level 2, misal: 1 Bulan, 3 Bulan).
     */
    public function selectVariant(int $variantId, ?ProductVariantService $service = null): void
    {
        $service = $service ?? app(ProductVariantService::class);
        $this->ensureEagerLoaded();

        // Cari varian di dalam relasi yang sudah di-eager load
        $varian = $this->product->tipeLayanan
            ->flatMap(fn($t) => $t->varianLayanan)
            ->firstWhere('id_varian', $variantId);

        if (!$varian) {
            return;
        }

        $tipe = $this->product->tipeLayanan->firstWhere('id_tipe', $varian->id_tipe);

        if ($tipe) {
            $this->applySelection($tipe, $varian, $service);
        }
    }

    /**
     * Terapkan state seleksi dan dispatch browser event.
     */
    protected function applySelection(TipeLayanan $tipe, VarianLayanan $varian, ProductVariantService $service): void
    {
        $this->selectedTipeId = $tipe->id_tipe;
        $this->selectedTipeName = $tipe->nama_tipe;

        $this->selectedVariantId = $varian->id_varian;
        $this->selectedVariantName = $varian->nama_varian;
        $this->selectedDurasiHari = $varian->durasi_hari;

        $this->selectedPrice = (float) $varian->harga;
        $this->formattedPrice = $service->formatRupiah($this->selectedPrice);

        $this->selectedStock = (int) ($this->stockMap[$varian->id_varian] ?? 0);
        $this->isAvailable = $this->selectedStock > 0;

        // Validasi kuantitas saat varian berganti
        $this->qty = $service->clampQuantity($this->qty, $this->selectedStock);

        // Dispatch browser event untuk integrasi external (misal update galeri gambar atau tracking)
        $this->dispatch('variant-changed', [
            'id_tipe' => $this->selectedTipeId,
            'nama_tipe' => $this->selectedTipeName,
            'id_varian' => $this->selectedVariantId,
            'nama_varian' => $this->selectedVariantName,
            'durasi_hari' => $this->selectedDurasiHari,
            'harga' => $this->selectedPrice,
            'harga_formatted' => $this->formattedPrice,
            'stok' => $this->selectedStock,
            'is_available' => $this->isAvailable,
        ]);
    }

    public function incrementQty(?ProductVariantService $service = null): void
    {
        $service = $service ?? app(ProductVariantService::class);
        $this->qty = $service->clampQuantity($this->qty + 1, $this->selectedStock);
    }

    public function decrementQty(?ProductVariantService $service = null): void
    {
        $service = $service ?? app(ProductVariantService::class);
        $this->qty = $service->clampQuantity($this->qty - 1, $this->selectedStock);
    }

    public function updatedQty($value, ?ProductVariantService $service = null): void
    {
        $service = $service ?? app(ProductVariantService::class);
        $requested = is_numeric($value) ? (int) $value : 1;
        $this->qty = $service->clampQuantity($requested, $this->selectedStock);
    }

    /**
     * Memastikan seluruh relasi tipe & varian selalu ter-eager load tanpa N+1.
     */
    protected function ensureEagerLoaded(): void
    {
        $this->product->loadMissing([
            'tipeLayanan' => function ($query) {
                $query->where('status', 'aktif')
                    ->with([
                        'varianLayanan' => function ($vQuery) {
                            $vQuery->where('status', 'aktif');
                        }
                    ]);
            }
        ]);
    }

    public function render()
    {
        return view('livewire.product-variant-selector');
    }
}
