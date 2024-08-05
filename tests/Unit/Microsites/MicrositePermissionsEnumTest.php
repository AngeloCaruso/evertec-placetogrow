<?php

declare(strict_types=1);

namespace Tests\Unit\Microsites;

use App\Enums\Microsites\MicrositePermissions;
use PHPUnit\Framework\TestCase;

class MicrositePermissionsEnumTest extends TestCase
{
    public function test_microsite_permissions_enum_labels(): void
    {
        $this->assertEquals('View Any', MicrositePermissions::ViewAny->getLabel());
        $this->assertEquals('View', MicrositePermissions::View->getLabel());
        $this->assertEquals('Create', MicrositePermissions::Create->getLabel());
        $this->assertEquals('Update', MicrositePermissions::Update->getLabel());
        $this->assertEquals('Delete', MicrositePermissions::Delete->getLabel());
    }

    public function test_microsite_permissions_enum_values(): void
    {
        $values = MicrositePermissions::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = ['microsites.view_any', 'microsites.view', 'microsites.create', 'microsites.update', 'microsites.delete'];
        $this->assertEquals($expectedValues, $values);
    }
}
