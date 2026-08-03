<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'tbl_voucher';
    protected $primaryKey = 'id_voucher';

    protected $fillable = [
        'kode',
        'tipe_diskon',
        'nilai_diskon',
        'maksimal_potongan',
        'minimal_transaksi',
        'kuota_total',
        'kuota_terpakai',
        'berlaku_dari',
        'berlaku_sampai',
        'scope',
        'id_toko',
        'dibuat_oleh',
        'is_active',
    ];

    protected $casts = [
        'berlaku_dari' => 'datetime',
        'berlaku_sampai' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id');
    }

    public function klaims(): HasMany
    {
        return $this->hasMany(VoucherKlaim::class, 'id_voucher', 'id_voucher');
    }
}
