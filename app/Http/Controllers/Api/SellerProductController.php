<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProdukModel;
use App\Models\Toko;
use Illuminate\Support\Facades\Validator;

class SellerProductController extends ApiController
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $perPage = $request->input('per_page', 10);
        $produk = ProdukModel::where('id_toko', $toko->id_toko)->paginate($perPage);

        return $this->sendResponse($produk, 'Daftar produk berhasil diambil');
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|max:100',
            'deskripsi'   => 'nullable',
            'status'      => 'required|in:aktif,nonaktif',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/produk_premium'), $gambarName);
        }

        $produk = ProdukModel::create([
            'nama_produk' => $request->nama_produk,
            'tipe_produk' => 'premium',
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

        $produk = ProdukModel::where('id', $id)->where('id_toko', $toko->id_toko)->first();
        if (!$produk) return $this->sendError('Produk tidak ditemukan atau bukan milik Anda', [], 404);

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|max:100',
            'deskripsi'   => 'nullable',
            'status'      => 'required|in:aktif,nonaktif',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        $produk->status = $request->status;

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && file_exists(public_path('assets/img/produk_premium/' . $produk->gambar))) {
                @unlink(public_path('assets/img/produk_premium/' . $produk->gambar));
            }
            $file = $request->file('gambar');
            $gambarName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/produk_premium'), $gambarName);
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

        $produk = ProdukModel::where('id', $id)->where('id_toko', $toko->id_toko)->first();
        if (!$produk) return $this->sendError('Produk tidak ditemukan atau bukan milik Anda', [], 404);

        if ($produk->gambar && file_exists(public_path('assets/img/produk_premium/' . $produk->gambar))) {
            @unlink(public_path('assets/img/produk_premium/' . $produk->gambar));
        }

        $produk->delete();

        return $this->sendResponse([], 'Produk berhasil dihapus');
    }
}
