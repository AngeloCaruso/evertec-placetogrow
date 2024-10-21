<?php

declare(strict_types=1);

namespace Tests\Unit\Payments;

use App\Enums\Payments\PaymentType;
use PHPUnit\Framework\TestCase;

class PaymentTypeEnumTest extends TestCase
{
    public function test_enum_labels(): void
    {
        $this->assertEquals('Subscription', PaymentType::Subscription->getLabel());
        $this->assertEquals('Basic', PaymentType::Basic->getLabel());
    }

    public function test_enum_values(): void
    {
        $values = PaymentType::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = [
            'subscription',
            'basic',
        ];
        $this->assertEquals($expectedValues, $values);
    }
}
