<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripayWebhookLog extends Model
{
    use HasFactory;

    protected $table = 'tripay_webhook_logs';

    protected $fillable = [
        'reference',
        'merchant_ref',
        'amount',
        'status',
        'payload',
        'verified_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'verified_at' => 'datetime',
    ];
}
