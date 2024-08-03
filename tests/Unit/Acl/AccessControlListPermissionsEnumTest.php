<?php

namespace Tests\Unit\Acl;

use App\Enums\Acl\AccessControlListPermissions;
use PHPUnit\Framework\TestCase;

class AccessControlListPermissionsEnumTest extends TestCase
{
    public function test_enum_labels()
    {
        $this->assertEquals('View Any', AccessControlListPermissions::ViewAny->getLabel());
        $this->assertEquals('View', AccessControlListPermissions::View->getLabel());
        $this->assertEquals('Create', AccessControlListPermissions::Create->getLabel());
        $this->assertEquals('Update', AccessControlListPermissions::Update->getLabel());
        $this->assertEquals('Delete', AccessControlListPermissions::Delete->getLabel());
    }

    public function test_enum_values()
    {
        $values = AccessControlListPermissions::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = [
            'acl.view_any',
            'acl.view',
            'acl.create',
            'acl.update',
            'acl.delete'
        ];
        $this->assertEquals($expectedValues, $values);
    }
}
