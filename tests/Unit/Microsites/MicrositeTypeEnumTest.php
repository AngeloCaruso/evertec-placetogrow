<?php

namespace Tests\Unit\Microsites;

use App\Enums\Microsites\MicrositeType;
use PHPUnit\Framework\TestCase;

class MicrositeTypeEnumTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_microsite_type_enum_values(): void
    {
        $values = MicrositeType::values();

        $this->assertIsArray($values);
        $this->assertCount(3, $values);
        $this->assertEquals(['donation', 'billing', 'subscription'], $values);
    }

    public function test_microsite_type_enum_labels(): void
    {
        $this->assertEquals('Donation', MicrositeType::Donation->getLabel());
        $this->assertEquals('Billing', MicrositeType::Billing->getLabel());
        $this->assertEquals('Subscription', MicrositeType::Subscription->getLabel());
    }

    public function test_microsite_type_enum_colors(): void
    {
        $this->assertEquals('primary', MicrositeType::Donation->getColor());
        $this->assertEquals('primary', MicrositeType::Billing->getColor());
        $this->assertEquals('primary', MicrositeType::Subscription->getColor());
    }
}
