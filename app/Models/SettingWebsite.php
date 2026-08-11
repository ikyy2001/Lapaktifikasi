<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingWebsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_description',
        'logo_path',
        'favicon_path',
        'contact_email',
        'contact_phone',
        'address'
    ];
}
