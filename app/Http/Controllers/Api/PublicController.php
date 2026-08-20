<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\MitraIndustri;
use App\Models\Testimoni;
use App\Models\SettingWebsite;
use App\Models\SettingKomisi;
use App\Models\News;
use App\Services\Gateways\TriPayGateway;

class PublicController extends ApiController
{
    /**
     * Get Landing page public data
     */
    public function getHome()
    {
        $mitras = MitraIndustri::where('is_active', true)->orderBy('id', 'desc')->get();
        $testimonis = Testimoni::where('is_active', true)->orderBy('id', 'desc')->get();
        
        $settings = SettingWebsite::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Lapaktifikasi',
                'site_description' => 'Platform Jasa Digital',
                'is_midtrans_active' => true,
                'is_tripay_active' => true,
                'is_pakasir_active' => true,
            ]
        );

        $settingKomisi = SettingKomisi::first();
        $isMaintenance = (bool) ($settingKomisi?->is_maintenance ?? false);

        $latestNews = News::published()
            ->with('admin:id,name')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return $this->sendResponse([
            'site_settings' => [
                'site_name' => $settings->site_name,
                'site_description' => $settings->site_description,
                'contact_email' => $settings->contact_email,
                'contact_phone' => $settings->contact_phone,
                'address' => $settings->address,
                'logo_url' => $settings->logo_path ? asset($settings->logo_path) : null,
                'favicon_url' => $settings->favicon_path ? asset($settings->favicon_path) : null,
                'auth_hero_url' => $settings->auth_hero_path ? asset($settings->auth_hero_path) : null,
                'active_gateways' => SettingWebsite::getActiveGateways(),
            ],
            'is_maintenance' => $isMaintenance,
            'mitras' => $mitras,
            'testimonis' => $testimonis,
            'latest_news' => $latestNews,
        ], 'Data home berhasil diambil');
    }

    /**
     * Get published news list
     */
    public function getNews(Request $request)
    {
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);

        $query = News::published()->with('admin:id,name')->orderBy('published_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('subjudul', 'like', '%' . $search . '%')
                  ->orWhere('konten', 'like', '%' . $search . '%');
            });
        }

        $news = $query->paginate($perPage);

        return $this->sendResponse($news, 'Daftar berita berhasil diambil');
    }

    /**
     * Get published news detail by slug
     */
    public function getNewsDetail(string $slug)
    {
        $news = News::published()->with('admin:id,name')->where('slug', $slug)->first();

        if (!$news) {
            return $this->sendError('Berita tidak ditemukan atau belum dipublikasikan', [], 404);
        }

        return $this->sendResponse($news, 'Detail berita berhasil diambil');
    }

    /**
     * Get active payment gateways and TriPay channels
     */
    public function getPaymentChannels()
    {
        $activeGateways = SettingWebsite::getActiveGateways();
        $tripayChannels = [];

        if (in_array('tripay', $activeGateways)) {
            try {
                $gateway = new TriPayGateway();
                $tripayChannels = $gateway->getPaymentChannels();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Api getPaymentChannels error: ' . $e->getMessage());
            }
        }

        return $this->sendResponse([
            'active_gateways' => $activeGateways,
            'tripay_channels' => $tripayChannels,
        ], 'Daftar channel pembayaran berhasil diambil');
    }
}
