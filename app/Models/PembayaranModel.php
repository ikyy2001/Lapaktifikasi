<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_pembayaran_zip';
    protected $fillable = [
        'order_id',
        'metode_pembayaran',
        'jumlah_pembayaran',
        'status_pembayaran',
        'url_bukti_pembayaran',
    ];

    public $timestamps = false;

    public function beli_produk(): BelongsTo
    {
        return $this->belongsTo(BeliProdukModel::class, 'order_id', 'order_id');
    }
}
