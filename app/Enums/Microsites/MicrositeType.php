<?php

declare(strict_types=1);

namespace App\Enums\Microsites;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MicrositeType: string implements HasLabel, HasColor
{
    case Donation = 'donation';
    case Billing = 'billing';
    case Subscription = 'subscription';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Donation => 'Donation',
            self::Billing => 'Billing',
            self::Subscription => 'Subscription',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Donation => 'primary',
            self::Billing => 'primary',
            self::Subscription => 'primary',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
