<?php

declare(strict_types=1);

namespace Tests\Unit\Acl;

use App\Enums\Acl\ControllableTypes;
use App\Models\Microsite;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ControllableTypesEnumTest extends TestCase
{
    public function test_enum_labels(): void
    {
        $this->assertEquals('Microsite', ControllableTypes::Microsite->getLabel());
        $this->assertEquals('User', ControllableTypes::User->getLabel());
    }

    public function test_enum_title(): void
    {
        $this->assertEquals('name', ControllableTypes::Microsite->title());
        $this->assertEquals('name', ControllableTypes::User->title());
    }

    public function test_enum_values(): void
    {
        $values = ControllableTypes::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = [Microsite::class, User::class];
        $this->assertEquals($expectedValues, $values);
    }
}
