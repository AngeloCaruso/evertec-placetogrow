<?php

namespace Tests\Unit\System;

use App\Enums\System\IdTypes;
use PHPUnit\Framework\TestCase;

class IdTypesEnumTest extends TestCase
{
    public function test_enum_labels(): void
    {
        $this->assertEquals('Cédula de ciudadanía', IdTypes::CC->getLabel());
        $this->assertEquals('Cédula de extranjería', IdTypes::CE->getLabel());
        $this->assertEquals('NIT', IdTypes::NIT->getLabel());
        $this->assertEquals('Pasaporte', IdTypes::PP->getLabel());
        $this->assertEquals('Registro civil', IdTypes::RC->getLabel());
        $this->assertEquals('RUT', IdTypes::RUT->getLabel());
        $this->assertEquals('DNI', IdTypes::DNI->getLabel());
    }

    public function test_enum_values(): void
    {
        $values = IdTypes::values();

        $this->assertIsArray($values);
        $this->assertCount(7, $values);

        $expectedValues = ['cc', 'ce', 'nit', 'pp', 'rc', 'rut', 'dni'];
        $this->assertEquals($expectedValues, $values);
    }

}
