<?php

namespace App\Enums\Imports;

use Filament\Support\Contracts\HasLabel;

enum ImportPermissions: string implements HasLabel
{
    case ViewAny = 'imports.view_any';
    case View = 'imports.view';
    case Create = 'imports.create';
    case Delete = 'imports.delete';

    public function getLabel(): string
    {
        return match ($this) {
            self::ViewAny => 'View Any',
            self::View => 'View',
            self::Create => 'Create',
            self::Delete => 'Delete',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
