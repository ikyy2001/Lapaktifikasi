<?php

namespace App\Services;

use App\Models\Toko;
use App\Models\SettingKomisi;

class KomisiService
{
    /**
     * Get the effective commission rate for a given Toko.
     * Fallback to default commission from tbl_setting_komisi if override is null.
     *
     * @param Toko $toko
     * @return float
     */
    public function getKomisiEfektif(Toko $toko): float
    {
        if (!is_null($toko->komisi_override)) {
            return (float) $toko->komisi_override;
        }

        $defaultSetting = SettingKomisi::first();
        return $defaultSetting ? (float) $defaultSetting->komisi_default : 10.00;
    }
}
