<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdukModel extends Model
{
    use HasFactory;

    protected $table = "tbl_produk";
    protected $primaryKey = "id_produk";
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'gambar',
        'status',
        'tipe_produk',
        'id_toko',
    ];

    public function produk_terjual(): HasMany
    {
        return $this->hasMany(ProdukTerjualModel::class, 'produk_id');
    }

    public function produk_beli(): HasMany
    {
        return $this->hasMany(BeliProdukModel::class, 'produk_id');
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }
}
