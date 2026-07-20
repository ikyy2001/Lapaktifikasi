<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerModel extends Model
{
    use HasFactory;

    protected $table = "tbl_customer";
    protected $fillable = [
        'user_id',
        'nama_customer',
        'nomor_telepon',
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
