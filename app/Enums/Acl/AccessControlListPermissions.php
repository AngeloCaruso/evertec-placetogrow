<?php

declare(strict_types=1);

namespace App\Enums\Acl;

use Filament\Support\Contracts\HasLabel;

enum AccessControlListPermissions: string implements HasLabel
{
    case ViewAny = 'acl.view_any';
    case View = 'acl.view';
    case Create = 'acl.create';
    case Update = 'acl.update';
    case Delete = 'acl.delete';

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
