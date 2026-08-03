<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Toko;
use App\Models\Voucher;
use App\Models\SettingKomisi;
use App\Models\MutasiSaldo;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends ApiController
{
    /**
     * Dashboard Admin
     */
    public function getDashboard()
    {
        // Simple aggregate stats for Admin
        $totalToko = Toko::count();
        $totalLaporan = Laporan::count();
        $laporanPending = Laporan::where('status', 'pending')->count();
        $totalVoucherAdmin = Voucher::where('scope', 'semua_toko')->count();

        return $this->sendResponse([
            'total_toko' => $totalToko,
            'total_laporan' => $totalLaporan,
            'laporan_pending' => $laporanPending,
            'total_voucher_admin' => $totalVoucherAdmin
        ], 'Dashboard admin berhasil diambil');
    }

    /**
     * Kelola Seller
     */
    public function getSellers(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sellers = Toko::with('user')->paginate($perPage);

        return $this->sendResponse($sellers, 'Data seller berhasil diambil');
    }

    public function toggleSellerStatus($id)
    {
        $toko = Toko::findOrFail($id);
        $toko->status = $toko->status === 'aktif' ? 'nonaktif' : 'aktif';
        $toko->save();

        return $this->sendResponse($toko, 'Status toko berhasil diubah');
    }

    /**
     * Laporan Admin
     */
    public function getLaporan(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $laporan = Laporan::with('user.customer')->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->sendResponse($laporan, 'Data laporan berhasil diambil');
    }

    public function updateLaporanStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,proses,selesai'
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => $request->status]);

        return $this->sendResponse($laporan, 'Status laporan berhasil diubah');
    }

    /**
     * Setting Komisi
     */
    public function getSettingKomisi()
    {
        $setting = SettingKomisi::first();
        return $this->sendResponse($setting, 'Setting komisi berhasil diambil');
    }

    public function updateSettingKomisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'persentase_komisi' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) return $this->sendError('Validasi gagal', $validator->errors()->toArray(), 422);

        $setting = SettingKomisi::first();
        if (!$setting) {
            $setting = new SettingKomisi();
        }
        $setting->persentase_komisi = $request->persentase_komisi;
        $setting->save();

        return $this->sendResponse($setting, 'Setting komisi berhasil diperbarui');
    }

    /**
     * Voucher Admin
     */
    public function getVoucherAdmin(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $vouchers = Voucher::where('scope', 'semua_toko')->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->sendResponse($vouchers, 'Data voucher admin berhasil diambil');
    }

    public function storeVoucherAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:tbl_voucher,kode',
            'nama_voucher' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_diskon' => 'required|in:persen,nominal',
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
        $data['scope'] = 'semua_toko';
        $data['kode'] = strtoupper($data['kode']);
        $data['is_active'] = $request->has('is_active') ? $request->is_active : true;

        $voucher = Voucher::create($data);

        return $this->sendResponse($voucher, 'Voucher berhasil ditambahkan', 201);
    }

    public function updateVoucherAdmin(Request $request, $id)
    {
        $voucher = Voucher::where('scope', 'semua_toko')->where('id_voucher', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:tbl_voucher,kode,'.$id.',id_voucher',
            'nama_voucher' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_diskon' => 'required|in:persen,nominal',
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
        $data['kode'] = strtoupper($data['kode']);
        
        $voucher->update($data);

        return $this->sendResponse($voucher, 'Voucher berhasil diperbarui');
    }

    public function destroyVoucherAdmin($id)
    {
        $voucher = Voucher::where('scope', 'semua_toko')->where('id_voucher', $id)->firstOrFail();
        $voucher->delete();

        return $this->sendResponse([], 'Voucher berhasil dihapus');
    }
}
