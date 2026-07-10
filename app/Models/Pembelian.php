<?php

namespace App\Models;

use App\Enums\PembelianStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'tbl_pembelian';
    protected $primaryKey = 'id_pembelian';

    protected $fillable = [
        'order_id',
        'id_customer',
        'id_varian',
        'id_stok',
        'harga_saat_beli',
        'status',
        'reserved_until',
    ];

    protected $casts = [
        'status' => PembelianStatus::class,
        'reserved_until' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($pembelian) {
            if (empty($pembelian->order_id)) {
                $pembelian->order_id = (string) Str::ulid();
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'id_customer', 'id');
    }

    public function varianLayanan()
    {
        return $this->belongsTo(VarianLayanan::class, 'id_varian', 'id_varian');
    }

    public function stokAkun()
    {
        return $this->belongsTo(StokAkun::class, 'id_stok', 'id_stok');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_pembelian', 'id_pembelian');
    }
}
