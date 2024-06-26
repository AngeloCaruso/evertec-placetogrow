<?php

namespace App\Enums\Microsites;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MicrositeType: string implements HasLabel, HasColor
{
    case Basic = 'basic';
    case Billing = 'billing';
    case Subscription = 'subscription';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Basic => 'Basic',
            self::Billing => 'Billing',
            self::Subscription => 'Subscription',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Basic => 'info',
            self::Billing => 'success',
            self::Subscription => 'warning',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
