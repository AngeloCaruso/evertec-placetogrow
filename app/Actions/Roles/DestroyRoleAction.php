<?php

namespace App\Actions\Roles;

use Illuminate\Database\Eloquent\Model;

class DestroyRoleAction
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->delete();
    }
}
