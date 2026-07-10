<?php

namespace App\Models;

use App\Enums\StokStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokAkun extends Model
{
    use HasFactory;

    protected $table = 'tbl_stok_akun';
    protected $primaryKey = 'id_stok';

    protected $fillable = [
        'id_varian',
        'email_username',
        'password_encrypted',
        'catatan',
        'status',
        'id_pembelian',
        'reserved_at',
        'reserved_expired_at',
        'tanggal_terjual',
    ];

    protected $casts = [
        'password_encrypted' => 'encrypted',
        'status' => StokStatus::class,
        'reserved_at' => 'datetime',
        'reserved_expired_at' => 'datetime',
        'tanggal_terjual' => 'datetime',
    ];

    public function varianLayanan()
    {
        return $this->belongsTo(VarianLayanan::class, 'id_varian', 'id_varian');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }
}
