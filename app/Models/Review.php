<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'tbl_review';
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_pembelian',
        'id_toko',
        'id_customer',
        'rating',
        'komentar',
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'id_customer', 'id');
    }
}
