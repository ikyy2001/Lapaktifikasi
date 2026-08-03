<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AdminVoucherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Voucher::with(['toko', 'pembuat'])->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('kode', 'like', '%' . strtoupper($search) . '%');
        }

        $vouchers = $query->paginate(15)->withQueryString();

        return view('admin.voucher.index', compact('vouchers'));
    }

    public function create()
    {
        $tokos = Toko::where('status', 'aktif')->orderBy('nama_toko', 'asc')->get();
        return view('admin.voucher.create', compact('tokos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:tbl_voucher,kode',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|numeric|min:0',
            'maksimal_potongan' => 'nullable|numeric|min:0',
            'minimal_transaksi' => 'required|numeric|min:0',
            'kuota_total' => 'nullable|integer|min:1',
            'berlaku_dari' => 'nullable|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_dari',
            'scope' => 'required|in:global,toko_spesifik',
            'id_toko' => 'nullable|required_if:scope,toko_spesifik|exists:tbl_toko,id_toko',
        ]);

        Voucher::create([
            'kode' => strtoupper(trim($request->kode)),
            'tipe_diskon' => $request->tipe_diskon,
            'nilai_diskon' => $request->nilai_diskon,
            'maksimal_potongan' => $request->tipe_diskon === 'persen' ? $request->maksimal_potongan : null,
            'minimal_transaksi' => $request->minimal_transaksi ?? 0,
            'kuota_total' => $request->kuota_total,
            'kuota_terpakai' => 0,
            'berlaku_dari' => $request->berlaku_dari,
            'berlaku_sampai' => $request->berlaku_sampai,
            'scope' => $request->scope,
            'id_toko' => $request->scope === 'toko_spesifik' ? $request->id_toko : null,
            'dibuat_oleh' => auth()->id(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil dibuat!');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $tokos = Toko::where('status', 'aktif')->orderBy('nama_toko', 'asc')->get();
        return view('admin.voucher.edit', compact('voucher', 'tokos'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:50|unique:tbl_voucher,kode,' . $id . ',id_voucher',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|numeric|min:0',
            'maksimal_potongan' => 'nullable|numeric|min:0',
            'minimal_transaksi' => 'required|numeric|min:0',
            'kuota_total' => 'nullable|integer|min:1',
            'berlaku_dari' => 'nullable|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_dari',
            'scope' => 'required|in:global,toko_spesifik',
            'id_toko' => 'nullable|required_if:scope,toko_spesifik|exists:tbl_toko,id_toko',
        ]);

        $voucher->update([
            'kode' => strtoupper(trim($request->kode)),
            'tipe_diskon' => $request->tipe_diskon,
            'nilai_diskon' => $request->nilai_diskon,
            'maksimal_potongan' => $request->tipe_diskon === 'persen' ? $request->maksimal_potongan : null,
            'minimal_transaksi' => $request->minimal_transaksi ?? 0,
            'kuota_total' => $request->kuota_total,
            'berlaku_dari' => $request->berlaku_dari,
            'berlaku_sampai' => $request->berlaku_sampai,
            'scope' => $request->scope,
            'id_toko' => $request->scope === 'toko_spesifik' ? $request->id_toko : null,
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update(['is_active' => !$voucher->is_active]);

        $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Voucher {$voucher->kode} berhasil {$statusText}.");
    }
}
