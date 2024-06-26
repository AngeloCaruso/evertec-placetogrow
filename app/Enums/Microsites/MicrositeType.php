<?php

namespace App\Enums\Microsites;

use Filament\Support\Contracts\HasLabel;

enum MicrositeType: string implements HasLabel
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

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
