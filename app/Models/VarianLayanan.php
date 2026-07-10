<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianLayanan extends Model
{
    use HasFactory;

    protected $table = 'tbl_varian_layanan';
    protected $primaryKey = 'id_varian';

    protected $fillable = [
        'id_tipe',
        'nama_varian',
        'durasi_hari',
        'harga',
        'deskripsi',
        'status',
    ];

    public function tipeLayanan()
    {
        return $this->belongsTo(TipeLayanan::class, 'id_tipe', 'id_tipe');
    }

    public function stokAkun()
    {
        return $this->hasMany(StokAkun::class, 'id_varian', 'id_varian');
    }
}
