<?php

declare(strict_types=1);

namespace App\Enums\Microsites;

use Filament\Support\Contracts\HasLabel;

enum MicrositePermissions: string implements HasLabel
{
    case ViewAny = 'microsites.view_any';
    case View = 'microsites.view';
    case Create = 'microsites.create';
    case Update = 'microsites.update';
    case Delete = 'microsites.delete';

    public function getLabel(): string
    {
        return match ($this) {
            self::ViewAny => 'View Any',
            self::View => 'View',
            self::Create => 'Create',
            self::Update => 'Update',
            self::Delete => 'Delete',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
