<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeLayanan extends Model
{
    use HasFactory;

    protected $table = 'tbl_tipe_layanan';
    protected $primaryKey = 'id_tipe';

    protected $fillable = [
        'id_produk',
        'nama_tipe',
        'status',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function varianLayanan()
    {
        return $this->hasMany(VarianLayanan::class, 'id_tipe', 'id_tipe');
    }
}
