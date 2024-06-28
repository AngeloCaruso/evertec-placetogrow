<?php

namespace Tests\Unit\Microsites;

use App\Enums\Microsites\MicrositeCurrency;
use PHPUnit\Framework\TestCase;

class MicrositeCurrencyEnumTest extends TestCase
{
    public function test_microsite_currency_enum_labels()
    {
        $this->assertEquals('USD', MicrositeCurrency::USD->getLabel());
        $this->assertEquals('ARS', MicrositeCurrency::ARS->getLabel());
        $this->assertEquals('BRL', MicrositeCurrency::BRL->getLabel());
        $this->assertEquals('CLP', MicrositeCurrency::CLP->getLabel());
        $this->assertEquals('COP', MicrositeCurrency::COP->getLabel());
        $this->assertEquals('MXN', MicrositeCurrency::MXN->getLabel());
        $this->assertEquals('PEN', MicrositeCurrency::PEN->getLabel());
        $this->assertEquals('UYU', MicrositeCurrency::UYU->getLabel());
        $this->assertEquals('VEF', MicrositeCurrency::VEF->getLabel());
        $this->assertEquals('EUR', MicrositeCurrency::EUR->getLabel());
        $this->assertEquals('GBP', MicrositeCurrency::GBP->getLabel());
        $this->assertEquals('AUD', MicrositeCurrency::AUD->getLabel());
        $this->assertEquals('CAD', MicrositeCurrency::CAD->getLabel());
        $this->assertEquals('JPY', MicrositeCurrency::JPY->getLabel());
        $this->assertEquals('CNY', MicrositeCurrency::CNY->getLabel());
        $this->assertEquals('KRW', MicrositeCurrency::KRW->getLabel());
        $this->assertEquals('INR', MicrositeCurrency::INR->getLabel());
    }

    public function test_microsite_currency_enum_values()
    {
        $values = MicrositeCurrency::values();

        $this->assertIsArray($values);
        $this->assertCount(17, $values);

        $expectedValues = [
            'USD', 'ARS', 'BRL', 'CLP', 'COP', 'MXN', 'PEN', 'UYU', 'VEF',
            'EUR', 'GBP', 'AUD', 'CAD', 'JPY', 'CNY', 'KRW', 'INR'
        ];
        $this->assertEquals($expectedValues, $values);
    }
}
