<?php

declare(strict_types=1);

namespace Tests\Unit\Gateways\Status;

use App\Enums\Gateways\Status\PlacetopayStatus;
use PHPUnit\Framework\TestCase;

class PlacetopayStatusEnumTest extends TestCase
{
    public function test_enum_labels(): void
    {
        $this->assertEquals('Pending', PlacetopayStatus::Pending->getLabel());
        $this->assertEquals('Approved', PlacetopayStatus::Approved->getLabel());
        $this->assertEquals('Rejected', PlacetopayStatus::Rejected->getLabel());
        $this->assertEquals('Parcially Approved', PlacetopayStatus::Parcial->getLabel());
        $this->assertEquals('Expired', PlacetopayStatus::Expired->getLabel());
    }

    public function test_enum_colors(): void
    {
        $this->assertEquals('warning', PlacetopayStatus::Pending->getColor());
        $this->assertEquals('success', PlacetopayStatus::Approved->getColor());
        $this->assertEquals('danger', PlacetopayStatus::Rejected->getColor());
        $this->assertEquals('warning', PlacetopayStatus::Parcial->getColor());
        $this->assertEquals('info', PlacetopayStatus::Expired->getColor());
    }

    public function test_enum_icons(): void
    {
        $this->assertEquals('heroicon-s-clock', PlacetopayStatus::Pending->getIcon());
        $this->assertEquals('heroicon-s-check-circle', PlacetopayStatus::Approved->getIcon());
        $this->assertEquals('heroicon-s-x-circle', PlacetopayStatus::Rejected->getIcon());
        $this->assertEquals('heroicon-s-minus-circle', PlacetopayStatus::Parcial->getIcon());
        $this->assertEquals('heroicon-s-clock', PlacetopayStatus::Expired->getIcon());
    }

    public function test_enum_values(): void
    {
        $values = PlacetopayStatus::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = ['PENDING', 'APPROVED', 'REJECTED', 'APPROVED_PARTIAL', 'EXPIRED'];
        $this->assertEquals($expectedValues, $values);
    }
}
