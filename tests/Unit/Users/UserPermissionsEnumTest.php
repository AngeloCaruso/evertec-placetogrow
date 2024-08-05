<?php

declare(strict_types=1);

namespace Tests\Unit\Users;

use App\Enums\Users\UserPermissions;
use PHPUnit\Framework\TestCase;

class UserPermissionsEnumTest extends TestCase
{
    public function test_user_permissions_enum_labels(): void
    {
        $this->assertEquals('View Any', UserPermissions::ViewAny->getLabel());
        $this->assertEquals('View', UserPermissions::View->getLabel());
        $this->assertEquals('Create', UserPermissions::Create->getLabel());
        $this->assertEquals('Update', UserPermissions::Update->getLabel());
        $this->assertEquals('Delete', UserPermissions::Delete->getLabel());
    }

    public function test_user_permissions_enum_values(): void
    {
        $values = UserPermissions::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = ['users.view_any', 'users.view', 'users.create', 'users.update', 'users.delete'];
        $this->assertEquals($expectedValues, $values);
    }
}
