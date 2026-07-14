<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'tbl_pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pembelian',
        'metode_pembayaran',
        'jumlah_dibayar',
        'midtrans_transaction_id',
        'tanggal_bayar',
        'wa_sent_at',
        'wa_response',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'wa_sent_at' => 'datetime',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }
}
