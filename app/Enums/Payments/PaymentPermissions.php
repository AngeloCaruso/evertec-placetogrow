<?php

declare(strict_types=1);

namespace App\Enums\Payments;

use Filament\Support\Contracts\HasLabel;

enum PaymentPermissions: string implements HasLabel
{
    case ViewAny = 'payments.view_any';
    case View = 'payments.view';

    public function getLabel(): string
    {
        return match ($this) {
            self::ViewAny => 'View Any',
            self::View => 'View',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
