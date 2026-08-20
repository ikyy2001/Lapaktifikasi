<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SettingWebsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_description',
        'logo_path',
        'favicon_path',
        'auth_hero_path',
        'contact_email',
        'contact_phone',
        'address',
        'is_midtrans_active',
        'is_tripay_active',
        'is_pakasir_active',
    ];

    protected $casts = [
        'is_midtrans_active' => 'boolean',
        'is_tripay_active' => 'boolean',
        'is_pakasir_active' => 'boolean',
    ];

    /**
     * Get cached site settings
     */
    public static function getCached(): ?self
    {
        return Cache::remember('setting_website_global', 3600, function () {
            return self::first();
        });
    }

    /**
     * Get list of currently active payment gateway slugs.
     *
     * @return array
     */
    public static function getActiveGateways(): array
    {
        $settings = self::getCached();
        if (!$settings) {
            return ['midtrans', 'tripay', 'pakasir'];
        }

        $active = [];
        if ($settings->is_midtrans_active) {
            $active[] = 'midtrans';
        }
        if ($settings->is_tripay_active) {
            $active[] = 'tripay';
        }
        if ($settings->is_pakasir_active) {
            $active[] = 'pakasir';
        }

        return $active;
    }

    /**
     * Check if a specific payment gateway is active.
     *
     * @param string $gateway
     * @return bool
     */
    public static function isGatewayActive(string $gateway): bool
    {
        $gateway = strtolower(trim($gateway));
        $active = self::getActiveGateways();

        return in_array($gateway, $active, true);
    }
}
