<?php

declare(strict_types=1);

namespace App\Enums\Payments;

use Filament\Support\Contracts\HasLabel;

enum PaymentType: string implements HasLabel
{
    case Subscription = 'subscription';
    case Basic = 'basic';

    public function getLabel(): string
    {
        return match ($this) {
            self::Subscription => 'Subscription',
            self::Basic => 'Basic',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
