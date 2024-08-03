<?php

namespace Tests\Unit\Gateways;

use App\Enums\Gateways\GatewayType;
use App\Enums\Gateways\Status\PaypalStatus;
use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Services\Gateways\PaypalGateway;
use App\Services\Gateways\PlacetopayGateway;
use PHPUnit\Framework\TestCase;

class GatewayTypesEnumTest extends TestCase
{
    public function test_stategies_enum_entity()
    {
        $this->assertEquals(new PlacetopayGateway(), GatewayType::Placetopay->getStrategy());
        $this->assertEquals(new PaypalGateway(), GatewayType::Paypal->getStrategy());
    }

    public function test_statuses_enum()
    {
        $this->assertEquals(PlacetopayStatus::class, GatewayType::Placetopay->getGatewayStatuses());
        $this->assertEquals(PaypalStatus::class, GatewayType::Paypal->getGatewayStatuses());
    }

    public function test_enum_values()
    {
        $values = GatewayType::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = ['placetopay', 'paypal'];
        $this->assertEquals($expectedValues, $values);
    }
}
