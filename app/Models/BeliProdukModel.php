<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeliProdukModel extends Model
{
    use HasFactory;

    protected $table = "tbl_beli_produk";
    protected $fillable = [
        'order_id',
        'user_id',
        'produk_id',
        'nama_pembeli',
        'email_pembeli',
        'nomor_telepon',
        'harga_beli',
        'status_pembelian',
        'id_toko',
    ];

    public $timestamps = false;

    public function produk(): BelongsTo
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id', 'id_produk');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranModel::class, 'order_id', 'order_id');
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }
}
