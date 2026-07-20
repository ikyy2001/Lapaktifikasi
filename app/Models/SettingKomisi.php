<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingKomisi extends Model
{
    use HasFactory;

    protected $table = 'tbl_setting_komisi';

    const CREATED_AT = null;

    protected $fillable = [
        'komisi_default',
    ];
}
