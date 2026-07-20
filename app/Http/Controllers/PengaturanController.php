<?php

namespace App\Http\Controllers;

use App\Models\ProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ScreenshotsProdukModel;
use ZipArchive;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('pengaturan.ganti_password');
    }

    public function proses_ganti_password(Request $request)
    {

        $password_baru = $request->input('password_baru');
        $konfirmasi_password_baru = $request->input('konfirmasi_password_baru');

        $rules = [
            'password_baru' => 'required|min:10',
            'konfirmasi_password_baru' => 'required'
        ];

        $messages = [
            'password_baru.required' => 'Password baru harus di isi.',
            'password_baru.min' => 'Password baru setidaknya minimal 10 karakter.',
            'konfirmasi_password_baru.required' => 'Konfirmasi password harus di isi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/ganti_password')
                ->withErrors($validator)
                ->withInput();
        } else if ($password_baru != $konfirmasi_password_baru) {
            Session::flash('error', 'Mohon maaf konfirmasi password tidak sesuai.');
            return redirect('/ganti_password');
        } else {

            $id = $request->session()->get('id');
            $passwordHash = Hash::make($password_baru);
            User::where('id', $id)
                ->update([
                    'password' => $passwordHash,
                    'must_change_password' => false
                ]);

            Session::flash('success', 'Password lama berhasil di ganti.');
            return redirect('/ganti_password');
        }
    }
}
