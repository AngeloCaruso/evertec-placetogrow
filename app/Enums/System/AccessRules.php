<?php

declare(strict_types=1);

namespace App\Enums\System;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AccessRules: string implements HasLabel, HasColor, HasIcon
{
    case Allow = 'allow';
    case Deny = 'deny';

    public function getLabel(): string
    {
        return match ($this) {
            self::Allow => 'Allow',
            self::Deny => 'Deny',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Allow => 'success',
            self::Deny => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Allow => 'heroicon-o-check-circle',
            self::Deny => 'heroicon-o-minus-circle',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
