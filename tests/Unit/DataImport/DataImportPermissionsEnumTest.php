<?php

declare(strict_types=1);

namespace Tests\Unit\Microsites;

use App\Enums\Imports\ImportPermissions;
use PHPUnit\Framework\TestCase;

class DataImportPermissionsEnumTest extends TestCase
{
    public function test_data_import_permissions_enum_labels(): void
    {
        $this->assertEquals('View Any', ImportPermissions::ViewAny->getLabel());
        $this->assertEquals('View', ImportPermissions::View->getLabel());
        $this->assertEquals('Create', ImportPermissions::Create->getLabel());
        $this->assertEquals('Delete', ImportPermissions::Delete->getLabel());
    }

    public function test_data_import_permissions_enum_values(): void
    {
        $values = ImportPermissions::values();

        $this->assertIsArray($values);
        $this->assertCount(4, $values);

        $expectedValues = ['imports.view_any', 'imports.view', 'imports.create', 'imports.delete'];
        $this->assertEquals($expectedValues, $values);
    }
}
