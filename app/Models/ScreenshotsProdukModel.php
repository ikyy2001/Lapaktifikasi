<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenshotsProdukModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_screenshots_produk';
    protected $fillable = [
        'folder',
        'produk_id',
    ];
    public $timestamps = false;
}
