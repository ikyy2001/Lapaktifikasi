<?php

namespace Database\Seeders;

use App\Models\CustomerModel;
use App\Models\CustomerTier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerBackfillSeeder extends Seeder
{
    public function run(): void
    {
        $bronze = CustomerTier::orderBy('urutan', 'asc')->first();
        $customers = CustomerModel::all();

        foreach ($customers as $c) {
            if (empty($c->kode_referral)) {
                $c->kode_referral = 'REF-' . strtoupper(Str::random(6));
            }
            if (empty($c->id_tier_saat_ini) && $bronze) {
                $c->id_tier_saat_ini = $bronze->id_tier;
            }
            $c->save();
        }
    }
}
