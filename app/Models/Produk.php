<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'tbl_produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'gambar',
        'status',
    ];

    public function tipeLayanan()
    {
        return $this->hasMany(TipeLayanan::class, 'id_produk', 'id_produk');
    }
}
