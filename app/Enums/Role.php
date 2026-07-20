<?php

namespace App\Enums;

enum Role: int
{
    case ADMIN = 1;
    case CUSTOMER = 2;
    case SELLER = 3;
}
