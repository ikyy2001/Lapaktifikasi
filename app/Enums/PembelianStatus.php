<?php

namespace App\Enums;

enum PembelianStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case EXPIRED = 'expired';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
}
