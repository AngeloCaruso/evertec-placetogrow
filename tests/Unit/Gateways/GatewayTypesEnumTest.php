<?php

declare(strict_types=1);

namespace Tests\Unit\Gateways;

use App\Enums\Gateways\GatewayType;
use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Services\Gateways\PlacetopayGateway;
use PHPUnit\Framework\TestCase;

class GatewayTypesEnumTest extends TestCase
{
    public function test_stategies_enum_entity(): void
    {
        $this->assertEquals(new PlacetopayGateway(), GatewayType::Placetopay->getStrategy());
    }

    public function test_statuses_enum(): void
    {
        $this->assertEquals(PlacetopayStatus::class, GatewayType::Placetopay->getGatewayStatuses());
    }

    public function test_enum_values(): void
    {
        $values = GatewayType::values();

        $this->assertIsArray($values);
        $this->assertCount(1, $values);

        $expectedValues = ['placetopay'];
        $this->assertEquals($expectedValues, $values);
    }
}
