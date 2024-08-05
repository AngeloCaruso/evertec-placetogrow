<?php

declare(strict_types=1);

namespace Tests\Unit\Roles;

use App\Enums\Roles\RolePermissions;
use PHPUnit\Framework\TestCase;

class RolePermissionsEnumTest extends TestCase
{
    public function test_role_permissions_enum_labels(): void
    {
        $this->assertEquals('View Any', RolePermissions::ViewAny->getLabel());
        $this->assertEquals('View', RolePermissions::View->getLabel());
        $this->assertEquals('Create', RolePermissions::Create->getLabel());
        $this->assertEquals('Update', RolePermissions::Update->getLabel());
        $this->assertEquals('Delete', RolePermissions::Delete->getLabel());
    }

    public function test_role_permissions_enum_values(): void
    {
        $values = RolePermissions::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = ['roles.view_any', 'roles.view', 'roles.create', 'roles.update', 'roles.delete'];
        $this->assertEquals($expectedValues, $values);
    }
}
