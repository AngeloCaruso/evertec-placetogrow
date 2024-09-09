<?php

declare(strict_types=1);

namespace App\Enums\Subscriptions;

use Filament\Support\Contracts\HasLabel;

enum SubscriptionPermissions: string implements HasLabel
{
    case ViewAny = 'subscriptions.view_any';
    case View = 'subscriptions.view';

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
