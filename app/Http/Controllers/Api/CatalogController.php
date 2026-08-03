<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\StokAkun;
use App\Enums\StokStatus;

class CatalogController extends ApiController
{
    /**
     * Get list of active shops
     */
    public function getShops(Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $shops = Toko::where('status', 'aktif')->paginate($perPage);

        return $this->sendResponse($shops, 'Daftar toko berhasil diambil');
    }

    /**
     * Get catalog products, optionally filtered by shop and search query
     */
    public function getCatalog(Request $request)
    {
        $search = $request->input('search');
        $id_toko = $request->input('id_toko');

        $query = Produk::where('status', 'aktif')->where('tipe_produk', 'premium');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        if ($id_toko) {
            $query->where('id_toko', $id_toko);
            
            // Check if shop exists and active
            $toko = Toko::where('id_toko', $id_toko)->where('status', 'aktif')->first();
            if (!$toko) {
                return $this->sendError('Toko tidak ditemukan atau tidak aktif', [], 404);
            }
        }

        $produk = $query->with([
            'toko',
            'tipeLayanan' => function ($query) {
                $query->where('status', 'aktif')
                    ->with([
                        'varianLayanan' => function ($vQuery) {
                            $vQuery->where('status', 'aktif');
                        }
                    ]);
            }
        ])->get();

        // Calculate available stock
        foreach ($produk as $prod) {
            foreach ($prod->tipeLayanan as $tipe) {
                foreach ($tipe->varianLayanan as $varian) {
                    $varian->stok_tersedia = StokAkun::where('id_varian', $varian->id_varian)
                        ->where('status', StokStatus::TERSEDIA)
                        ->count();
                }
            }
        }

        $data = [
            'produk' => $produk
        ];

        if ($id_toko && isset($toko)) {
            $avgRating = \App\Models\Review::where('id_toko', $id_toko)->avg('rating') ?? 0;
            $countReviews = \App\Models\Review::where('id_toko', $id_toko)->count();

            if ((float)$toko->rating_rata_rata !== (float)round($avgRating, 2) || (int)$toko->jumlah_review !== (int)$countReviews) {
                $toko->update([
                    'rating_rata_rata' => round($avgRating, 2),
                    'jumlah_review' => $countReviews,
                ]);
                $toko->refresh();
            }

            $toko->load('badges');
            
            // Get reviews stats
            $reviews = \App\Models\Review::with('customer.user')
                ->where('id_toko', $id_toko)
                ->orderBy('created_at', 'desc')
                ->paginate(5);
                
            $distributionRaw = \App\Models\Review::where('id_toko', $id_toko)
                ->selectRaw('rating, count(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->toArray();

            $ratingDistribution = [];
            for ($i = 1; $i <= 5; $i++) {
                $ratingDistribution[$i] = (int)($distributionRaw[$i] ?? 0);
            }

            $data['toko'] = $toko;
            $data['reviews'] = $reviews;
            $data['rating_distribution'] = $ratingDistribution;
        }

        return $this->sendResponse($data, 'Katalog produk berhasil diambil');
    }

    /**
     * Get specific product detail
     */
    public function getProductDetail($id)
    {
        $produk = Produk::with([
            'toko',
            'tipeLayanan' => function ($query) {
                $query->where('status', 'aktif')
                    ->with([
                        'varianLayanan' => function ($vQuery) {
                            $vQuery->where('status', 'aktif');
                        }
                    ]);
            }
        ])->where('id', $id)->where('status', 'aktif')->first();

        if (!$produk) {
            return $this->sendError('Produk tidak ditemukan', [], 404);
        }

        foreach ($produk->tipeLayanan as $tipe) {
            foreach ($tipe->varianLayanan as $varian) {
                $varian->stok_tersedia = StokAkun::where('id_varian', $varian->id_varian)
                    ->where('status', StokStatus::TERSEDIA)
                    ->count();
            }
        }

        return $this->sendResponse($produk, 'Detail produk berhasil diambil');
    }

    /**
     * Check stock for a specific variant
     */
    public function checkStock($id_varian)
    {
        $stok = StokAkun::where('id_varian', $id_varian)
            ->where('status', StokStatus::TERSEDIA)
            ->count();

        return $this->sendResponse([
            'id_varian' => $id_varian,
            'stok_tersedia' => $stok
        ], 'Stok berhasil dicek');
    }
}
