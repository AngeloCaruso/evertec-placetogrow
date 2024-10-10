<?php

declare(strict_types=1);

namespace App\Enums\Microsites;

use Filament\Support\Contracts\HasLabel;

enum SubscriptionCollectType: string implements HasLabel
{
    case UpFront = 'upfront';
    case PayLater = 'pay_later';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::UpFront => __('Pay up front'),
            self::PayLater => __('Pay Later'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
