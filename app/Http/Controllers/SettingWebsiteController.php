<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingWebsite;
use Illuminate\Support\Facades\File;

class SettingWebsiteController extends Controller
{
    public function index()
    {
        $settings = SettingWebsite::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Lapaktifikasi',
                'site_description' => 'Platform Jasa Digital',
            ]
        );
        return view('admin.setting_website.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,svg|max:1024',
            'auth_hero' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:3072',
        ]);

        $isMidtrans = $request->boolean('is_midtrans_active');
        $isTripay = $request->boolean('is_tripay_active');
        $isPakasir = $request->boolean('is_pakasir_active');

        if (!$isMidtrans && !$isTripay && !$isPakasir) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['payment_gateways' => 'Minimal salah satu Gateway Pembayaran harus aktif agar pelanggan dapat bertransaksi.']);
        }

        $settings = SettingWebsite::first();

        $settings->site_name = $request->site_name;
        $settings->site_description = $request->site_description;
        $settings->contact_email = $request->contact_email;
        $settings->contact_phone = $request->contact_phone;
        $settings->address = $request->address;

        $settings->is_midtrans_active = $isMidtrans;
        $settings->is_tripay_active = $isTripay;
        $settings->is_pakasir_active = $isPakasir;

        if ($request->hasFile('logo')) {
            if ($settings->logo_path && File::exists(public_path($settings->logo_path))) {
                File::delete(public_path($settings->logo_path));
            }
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('assets/img'), $logoName);
            $settings->logo_path = 'assets/img/' . $logoName;
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path && File::exists(public_path($settings->favicon_path))) {
                File::delete(public_path($settings->favicon_path));
            }
            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('assets/img'), $faviconName);
            $settings->favicon_path = 'assets/img/' . $faviconName;
        }

        if ($request->hasFile('auth_hero')) {
            if ($settings->auth_hero_path && File::exists(public_path($settings->auth_hero_path))) {
                File::delete(public_path($settings->auth_hero_path));
            }
            $authHero = $request->file('auth_hero');
            $authHeroName = 'auth_hero_' . time() . '.' . $authHero->getClientOriginalExtension();
            $authHero->move(public_path('assets/img'), $authHeroName);
            $settings->auth_hero_path = 'assets/img/' . $authHeroName;
        }

        $settings->save();

        \Illuminate\Support\Facades\Cache::forget('setting_website_global');
        \Illuminate\Support\Facades\Cache::forget('api_landing_home');
        \Illuminate\Support\Facades\Cache::forget('api_payment_channels');

        return redirect()->back()->with('success', 'Pengaturan Website berhasil diperbarui!');
    }
}
