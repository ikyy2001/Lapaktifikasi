<?php

namespace App\Enums;

enum StokStatus: string
{
    case TERSEDIA = 'tersedia';
    case RESERVED = 'reserved';
    case TERJUAL = 'terjual';
}
