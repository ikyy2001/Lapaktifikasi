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
        'payment_gateway',
        'jumlah_dibayar',
        'midtrans_transaction_id',
        'tanggal_bayar',
        'wa_sent_at',
        'wa_response',
        'wa_retry_count',
        'wa_last_retry_at',
        'wa_last_retry_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'wa_sent_at' => 'datetime',
        'wa_last_retry_at' => 'datetime',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function lastRetryBy()
    {
        return $this->belongsTo(User::class, 'wa_last_retry_by', 'id');
    }
}
