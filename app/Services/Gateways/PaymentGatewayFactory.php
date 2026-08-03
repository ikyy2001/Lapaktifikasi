<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;

class PaymentGatewayFactory
{
    public static function make(string $gateway = 'midtrans'): PaymentGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'pakasir':
                return new PakasirGateway();
            case 'midtrans':
            default:
                return new MidtransGateway();
        }
    }
}
