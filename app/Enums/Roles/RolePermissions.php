<?php

namespace App\Enums\Roles;

use Filament\Support\Contracts\HasLabel;

enum RolePermissions: string implements HasLabel
{
    case ViewAny = 'roles.view_any';
    case View = 'roles.view';
    case Create = 'roles.create';
    case Update = 'roles.update';
    case Delete = 'roles.delete';

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
