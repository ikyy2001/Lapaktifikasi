<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class KelolaSellerController extends Controller
{
    public function index()
    {
        $sellers = Toko::with('user')->get();
        return view('admin.kelola_seller.index', compact('sellers'));
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:10',
            'nama_toko' => 'required|string|max:150',
            'no_telp' => 'required|string|max:20',
            'akun_telegram' => 'required|string|max:100',
            'informasi_toko' => 'nullable|string',
            'komisi_override' => 'nullable|numeric|between:0,100',
        ];

        $messages = [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password setidaknya minimal 10 karakter.',
            'nama_toko.required' => 'Nama Toko harus diisi.',
            'no_telp.required' => 'Nomor Telepon harus diisi.',
            'akun_telegram.required' => 'Akun Telegram harus diisi.',
            'komisi_override.numeric' => 'Komisi override harus berupa angka.',
            'komisi_override.between' => 'Komisi override harus di antara 0 dan 100%.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/kelola_seller')
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'profile_picture' => 'avatar-1.png',
                'role_id' => 3,
                'must_change_password' => true,
            ]);

            Toko::create([
                'user_id' => $user->id,
                'nama_toko' => $request->input('nama_toko'),
                'no_telp' => $request->input('no_telp'),
                'akun_telegram' => $request->input('akun_telegram'),
                'informasi_toko' => $request->input('informasi_toko'),
                'logo_toko' => null,
                'komisi_override' => $request->input('komisi_override'),
                'saldo' => 0,
                'status' => 'aktif',
            ]);
        });

        Session::flash('success', 'Seller dan Toko baru berhasil dibuat.');
        return redirect('/kelola_seller');
    }

    public function update(Request $request, $id_toko)
    {
        $rules = [
            'nama_toko' => 'required|string|max:150',
            'no_telp' => 'required|string|max:20',
            'akun_telegram' => 'required|string|max:100',
            'informasi_toko' => 'nullable|string',
            'komisi_override' => 'nullable|numeric|between:0,100',
        ];

        $messages = [
            'nama_toko.required' => 'Nama Toko harus diisi.',
            'no_telp.required' => 'Nomor Telepon harus diisi.',
            'akun_telegram.required' => 'Akun Telegram harus diisi.',
            'komisi_override.numeric' => 'Komisi override harus berupa angka.',
            'komisi_override.between' => 'Komisi override harus di antara 0 dan 100%.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/kelola_seller')
                ->withErrors($validator)
                ->withInput();
        }

        $toko = Toko::findOrFail($id_toko);
        $toko->update([
            'nama_toko' => $request->input('nama_toko'),
            'no_telp' => $request->input('no_telp'),
            'akun_telegram' => $request->input('akun_telegram'),
            'informasi_toko' => $request->input('informasi_toko'),
            'komisi_override' => $request->input('komisi_override'),
        ]);

        Session::flash('success', 'Informasi Toko berhasil diperbarui.');
        return redirect('/kelola_seller');
    }

    public function toggleStatus($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        $newStatus = $toko->status == 'aktif' ? 'nonaktif' : 'aktif';
        $toko->update(['status' => $newStatus]);

        Session::flash('success', 'Status Toko berhasil diubah menjadi ' . $newStatus . '.');
        return redirect('/kelola_seller');
    }
}
