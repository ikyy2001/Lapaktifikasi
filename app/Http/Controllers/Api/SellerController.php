<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\MutasiSaldo;
use App\Models\SellerBadge;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Enums\PembelianStatus;
use Illuminate\Support\Facades\Validator;

class SellerController extends ApiController
{
    /**
     * Get Seller Dashboard Stats
     */
    public function getDashboard(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();

        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $pendapatan = MutasiSaldo::where('id_toko', $toko->id_toko)
            ->where('tipe', 'masuk_penjualan')
            ->orWhere(function($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko)->where('nominal', '>', 0);
            })
            ->sum('nominal');

        $totalProduk = Produk::where('id_toko', $toko->id_toko)->count();
        $totalProdukPremium = Produk::where('id_toko', $toko->id_toko)->where('tipe_produk', 'premium')->count();
        $totalProdukDigital = Produk::where('id_toko', $toko->id_toko)->where('tipe_produk', 'digital')->count();
        
        $pesananSelesai = Pembelian::whereHas('varianLayanan.tipeLayanan.produk', function ($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })
            ->where('status', PembelianStatus::SUCCESS)
            ->count();

        return $this->sendResponse([
            'nama_toko' => $toko->nama_toko,
            'slug' => $toko->slug,
            'saldo' => (float) $toko->saldo,
            'pendapatan' => (float) $pendapatan,
            'total_produk' => $totalProduk,
            'total_produk_premium' => $totalProdukPremium,
            'total_produk_digital' => $totalProdukDigital,
            'pesanan_selesai' => $pesananSelesai,
            'rating_rata_rata' => (float) $toko->rating_rata_rata,
            'jumlah_review' => (int) $toko->jumlah_review,
        ], 'Dashboard seller berhasil diambil');
    }

    /**
     * Get Mutasi Saldo
     */
    public function getMutasi(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();

        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $perPage = (int) $request->input('per_page', 15);
        $mutasi = MutasiSaldo::with('dibuatOleh:id,name')
            ->where('id_toko', $toko->id_toko)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->sendResponse($mutasi, 'Data mutasi saldo berhasil diambil');
    }

    /**
     * Get Profil Toko
     */
    public function getProfil(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::with('badges')->where('user_id', $userId)->first();

        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        return $this->sendResponse(['toko' => $toko], 'Profil toko berhasil diambil');
    }

    /**
     * Update Profil Toko
     */
    public function updateProfil(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();

        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required|string|max:150',
            'no_telp' => 'required|string|max:20',
            'akun_telegram' => 'required|string|max:100',
            'informasi_toko' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $namaToko = $request->nama_toko;
        $slug = $toko->slug ?: Toko::generateUniqueSlug($namaToko, $toko->id_toko);

        $toko->nama_toko = $namaToko;
        $toko->slug = $slug;
        $toko->no_telp = $request->no_telp;
        $toko->akun_telegram = $request->akun_telegram;
        $toko->informasi_toko = $request->informasi_toko;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $toko->id_toko . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('assets/img/toko');
            if (!file_exists($targetDir)) @mkdir($targetDir, 0755, true);
            $file->move($targetDir, $filename);
            
            if ($toko->logo_toko && file_exists(public_path('assets/img/toko/' . $toko->logo_toko))) {
                @unlink(public_path('assets/img/toko/' . $toko->logo_toko));
            }
            
            $toko->logo_toko = $filename;
        }

        $toko->save();

        return $this->sendResponse(['toko' => $toko], 'Profil toko berhasil diperbarui');
    }

    /**
     * Get Seller Badges
     */
    public function getBadges(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::with('badges')->where('user_id', $userId)->first();

        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $allBadges = SellerBadge::all();

        return $this->sendResponse([
            'toko_badges' => $toko->badges,
            'all_badges' => $allBadges
        ], 'Data badge berhasil diambil');
    }
}
