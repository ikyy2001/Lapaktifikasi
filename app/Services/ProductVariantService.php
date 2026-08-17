<?php

namespace App\Services;

use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Enums\StokStatus;

class ProductVariantService
{
    /**
     * Hitung stok akun yang tersedia untuk seluruh varian dalam produk (Batch Query / Bebas N+1).
     *
     * @param Produk $product
     * @return array<int, int> [id_varian => stok_tersedia]
     */
    public function getStockMap(Produk $product): array
    {
        $variantIds = $product->tipeLayanan
            ->flatMap->varianLayanan
            ->pluck('id_varian')
            ->toArray();

        if (empty($variantIds)) {
            return [];
        }

        if ($product->tipe_produk === 'digital') {
            $map = [];
            foreach ($variantIds as $id) {
                $map[$id] = 999;
            }
            return $map;
        }

        $stokCounts = StokAkun::whereIn('id_varian', $variantIds)
            ->where('status', StokStatus::TERSEDIA)
            ->selectRaw('id_varian, count(*) as count')
            ->groupBy('id_varian')
            ->pluck('count', 'id_varian')
            ->toArray();

        $map = [];
        foreach ($variantIds as $id) {
            $map[$id] = (int) ($stokCounts[$id] ?? 0);
        }

        return $map;
    }

    /**
     * Dapatkan stok spesifik untuk satu varian.
     */
    public function getVariantStock(int $variantId, string $tipeProduk = 'premium'): int
    {
        if ($tipeProduk === 'digital') {
            return 999;
        }

        return (int) StokAkun::where('id_varian', $variantId)
            ->where('status', StokStatus::TERSEDIA)
            ->count();
    }

    /**
     * Tentukan kombinasi default (Tipe Layanan + Varian) saat komponen di-mount.
     * Prioritas: Varian aktif pertama yang memiliki stok > 0.
     *
     * @return array{tipe: ?TipeLayanan, varian: ?VarianLayanan}
     */
    public function resolveInitialSelection(Produk $product, array $stockMap): array
    {
        $activeTipes = $product->tipeLayanan->where('status', 'aktif');

        if ($activeTipes->isEmpty()) {
            return ['tipe' => null, 'varian' => null];
        }

        // Cari tipe dan varian pertama yang punya stok
        foreach ($activeTipes as $tipe) {
            $activeVariants = $tipe->varianLayanan->where('status', 'aktif');
            $inStockVariant = $activeVariants->first(fn($v) => ($stockMap[$v->id_varian] ?? 0) > 0);

            if ($inStockVariant) {
                return ['tipe' => $tipe, 'varian' => $inStockVariant];
            }
        }

        // Fallback: ambil tipe pertama dan varian pertamanya meski stok 0
        $firstTipe = $activeTipes->first();
        $firstVariant = $firstTipe?->varianLayanan->where('status', 'aktif')->first();

        return ['tipe' => $firstTipe, 'varian' => $firstVariant];
    }

    /**
     * Validasi kombinasi tipe dan varian layanan.
     * Mencegah invalid state ketika user mengganti Tipe Layanan (Level 1)
     * agar Varian Layanan (Level 2) yang terpilih selalu valid dan sinkron.
     *
     * @param Produk $product
     * @param int $tipeId
     * @param ?int $preferredVariantId
     * @param array $stockMap
     * @return array{tipe: ?TipeLayanan, varian: ?VarianLayanan}
     */
    public function validateAndResolveCombination(
        Produk $product,
        int $tipeId,
        ?int $preferredVariantId,
        array $stockMap
    ): array {
        $tipe = $product->tipeLayanan
            ->where('status', 'aktif')
            ->firstWhere('id_tipe', $tipeId);

        if (!$tipe) {
            // Tipe tidak valid, kembali ke initial
            return $this->resolveInitialSelection($product, $stockMap);
        }

        $activeVariants = $tipe->varianLayanan->where('status', 'aktif');

        if ($activeVariants->isEmpty()) {
            return ['tipe' => $tipe, 'varian' => null];
        }

        // Cek apakah preferredVariantId valid dan memang anak dari $tipeId ini
        if ($preferredVariantId) {
            $matched = $activeVariants->firstWhere('id_varian', $preferredVariantId);
            if ($matched) {
                return ['tipe' => $tipe, 'varian' => $matched];
            }
        }

        // Jika preferredVariantId bukan anak tipe ini, pilih varian pertama yang in-stock di tipe ini
        $firstInStock = $activeVariants->first(fn($v) => ($stockMap[$v->id_varian] ?? 0) > 0);
        $selectedVariant = $firstInStock ?? $activeVariants->first();

        return ['tipe' => $tipe, 'varian' => $selectedVariant];
    }

    /**
     * Format harga ke format mata uang Rupiah standar.
     */
    public function formatRupiah(float|int $nominal): string
    {
        return 'Rp ' . number_format($nominal, 0, ',', '.');
    }

    /**
     * Hitung rentang harga (min - max) untuk seluruh varian produk.
     */
    public function getPriceRange(Produk $product): array
    {
        $prices = $product->tipeLayanan
            ->where('status', 'aktif')
            ->flatMap(fn($t) => $t->varianLayanan->where('status', 'aktif'))
            ->pluck('harga')
            ->map(fn($h) => (float) $h)
            ->toArray();

        if (empty($prices)) {
            return [
                'min' => 0,
                'max' => 0,
                'is_single' => true,
                'formatted' => $this->formatRupiah(0),
            ];
        }

        $min = min($prices);
        $max = max($prices);

        return [
            'min' => $min,
            'max' => $max,
            'is_single' => $min === $max,
            'formatted' => $min === $max
                ? $this->formatRupiah($min)
                : $this->formatRupiah($min) . ' - ' . $this->formatRupiah($max),
        ];
    }

    /**
     * Validasi dan clamp kuantitas belanja berdasarkan batas stok.
     */
    public function clampQuantity(int $requestedQty, int $availableStock): int
    {
        if ($availableStock <= 0) {
            return 1;
        }

        if ($requestedQty < 1) {
            return 1;
        }

        return min($requestedQty, $availableStock);
    }
}
