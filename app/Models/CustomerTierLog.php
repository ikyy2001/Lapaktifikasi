<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerTierLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_customer_tier_log';
    public $timestamps = false;

    protected $fillable = [
        'id_customer',
        'id_tier_lama',
        'id_tier_baru',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'id_customer', 'id');
    }

    public function tierLama(): BelongsTo
    {
        return $this->belongsTo(CustomerTier::class, 'id_tier_lama', 'id_tier');
    }

    public function tierBaru(): BelongsTo
    {
        return $this->belongsTo(CustomerTier::class, 'id_tier_baru', 'id_tier');
    }
}
