<?php

namespace App\Enums\System;

use Filament\Support\Contracts\HasLabel;

enum DefaultRoles: string implements HasLabel
{
    case Admin = 'admin';
    case Guest = 'guest';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Guest => 'Guest',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
