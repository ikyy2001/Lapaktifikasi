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
            'digital_file_limit_mb' => 'required|numeric|min:1',
        ];

        $messages = [
            'komisi_default.required' => 'Komisi default harus diisi.',
            'komisi_default.numeric' => 'Komisi default harus berupa angka.',
            'komisi_default.between' => 'Komisi default harus berada di antara 0 dan 100%.',
            'digital_file_limit_mb.required' => 'Batas ukuran file harus diisi.',
            'digital_file_limit_mb.numeric' => 'Batas ukuran file harus berupa angka.',
            'digital_file_limit_mb.min' => 'Batas ukuran file minimal 1 MB.',
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
        $setting->digital_file_limit_mb = $request->input('digital_file_limit_mb');
        $setting->save();

        Session::flash('success', 'Komisi default platform berhasil diperbarui.');
        return redirect('/setting_komisi');
    }

    public function toggleMaintenance(Request $request)
    {
        $setting = SettingKomisi::first();
        if (!$setting) {
            $setting = new SettingKomisi();
        }

        // If is_maintenance is submitted directly in request, use it; otherwise toggle current status
        if ($request->has('is_maintenance')) {
            $setting->is_maintenance = (bool) $request->input('is_maintenance');
        } else {
            $setting->is_maintenance = !$setting->is_maintenance;
        }

        $setting->save();

        $statusText = $setting->is_maintenance ? 'diaktifkan' : 'dinonaktifkan';
        Session::flash('success', "Mode Maintenance platform berhasil {$statusText}.");
        return redirect('/setting_komisi');
    }
}
