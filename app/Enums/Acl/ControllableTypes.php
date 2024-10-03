<?php

declare(strict_types=1);

namespace App\Enums\Acl;

use App\Models\Microsite;
use App\Models\User;
use Filament\Support\Contracts\HasLabel;

enum ControllableTypes: string implements HasLabel
{
    case Microsite = Microsite::class;
    case User = User::class;

    public function getLabel(): string
    {
        return match ($this) {
            self::Microsite => 'Microsite',
            self::User => 'User',
        };
    }

    public function title(): string
    {
        return match ($this) {
            self::Microsite => 'name',
            self::User => 'name',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
