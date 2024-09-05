<?php

declare(strict_types=1);

namespace App\Enums\Microsites;

use App\Enums\System\IdTypes;
use Filament\Support\Contracts\HasLabel;

enum MicrositeFormFieldTypes: string implements HasLabel
{
    case Text = 'text';
    case Select = 'select';
    case Checkbox = 'checkbox';
    case DefaultIdTypes = IdTypes::class;
    case DefaultCurrencies = MicrositeCurrency::class;

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => 'Text',
            self::Select => 'Select',
            self::Checkbox => 'Checkbox',
            self::DefaultIdTypes => 'Default ID Types (select)',
            self::DefaultCurrencies => 'Default Currencies (select)',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
