<?php

declare(strict_types=1);

namespace Tests\Unit\Payments;

use App\Enums\Subscriptions\SubscriptionPermissions;
use PHPUnit\Framework\TestCase;

class SubscriptionPermissionsEnumTest extends TestCase
{
    public function test_enum_labels(): void
    {
        $this->assertEquals('View Any', SubscriptionPermissions::ViewAny->getLabel());
        $this->assertEquals('View', SubscriptionPermissions::View->getLabel());
    }

    public function test_enum_values(): void
    {
        $values = SubscriptionPermissions::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = [
            'subscriptions.view_any',
            'subscriptions.view',
        ];
        $this->assertEquals($expectedValues, $values);
    }
}
