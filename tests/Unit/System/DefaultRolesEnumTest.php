<?php

declare(strict_types=1);

namespace Tests\Unit\System;

use App\Enums\System\DefaultRoles;
use PHPUnit\Framework\TestCase;

class DefaultRolesEnumTest extends TestCase
{
    public function test_default_roles_permissions_enum_labels(): void
    {
        $this->assertEquals('Administrator', DefaultRoles::Admin->getLabel());
        $this->assertEquals('Guest', DefaultRoles::Guest->getLabel());
    }

    public function test_default_roles_permissions_enum_values(): void
    {
        $values = DefaultRoles::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = ['admin', 'guest'];
        $this->assertEquals($expectedValues, $values);
    }
}
