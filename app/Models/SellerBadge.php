<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SellerBadge extends Model
{
    use HasFactory;

    protected $table = 'tbl_seller_badge';
    protected $primaryKey = 'id_badge';

    protected $fillable = [
        'nama_badge',
        'deskripsi',
        'kriteria_tipe',
        'kriteria_nilai',
        'icon_path',
    ];

    public function toko(): BelongsToMany
    {
        return $this->belongsToMany(Toko::class, 'tbl_toko_badge', 'id_badge', 'id_toko')
            ->withPivot('diperoleh_pada');
    }
}
