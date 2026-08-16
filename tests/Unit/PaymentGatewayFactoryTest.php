<?php

namespace Tests\Unit;

use App\Services\Gateways\MidtransGateway;
use App\Services\Gateways\PakasirGateway;
use App\Services\Gateways\PaymentGatewayFactory;
use App\Services\Gateways\TriPayGateway;
use Tests\TestCase;

class PaymentGatewayFactoryTest extends TestCase
{
    public function test_it_creates_midtrans_gateway(): void
    {
        $gateway = PaymentGatewayFactory::make('midtrans');
        $this->assertInstanceOf(MidtransGateway::class, $gateway);
    }

    public function test_it_creates_pakasir_gateway(): void
    {
        $gateway = PaymentGatewayFactory::make('pakasir');
        $this->assertInstanceOf(PakasirGateway::class, $gateway);
    }

    public function test_it_creates_tripay_gateway(): void
    {
        $gateway = PaymentGatewayFactory::make('tripay');
        $this->assertInstanceOf(TriPayGateway::class, $gateway);
    }

    public function test_it_defaults_to_midtrans_gateway(): void
    {
        $gateway = PaymentGatewayFactory::make('unsupported_gateway');
        $this->assertInstanceOf(MidtransGateway::class, $gateway);
    }
}
