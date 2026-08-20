<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Enums\StokStatus;
use Illuminate\Support\Facades\Validator;

class SellerProductController extends ApiController
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $perPage = (int) $request->input('per_page', 10);
        $tipeProduk = $request->input('tipe_produk');
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Produk::where('id_toko', $toko->id_toko);

        if ($tipeProduk && in_array($tipeProduk, ['premium', 'digital'])) {
            $query->where('tipe_produk', $tipeProduk);
        }

        if ($status && in_array($status, ['aktif', 'nonaktif'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $produk = $query->withCount(['tipeLayanan'])->orderBy('id', 'desc')->paginate($perPage);

        return $this->sendResponse($produk, 'Daftar produk berhasil diambil');
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $produk = Produk::with([
            'tipeLayanan.varianLayanan'
        ])->where('id', $id)->where('id_toko', $toko->id_toko)->first();

        if (!$produk) return $this->sendError('Produk tidak ditemukan atau bukan milik Anda', [], 404);

        $isDigital = ($produk->tipe_produk === 'digital');
        foreach ($produk->tipeLayanan as $tipe) {
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

        return $this->sendResponse($produk, 'Detail produk berhasil diambil');
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|max:100',
            'tipe_produk' => 'required|in:premium,digital',
            'deskripsi'   => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarName = time() . '_' . $file->getClientOriginalName();
            $targetDir = public_path('assets/img/produk_premium');
            if (!file_exists($targetDir)) @mkdir($targetDir, 0755, true);
            $file->move($targetDir, $gambarName);
        }

        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'tipe_produk' => $request->tipe_produk,
            'deskripsi'   => $request->deskripsi,
            'gambar'      => $gambarName,
            'status'      => $request->status,
            'id_toko'     => $toko->id_toko,
        ]);

        return $this->sendResponse($produk, 'Produk berhasil ditambahkan', 201);
    }

    public function update(Request $request, $id)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $produk = Produk::where('id', $id)->where('id_toko', $toko->id_toko)->first();
        if (!$produk) return $this->sendError('Produk tidak ditemukan atau bukan milik Anda', [], 404);

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|max:100',
            'tipe_produk' => 'sometimes|in:premium,digital',
            'deskripsi'   => 'nullable|string',
            'status'      => 'required|in:aktif,nonaktif',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $produk->nama_produk = $request->nama_produk;
        if ($request->has('tipe_produk')) {
            $produk->tipe_produk = $request->tipe_produk;
        }
        $produk->deskripsi = $request->deskripsi;
        $produk->status = $request->status;

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && file_exists(public_path('assets/img/produk_premium/' . $produk->gambar))) {
                @unlink(public_path('assets/img/produk_premium/' . $produk->gambar));
            }
            $file = $request->file('gambar');
            $gambarName = time() . '_' . $file->getClientOriginalName();
            $targetDir = public_path('assets/img/produk_premium');
            if (!file_exists($targetDir)) @mkdir($targetDir, 0755, true);
            $file->move($targetDir, $gambarName);
            $produk->gambar = $gambarName;
        }

        $produk->save();

        return $this->sendResponse($produk, 'Produk berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $produk = Produk::where('id', $id)->where('id_toko', $toko->id_toko)->first();
        if (!$produk) return $this->sendError('Produk tidak ditemukan atau bukan milik Anda', [], 404);

        try {
            $tipeIds = TipeLayanan::where('id_produk', $produk->id)->pluck('id_tipe');
            $varianIds = VarianLayanan::whereIn('id_tipe', $tipeIds)->pluck('id_varian');
            $hasPurchases = \App\Models\Pembelian::whereIn('id_varian', $varianIds)->exists();

            if ($hasPurchases) {
                StokAkun::whereIn('id_varian', $varianIds)->where('status', StokStatus::TERSEDIA)->delete();
                VarianLayanan::whereIn('id_tipe', $tipeIds)->update(['status' => 'nonaktif']);
                TipeLayanan::where('id_produk', $produk->id)->update(['status' => 'nonaktif']);
                $produk->update(['status' => 'nonaktif']);

                return $this->sendResponse([], 'Produk memiliki riwayat transaksi, status diubah menjadi non-aktif demi keamanan data.');
            }

            StokAkun::whereIn('id_varian', $varianIds)->delete();
            VarianLayanan::whereIn('id_tipe', $tipeIds)->delete();
            TipeLayanan::where('id_produk', $produk->id)->delete();

            if ($produk->gambar && file_exists(public_path('assets/img/produk_premium/' . $produk->gambar))) {
                @unlink(public_path('assets/img/produk_premium/' . $produk->gambar));
            }

            $produk->delete();
            return $this->sendResponse([], 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            $produk->update(['status' => 'nonaktif']);
            return $this->sendResponse([], 'Produk diubah menjadi non-aktif demi keamanan data riwayat transaksi.');
        }
    }
}
