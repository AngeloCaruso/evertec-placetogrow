<?php

namespace Tests\Unit\Payments;

use App\Enums\Payments\PaymentPermissions;
use PHPUnit\Framework\TestCase;

class PaymentPermissionsEnumTest extends TestCase
{
    public function test_enum_labels()
    {
        $this->assertEquals('View Any', PaymentPermissions::ViewAny->getLabel());
        $this->assertEquals('View', PaymentPermissions::View->getLabel());
    }

    public function test_enum_values()
    {
        $values = PaymentPermissions::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = [
            'payments.view_any',
            'payments.view',
        ];
        $this->assertEquals($expectedValues, $values);
    }
}
