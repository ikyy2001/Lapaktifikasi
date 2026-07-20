<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiSaldo extends Model
{
    use HasFactory;

    protected $table = 'tbl_mutasi_saldo';

    const UPDATED_AT = null;

    protected $fillable = [
        'id_toko',
        'tipe',
        'nominal',
        'saldo_akhir',
        'keterangan',
        'id_beli_produk',
        'dibuat_oleh',
    ];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function beliProduk(): BelongsTo
    {
        return $this->belongsTo(BeliProdukModel::class, 'id_beli_produk', 'id');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id');
    }
}
