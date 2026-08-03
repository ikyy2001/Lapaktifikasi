<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherKlaim extends Model
{
    use HasFactory;

    protected $table = 'tbl_voucher_klaim';
    protected $primaryKey = 'id_klaim';
    public $timestamps = false;

    protected $fillable = [
        'id_voucher',
        'id_customer',
        'id_pembelian',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'id_voucher', 'id_voucher');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'id_customer', 'id');
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }
}
