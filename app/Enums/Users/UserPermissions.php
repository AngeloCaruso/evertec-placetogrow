<?php

declare(strict_types=1);

namespace App\Enums\Users;

use Filament\Support\Contracts\HasLabel;

enum UserPermissions: string implements HasLabel
{
    case ViewAny = 'users.view_any';
    case View = 'users.view';
    case Create = 'users.create';
    case Update = 'users.update';
    case Delete = 'users.delete';

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
