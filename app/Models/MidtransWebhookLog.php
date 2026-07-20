<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MidtransWebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status_code',
        'signature_key',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
