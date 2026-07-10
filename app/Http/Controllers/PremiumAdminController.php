<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\TipeLayanan;
use App\Models\VarianLayanan;
use App\Models\StokAkun;
use App\Models\Pembelian;
use App\Enums\StokStatus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class PremiumAdminController extends Controller
{
    // === 1. CRUD Produk ===
    public function produk_index()
    {
        $produk = Produk::all();
        return view('premium_admin.produk.index', compact('produk'));
    }

    public function produk_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|max:100',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gambarName = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/produk_premium'), $gambarName);
        }

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarName,
            'status' => $request->status,
        ]);

        Session::flash('success', 'Berhasil menambahkan produk premium.');
        return redirect()->route('premium.produk.index');
    }

    public function produk_update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|max:100',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($produk->gambar && file_exists(public_path('assets/img/produk_premium/' . $produk->gambar))) {
                @unlink(public_path('assets/img/produk_premium/' . $produk->gambar));
            }

            $file = $request->file('gambar');
            $gambarName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/produk_premium'), $gambarName);
            $produk->gambar = $gambarName;
        }

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        Session::flash('success', 'Berhasil memperbarui produk premium.');
        return redirect()->route('premium.produk.index');
    }

    // === 2. CRUD Tipe Layanan ===
    public function tipe_index()
    {
        $tipe = TipeLayanan::with('produk')->get();
        $produk = Produk::where('status', 'aktif')->get();
        return view('premium_admin.tipe.index', compact('tipe', 'produk'));
    }

    public function tipe_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_produk' => 'required|exists:tbl_produk,id_produk',
            'nama_tipe' => 'required|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TipeLayanan::create($request->only('id_produk', 'nama_tipe', 'status'));

        Session::flash('success', 'Berhasil menambahkan tipe layanan.');
        return redirect()->route('premium.tipe.index');
    }

    public function tipe_update(Request $request, $id)
    {
        $tipe = TipeLayanan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_produk' => 'required|exists:tbl_produk,id_produk',
            'nama_tipe' => 'required|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tipe->update($request->only('id_produk', 'nama_tipe', 'status'));

        Session::flash('success', 'Berhasil memperbarui tipe layanan.');
        return redirect()->route('premium.tipe.index');
    }

    // === 3. CRUD Varian Layanan ===
    public function varian_index()
    {
        $varian = VarianLayanan::with('tipeLayanan.produk')->get();
        $tipe = TipeLayanan::where('status', 'aktif')->get();
        return view('premium_admin.varian.index', compact('varian', 'tipe'));
    }

    public function varian_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tipe' => 'required|exists:tbl_tipe_layanan,id_tipe',
            'nama_varian' => 'required|max:50',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        VarianLayanan::create($request->only('id_tipe', 'nama_varian', 'durasi_hari', 'harga', 'deskripsi', 'status'));

        Session::flash('success', 'Berhasil menambahkan varian layanan.');
        return redirect()->route('premium.varian.index');
    }

    public function varian_update(Request $request, $id)
    {
        $varian = VarianLayanan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_tipe' => 'required|exists:tbl_tipe_layanan,id_tipe',
            'nama_varian' => 'required|max:50',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $varian->update($request->only('id_tipe', 'nama_varian', 'durasi_hari', 'harga', 'deskripsi', 'status'));

        Session::flash('success', 'Berhasil memperbarui varian layanan.');
        return redirect()->route('premium.varian.index');
    }

    // === 4. Input Stok Akun (Single + Bulk) ===
    public function stok_index()
    {
        $stok = StokAkun::with('varianLayanan.tipeLayanan.produk')->get();
        $varian = VarianLayanan::where('status', 'aktif')->get();
        return view('premium_admin.stok.index', compact('stok', 'varian'));
    }

    public function stok_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_varian' => 'required|exists:tbl_varian_layanan,id_varian',
            'email_username' => 'required|max:150',
            'password' => 'required',
            'catatan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        StokAkun::create([
            'id_varian' => $request->id_varian,
            'email_username' => $request->email_username,
            'password_encrypted' => $request->password, // automatically encrypted via encrypted cast!
            'catatan' => $request->catatan,
            'status' => StokStatus::TERSEDIA,
        ]);

        Session::flash('success', 'Berhasil menambahkan stok akun.');
        return redirect()->route('premium.stok.index');
    }

    public function stok_bulk_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_varian' => 'required|exists:tbl_varian_layanan,id_varian',
            'bulk_data' => 'required', // Format: email_username|password[|catatan] per line
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lines = explode("\n", str_replace("\r", "", $request->bulk_data));
        $count = 0;

        foreach ($lines as $line) {
            $parts = explode("|", trim($line));
            if (count($parts) >= 2) {
                StokAkun::create([
                    'id_varian' => $request->id_varian,
                    'email_username' => trim($parts[0]),
                    'password_encrypted' => trim($parts[1]), // encrypted cast handles it
                    'catatan' => isset($parts[2]) ? trim($parts[2]) : null,
                    'status' => StokStatus::TERSEDIA,
                ]);
                $count++;
            }
        }

        Session::flash('success', "Berhasil menambahkan {$count} stok akun secara bulk.");
        return redirect()->route('premium.stok.index');
    }

    // Explicit Decrypt On-demand Endpoint for Admin Detail (conforming to security guidelines)
    public function stok_decrypt($id)
    {
        $stok = StokAkun::findOrFail($id);
        
        // Decrypted password is read directly from model (Laravel automatically decrypts on attribute access)
        return response()->json([
            'email_username' => $stok->email_username,
            'password' => $stok->password_encrypted,
            'catatan' => $stok->catatan
        ]);
    }

    // === 5. Halaman Histori Penjualan ===
    public function histori_index()
    {
        $stokTerjual = StokAkun::with(['varianLayanan.tipeLayanan.produk', 'pembelian.customer.user'])
            ->where('status', StokStatus::TERJUAL)
            ->orderBy('tanggal_terjual', 'desc')
            ->get();

        return view('premium_admin.histori.index', compact('stokTerjual'));
    }
}
