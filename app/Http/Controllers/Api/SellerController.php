<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\MutasiSaldo;
use App\Models\Badge;
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
            ->where('jenis', 'masuk')
            ->sum('jumlah');

        $totalProduk = \App\Models\ProdukModel::where('id_toko', $toko->id_toko)->count();
        
        $pesananSelesai = \App\Models\Pembelian::whereHas('varianLayanan.tipeLayanan.produk', function ($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })
            ->where('status', \App\Enums\PembelianStatus::SUCCESS)
            ->count();

        $rating = $toko->rating_rata_rata;

        return $this->sendResponse([
            'pendapatan' => $pendapatan,
            'total_produk' => $totalProduk,
            'pesanan_selesai' => $pesananSelesai,
            'rating' => $rating,
            'saldo' => $toko->saldo
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

        $perPage = $request->input('per_page', 10);
        $mutasi = MutasiSaldo::where('id_toko', $toko->id_toko)
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
        $toko = Toko::where('user_id', $userId)->first();

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
            'nama_toko' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $toko->nama_toko = $request->nama_toko;
        $toko->deskripsi = $request->deskripsi;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $toko->id_toko . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('assets/img/toko');
            if (!file_exists($targetDir)) mkdir($targetDir, 0755, true);
            $file->move($targetDir, $filename);
            
            if ($toko->logo && file_exists(public_path('assets/img/toko/' . $toko->logo))) {
                unlink(public_path('assets/img/toko/' . $toko->logo));
            }
            
            $toko->logo = $filename;
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

        $allBadges = Badge::orderBy('level', 'asc')->get();

        return $this->sendResponse([
            'toko' => $toko,
            'all_badges' => $allBadges
        ], 'Data badge berhasil diambil');
    }
}
