<?php

namespace App\Actions\Roles;

use Illuminate\Database\Eloquent\Model;

class GetAllRolesAction
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->query()->get();
    }
}
