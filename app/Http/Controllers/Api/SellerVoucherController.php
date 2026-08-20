<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Toko;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SellerVoucherController extends ApiController
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $perPage = (int) $request->input('per_page', 15);
        $vouchers = Voucher::where('id_toko', $toko->id_toko)->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->sendResponse($vouchers, 'Daftar voucher berhasil diambil');
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:tbl_voucher,kode',
            'nama_voucher' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_diskon' => 'required|in:persen,nominal,persentase',
            'nilai_diskon' => 'required|numeric|min:0',
            'maksimal_potongan' => 'nullable|numeric|min:0',
            'minimal_transaksi' => 'required|numeric|min:0',
            'kuota_total' => 'nullable|integer|min:1',
            'berlaku_dari' => 'nullable|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_dari',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $data = $request->all();
        $data['id_toko'] = $toko->id_toko;
        $data['scope'] = 'toko_spesifik';
        $data['kode'] = strtoupper(trim($data['kode']));
        if ($data['tipe_diskon'] === 'persentase') $data['tipe_diskon'] = 'persen';
        $data['is_active'] = $request->has('is_active') ? $request->is_active : true;

        $voucher = Voucher::create($data);

        return $this->sendResponse($voucher, 'Voucher berhasil ditambahkan', 201);
    }

    public function update(Request $request, $id)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $voucher = Voucher::where('id_voucher', $id)->where('id_toko', $toko->id_toko)->first();
        if (!$voucher) return $this->sendError('Voucher tidak ditemukan atau bukan milik Anda', [], 404);

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:tbl_voucher,kode,'.$id.',id_voucher',
            'nama_voucher' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_diskon' => 'required|in:persen,nominal,persentase',
            'nilai_diskon' => 'required|numeric|min:0',
            'maksimal_potongan' => 'nullable|numeric|min:0',
            'minimal_transaksi' => 'required|numeric|min:0',
            'kuota_total' => 'nullable|integer|min:1',
            'berlaku_dari' => 'nullable|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_dari',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $data = $request->all();
        $data['kode'] = strtoupper(trim($data['kode']));
        if (isset($data['tipe_diskon']) && $data['tipe_diskon'] === 'persentase') $data['tipe_diskon'] = 'persen';
        
        $voucher->update($data);

        return $this->sendResponse($voucher, 'Voucher berhasil diperbarui');
    }

    public function toggleStatus(Request $request, $id)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $voucher = Voucher::where('id_voucher', $id)->where('id_toko', $toko->id_toko)->first();
        if (!$voucher) return $this->sendError('Voucher tidak ditemukan atau bukan milik Anda', [], 404);

        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        return $this->sendResponse($voucher, 'Status voucher berhasil diubah');
    }

    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;
        $toko = Toko::where('user_id', $userId)->first();
        if (!$toko) return $this->sendError('Toko tidak ditemukan', [], 404);

        $voucher = Voucher::where('id_voucher', $id)->where('id_toko', $toko->id_toko)->first();
        if (!$voucher) return $this->sendError('Voucher tidak ditemukan atau bukan milik Anda', [], 404);

        $voucher->delete();

        return $this->sendResponse([], 'Voucher berhasil dihapus');
    }
}
