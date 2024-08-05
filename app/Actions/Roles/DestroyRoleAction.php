<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Illuminate\Database\Eloquent\Model;

class DestroyRoleAction
{
    public static function exec(array $data, Model $model): bool
    {
        return $model->delete();
    }
}
