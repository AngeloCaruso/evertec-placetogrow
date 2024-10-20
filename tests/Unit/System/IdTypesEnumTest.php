<?php

declare(strict_types=1);

namespace Tests\Unit\System;

use App\Enums\System\IdTypes;
use PHPUnit\Framework\TestCase;

class IdTypesEnumTest extends TestCase
{
    public function test_enum_labels(): void
    {
        $this->assertEquals('Cédula de ciudadanía', IdTypes::CC->getLabel());
        $this->assertEquals('Cédula de extranjería', IdTypes::CE->getLabel());
        $this->assertEquals('Tarjeta de identidad', IdTypes::TI->getLabel());
        $this->assertEquals('NIT', IdTypes::NIT->getLabel());
        $this->assertEquals('RUT', IdTypes::RUT->getLabel());
    }

    public function test_enum_values(): void
    {
        $values = IdTypes::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = ['cc', 'ce', 'ti', 'nit', 'rut'];
        $this->assertEquals($expectedValues, $values);
    }

}
