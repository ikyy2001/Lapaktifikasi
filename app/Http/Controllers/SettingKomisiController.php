<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingKomisi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class SettingKomisiController extends Controller
{
    public function index()
    {
        $setting = SettingKomisi::first();
        if (!$setting) {
            $setting = SettingKomisi::create(['komisi_default' => 10.00]);
        }
        return view('admin.setting_komisi.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $rules = [
            'komisi_default' => 'required|numeric|between:0,100',
        ];

        $messages = [
            'komisi_default.required' => 'Komisi default harus diisi.',
            'komisi_default.numeric' => 'Komisi default harus berupa angka.',
            'komisi_default.between' => 'Komisi default harus berada di antara 0 dan 100%.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/setting_komisi')
                ->withErrors($validator)
                ->withInput();
        }

        $setting = SettingKomisi::first();
        if (!$setting) {
            $setting = new SettingKomisi();
        }
        $setting->komisi_default = $request->input('komisi_default');
        $setting->save();

        Session::flash('success', 'Komisi default platform berhasil diperbarui.');
        return redirect('/setting_komisi');
    }
}
