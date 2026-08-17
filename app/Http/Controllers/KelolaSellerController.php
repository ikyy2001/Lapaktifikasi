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
        $sellers = Toko::with(['user', 'badges'])->get();
        $allBadges = \App\Models\SellerBadge::all();
        return view('admin.kelola_seller.index', compact('sellers', 'allBadges'));
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

            $namaToko = $request->input('nama_toko');
            $slug = Toko::generateUniqueSlug($namaToko);

            Toko::create([
                'user_id' => $user->id,
                'nama_toko' => $namaToko,
                'slug' => $slug,
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
        $namaToko = $request->input('nama_toko');
        $slug = $toko->slug ?: Toko::generateUniqueSlug($namaToko, $toko->id_toko);

        $toko->update([
            'nama_toko' => $namaToko,
            'slug' => $slug,
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

    public function attachBadge(Request $request, $id_toko)
    {
        $request->validate([
            'id_badge' => 'required|exists:tbl_seller_badge,id_badge',
        ]);

        $toko = Toko::findOrFail($id_toko);
        if (!$toko->badges()->where('tbl_toko_badge.id_badge', $request->id_badge)->exists()) {
            $toko->badges()->attach($request->id_badge, ['diperoleh_pada' => now()]);
            Session::flash('success', 'Badge berhasil ditambahkan ke toko ' . $toko->nama_toko . '.');
        } else {
            Session::flash('info', 'Toko sudah memiliki badge tersebut.');
        }

        return redirect('/kelola_seller');
    }

    public function detachBadge(Request $request, $id_toko, $id_badge)
    {
        $toko = Toko::findOrFail($id_toko);
        $toko->badges()->detach($id_badge);

        Session::flash('success', 'Badge berhasil dihapus dari toko ' . $toko->nama_toko . '.');
        return redirect('/kelola_seller');
    }

    public function createCustomBadge(Request $request, $id_toko)
    {
        $request->validate([
            'nama_badge' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_badge.required' => 'Nama badge harus diisi.',
            'nama_badge.max' => 'Nama badge maksimal 150 karakter.',
        ]);

        $toko = Toko::findOrFail($id_toko);

        $badge = \App\Models\SellerBadge::create([
            'nama_badge' => $request->nama_badge,
            'deskripsi' => $request->deskripsi ?? 'Badge khusus dari Admin',
            'kriteria_tipe' => 'custom_admin',
            'kriteria_nilai' => 0,
        ]);

        $toko->badges()->attach($badge->id_badge, ['diperoleh_pada' => now()]);

        Session::flash('success', "Badge custom '{$badge->nama_badge}' berhasil dibuat dan diberikan ke toko {$toko->nama_toko}.");
        return redirect('/kelola_seller');
    }

    public function banSeller(Request $request, $id_toko)
    {
        $request->validate([
            'banned_reason' => 'required|string|max:500',
        ]);

        $toko = Toko::findOrFail($id_toko);
        $toko->update([
            'is_banned' => true,
            'banned_reason' => $request->banned_reason,
            'status' => 'nonaktif',
        ]);

        $toko->user->update([
            'is_banned' => true,
            'banned_reason' => $request->banned_reason,
        ]);

        Session::flash('success', 'Toko dan Seller berhasil dibanned.');
        return redirect('/kelola_seller');
    }

    public function unbanSeller($id_toko)
    {
        $toko = Toko::findOrFail($id_toko);
        $toko->update([
            'is_banned' => false,
            'banned_reason' => null,
            'status' => 'aktif',
        ]);

        $toko->user->update([
            'is_banned' => false,
            'banned_reason' => null,
        ]);

        Session::flash('success', 'Toko dan Seller berhasil di-unban.');
        return redirect('/kelola_seller');
    }
}
