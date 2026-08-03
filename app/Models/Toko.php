<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'tbl_toko';
    protected $primaryKey = 'id_toko';

    protected $fillable = [
        'user_id',
        'nama_toko',
        'no_telp',
        'akun_telegram',
        'telegram_chat_id',
        'informasi_toko',
        'logo_toko',
        'komisi_override',
        'saldo',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function produk(): HasMany
    {
        return $this->hasMany(ProdukModel::class, 'id_toko', 'id_toko');
    }

    public function mutasiSaldo(): HasMany
    {
        return $this->hasMany(MutasiSaldo::class, 'id_toko', 'id_toko');
    }

    public function beliProduk(): HasMany
    {
        return $this->hasMany(BeliProdukModel::class, 'id_toko', 'id_toko');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'id_toko', 'id_toko');
    }

    public function badges(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SellerBadge::class, 'tbl_toko_badge', 'id_toko', 'id_badge')
            ->withPivot('diperoleh_pada');
    }
}
