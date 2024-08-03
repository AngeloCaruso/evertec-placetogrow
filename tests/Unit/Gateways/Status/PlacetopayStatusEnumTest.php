<?php

namespace Tests\Unit\Gateways\Status;

use App\Enums\Gateways\Status\PlacetopayStatus;
use PHPUnit\Framework\TestCase;

class PlacetopayStatusEnumTest extends TestCase
{
    public function test_enum_labels()
    {
        $this->assertEquals('Pendiente', PlacetopayStatus::Pending->getLabel());
        $this->assertEquals('Aprobado', PlacetopayStatus::Approved->getLabel());
        $this->assertEquals('Rechazado', PlacetopayStatus::Rejected->getLabel());
        $this->assertEquals('Aprobado Parcial', PlacetopayStatus::Parcial->getLabel());
        $this->assertEquals('Parcial Expirado', PlacetopayStatus::Expired->getLabel());
    }

    public function test_enum_values()
    {
        $values = PlacetopayStatus::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = ['PENDING', 'APPROVED', 'REJECTED', 'APPROVED_PARTIAL', 'PARTIAL_EXPIRED'];
        $this->assertEquals($expectedValues, $values);
    }
}
