<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\StokAkun;
use App\Models\VarianLayanan;
use App\Models\Review;
use App\Enums\StokStatus;
use Illuminate\Support\Facades\DB;

class CatalogController extends ApiController
{
    /**
     * Get list of active shops
     */
    public function getShops(Request $request)
    {
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 12);

        $query = Toko::where('status', 'aktif')->where('is_banned', false)->with('badges');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_toko', 'like', '%' . $search . '%')
                  ->orWhere('informasi_toko', 'like', '%' . $search . '%');
            });
        }

        $shops = $query->paginate($perPage);

        return $this->sendResponse($shops, 'Daftar toko berhasil diambil');
    }

    /**
     * Get single shop profile detail by slug or ID
     */
    public function getShopDetail($identifier)
    {
        $query = Toko::with(['badges', 'user:id,name,profile_picture']);

        if (is_numeric($identifier)) {
            $query->where('id_toko', $identifier);
        } else {
            $query->where('slug', $identifier);
        }

        $toko = $query->first();

        if (!$toko || $toko->status !== 'aktif') {
            return $this->sendError('Toko tidak ditemukan atau tidak aktif', [], 404);
        }

        if ($toko->is_banned) {
            return $this->sendError('Toko sedang dinonaktifkan/dibanned: ' . ($toko->banned_reason ?? 'Pelanggaran ketentuan layanan.'), [
                'is_banned' => true,
                'banned_reason' => $toko->banned_reason
            ], 403);
        }

        // Sync ratings
        $avgRating = Review::where('id_toko', $toko->id_toko)->avg('rating') ?? 0;
        $countReviews = Review::where('id_toko', $toko->id_toko)->count();

        if ((float)$toko->rating_rata_rata !== (float)round($avgRating, 2) || (int)$toko->jumlah_review !== (int)$countReviews) {
            $toko->update([
                'rating_rata_rata' => round($avgRating, 2),
                'jumlah_review' => $countReviews,
            ]);
            $toko->refresh();
        }

        // Rating distribution
        $distributionRaw = Review::where('id_toko', $toko->id_toko)
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = (int)($distributionRaw[$i] ?? 0);
        }

        // Recent reviews
        $reviews = Review::with('customer.user:id,name,profile_picture')
            ->where('id_toko', $toko->id_toko)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->sendResponse([
            'toko' => $toko,
            'rating_distribution' => $ratingDistribution,
            'reviews' => $reviews,
        ], 'Detail toko berhasil diambil');
    }

    /**
     * Get catalog products with multi-category & store filtering
     */
    public function getCatalog(Request $request)
    {
        $search = $request->input('search');
        $tokoParam = $request->input('toko') ?? $request->input('id_toko');
        $kategori = $request->input('kategori') ?? $request->input('tipe_produk'); // 'all', 'premium', 'digital'
        $perPage = (int) $request->input('per_page', 20);

        $query = Produk::where('status', 'aktif')
            ->whereHas('toko', function($q) {
                $q->where('status', 'aktif')->where('is_banned', false);
            });

        if ($kategori && in_array($kategori, ['premium', 'digital'])) {
            $query->where('tipe_produk', $kategori);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $toko = null;
        if ($tokoParam) {
            if (is_numeric($tokoParam)) {
                $toko = Toko::where('id_toko', $tokoParam)->first();
            } else {
                $toko = Toko::where('slug', $tokoParam)->first();
            }

            if (!$toko || $toko->status !== 'aktif' || $toko->is_banned) {
                return $this->sendError('Toko tidak ditemukan atau tidak aktif', [], 404);
            }

            $query->where('id_toko', $toko->id_toko);
        }

        $produk = $query->with([
            'toko.badges',
            'tipeLayanan' => function ($query) {
                $query->where('status', 'aktif')
                    ->with([
                        'varianLayanan' => function ($vQuery) {
                            $vQuery->where('status', 'aktif');
                        }
                    ]);
            }
        ])->paginate($perPage);

        // Calculate available stock for each variant
        foreach ($produk as $prod) {
            $isDigital = ($prod->tipe_produk === 'digital');
            foreach ($prod->tipeLayanan as $tipe) {
                foreach ($tipe->varianLayanan as $varian) {
                    if ($isDigital) {
                        $varian->stok_tersedia = 999;
                    } else {
                        $varian->stok_tersedia = StokAkun::where('id_varian', $varian->id_varian)
                            ->where('status', StokStatus::TERSEDIA)
                            ->count();
                    }
                }
            }
        }

        $data = [
            'produk' => $produk
        ];

        if ($toko) {
            $data['toko'] = $toko;
        }

        return $this->sendResponse($data, 'Katalog produk berhasil diambil');
    }

    /**
     * Get specific product detail by ID or Slug
     */
    public function getProductDetail($identifier)
    {
        $query = Produk::with([
            'toko.badges',
            'tipeLayanan' => function ($query) {
                $query->where('status', 'aktif')
                    ->with([
                        'varianLayanan' => function ($vQuery) {
                            $vQuery->where('status', 'aktif');
                        }
                    ]);
            }
        ])->where('status', 'aktif');

        if (is_numeric($identifier)) {
            $query->where('id', $identifier);
        } else {
            // Slug matching or ID embedded at the end
            if (preg_match('/-(\d+)$/', $identifier, $matches)) {
                $productId = (int) $matches[1];
                $query->where('id', $productId);
            } else {
                $query->where(function ($q) use ($identifier) {
                    $q->whereRaw("LOWER(REPLACE(nama_produk, ' ', '-')) = ?", [strtolower($identifier)]);
                });
            }
        }

        $produk = $query->first();

        if (!$produk) {
            return $this->sendError('Produk tidak ditemukan atau sudah tidak aktif', [], 404);
        }

        if ($produk->toko && ($produk->toko->status !== 'aktif' || $produk->toko->is_banned)) {
            return $this->sendError('Toko pemilik produk ini sedang tidak aktif atau dinonaktifkan', [], 403);
        }

        $isDigital = ($produk->tipe_produk === 'digital');
        $minPrice = null;
        $maxPrice = null;

        foreach ($produk->tipeLayanan as $tipe) {
            foreach ($tipe->varianLayanan as $varian) {
                if ($isDigital) {
                    $varian->stok_tersedia = 999;
                } else {
                    $varian->stok_tersedia = StokAkun::where('id_varian', $varian->id_varian)
                        ->where('status', StokStatus::TERSEDIA)
                        ->count();
                }

                $harga = (float) $varian->harga;
                if ($minPrice === null || $harga < $minPrice) $minPrice = $harga;
                if ($maxPrice === null || $harga > $maxPrice) $maxPrice = $harga;
            }
        }

        // Gallery
        $gallery = [];
        if ($produk->gambar) {
            $gallery[] = asset('assets/img/produk_premium/' . $produk->gambar);
        }
        $screenshots = DB::table('tbl_screenshots_produk')->where('produk_id', $produk->id)->get();
        foreach ($screenshots as $ss) {
            if (isset($ss->folder) && isset($ss->gambar)) {
                $gallery[] = asset('assets/' . $ss->folder . '/' . $ss->gambar);
            }
        }

        $result = $produk->toArray();
        $result['min_price'] = $minPrice ?? 0;
        $result['max_price'] = $maxPrice ?? 0;
        $result['gallery'] = $gallery;

        return $this->sendResponse($result, 'Detail produk berhasil diambil');
    }

    /**
     * Check stock for a specific variant
     */
    public function checkStock($id_varian)
    {
        $varian = VarianLayanan::with('tipeLayanan.produk')->find($id_varian);
        if (!$varian) {
            return $this->sendError('Varian tidak ditemukan', [], 404);
        }

        $isDigital = ($varian->tipeLayanan?->produk?->tipe_produk === 'digital');

        if ($isDigital) {
            return $this->sendResponse([
                'id_varian' => $id_varian,
                'tipe_produk' => 'digital',
                'stok_tersedia' => 999
            ], 'Stok digital tersedia');
        }

        $stok = StokAkun::where('id_varian', $id_varian)
            ->where('status', StokStatus::TERSEDIA)
            ->count();

        return $this->sendResponse([
            'id_varian' => $id_varian,
            'tipe_produk' => 'premium',
            'stok_tersedia' => $stok
        ], 'Stok berhasil dicek');
    }
}
