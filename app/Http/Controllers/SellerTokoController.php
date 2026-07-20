<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class SellerTokoController extends Controller
{
    public function index()
    {
        $toko = Toko::where('user_id', Auth::id())->first();
        if (!$toko) {
            abort(404, 'Toko Anda tidak ditemukan. Hubungi Admin.');
        }
        return view('seller.profil', compact('toko'));
    }

    public function update(Request $request)
    {
        $toko = Toko::where('user_id', Auth::id())->firstOrFail();

        $rules = [
            'nama_toko' => 'required|string|max:150',
            'no_telp' => 'required|string|max:20',
            'akun_telegram' => 'required|string|max:100',
            'informasi_toko' => 'nullable|string',
            'logo_toko' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $messages = [
            'nama_toko.required' => 'Nama Toko harus diisi.',
            'no_telp.required' => 'Nomor Telepon harus diisi.',
            'akun_telegram.required' => 'Akun Telegram harus diisi.',
            'logo_toko.image' => 'Logo harus berupa gambar.',
            'logo_toko.mimes' => 'Format gambar logo harus jpeg, png, jpg, atau webp.',
            'logo_toko.max' => 'Ukuran gambar logo maksimal 2MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/seller/profil')
                ->withErrors($validator)
                ->withInput();
        }

        $logoName = $toko->logo_toko;
        if ($request->hasFile('logo_toko')) {
            // Delete old logo if exists
            if ($toko->logo_toko && file_exists(public_path('assets/img/logo_toko/' . $toko->logo_toko))) {
                @unlink(public_path('assets/img/logo_toko/' . $toko->logo_toko));
            }

            $file = $request->file('logo_toko');
            $logoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/logo_toko'), $logoName);
        }

        $toko->update([
            'nama_toko' => $request->input('nama_toko'),
            'no_telp' => $request->input('no_telp'),
            'akun_telegram' => $request->input('akun_telegram'),
            'informasi_toko' => $request->input('informasi_toko'),
            'logo_toko' => $logoName,
        ]);

        Session::flash('success', 'Profil toko Anda berhasil diperbarui.');
        return redirect('/seller/profil');
    }
}
