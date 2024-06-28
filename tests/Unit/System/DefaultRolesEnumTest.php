<?php

namespace Tests\Unit\System;

use App\Enums\System\DefaultRoles;
use PHPUnit\Framework\TestCase;

class DefaultRolesEnumTest extends TestCase
{
    public function test_default_roles_permissions_enum_labels()
    {
        $this->assertEquals('Administrator', DefaultRoles::Admin->getLabel());
        $this->assertEquals('Guest', DefaultRoles::Guest->getLabel());
    }

    public function test_default_roles_permissions_enum_values()
    {
        $values = DefaultRoles::values();

        $this->assertIsArray($values);
        $this->assertCount(2, $values);

        $expectedValues = ['admin', 'guest'];
        $this->assertEquals($expectedValues, $values);
    }
}
