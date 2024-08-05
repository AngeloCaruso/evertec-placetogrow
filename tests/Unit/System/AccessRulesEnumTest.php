<?php

declare(strict_types=1);

namespace Tests\Unit\System;

use App\Enums\System\AccessRules;
use PHPUnit\Framework\TestCase;

class AccessRulesEnumTest extends TestCase
{
    public function test_enum_labels(): void
    {
        $this->assertEquals('Allow', AccessRules::Allow->getLabel());
        $this->assertEquals('Deny', AccessRules::Deny->getLabel());
    }

    public function test_enum_colors(): void
    {
        $this->assertEquals('success', AccessRules::Allow->getColor());
        $this->assertEquals('danger', AccessRules::Deny->getColor());
    }

    public function test_enum_icons(): void
    {
        $this->assertEquals('heroicon-o-check-circle', AccessRules::Allow->getIcon());
        $this->assertEquals('heroicon-o-minus-circle', AccessRules::Deny->getIcon());
    }

    public function test_enum_values(): void
    {
        $values = AccessRules::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = ['allow', 'deny'];
        $this->assertEquals($expectedValues, $values);
    }
}
