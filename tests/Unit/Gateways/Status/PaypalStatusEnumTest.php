<?php

namespace Tests\Unit\Gateways\Status;

use App\Enums\Gateways\Status\PaypalStatus;
use PHPUnit\Framework\TestCase;

class PaypalStatusEnumTest extends TestCase
{
    public function test_paypal_status_enum_values()
    {
        $values = PaypalStatus::values();

        $this->assertIsArray($values);
        $this->assertCount(1, $values);

        $expectedValues = ['default'];
        $this->assertEquals($expectedValues, $values);
    }
}
