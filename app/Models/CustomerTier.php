<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerTier extends Model
{
    use HasFactory;

    protected $table = 'tbl_customer_tier';
    protected $primaryKey = 'id_tier';

    protected $fillable = [
        'nama_tier',
        'urutan',
        'minimal_belanja',
        'warna_tema',
        'icon_path',
        'benefit_cashback_persen',
        'benefit_deskripsi',
    ];

    protected $casts = [
        'benefit_deskripsi' => 'array',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(CustomerModel::class, 'id_tier_saat_ini', 'id_tier');
    }
}
