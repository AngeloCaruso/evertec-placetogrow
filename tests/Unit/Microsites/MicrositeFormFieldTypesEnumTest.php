<?php

declare(strict_types=1);

namespace Tests\Unit\Microsites;

use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositeFormFieldTypes;
use App\Enums\System\IdTypes;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\TestCase;

class MicrositeFormFieldTypesEnumTest extends TestCase
{
    public function test_microsite_form_fields_enum_labels(): void
    {
        $this->assertEquals('Text', MicrositeFormFieldTypes::Text->getLabel());
        $this->assertEquals('Select', MicrositeFormFieldTypes::Select->getLabel());
        $this->assertEquals('Checkbox', MicrositeFormFieldTypes::Checkbox->getLabel());
        $this->assertEquals('Default ID Types (select)', MicrositeFormFieldTypes::DefaultIdTypes->getLabel());
        $this->assertEquals('Default Currencies (select)', MicrositeFormFieldTypes::DefaultCurrencies->getLabel());
    }

    public function test_microsite_form_fields_default_rules(): void
    {
        $this->assertEquals(['max:255'], MicrositeFormFieldTypes::Text->getDefaultRules());
        $this->assertEquals([Rule::in([])], MicrositeFormFieldTypes::Select->getDefaultRules());
        $this->assertEquals(['boolean'], MicrositeFormFieldTypes::Checkbox->getDefaultRules());
        $this->assertEquals([Rule::enum(IdTypes::class)], MicrositeFormFieldTypes::DefaultIdTypes->getDefaultRules());
        $this->assertEquals([Rule::enum(MicrositeCurrency::class)], MicrositeFormFieldTypes::DefaultCurrencies->getDefaultRules());
    }

    public function test_microsite_form_fields_enum_values(): void
    {
        $values = MicrositeFormFieldTypes::values();

        $this->assertIsArray($values);
        $this->assertCount(5, $values);

        $expectedValues = ['text', 'select', 'checkbox', IdTypes::class, MicrositeCurrency::class];
        $this->assertEquals($expectedValues, $values);
    }
}
