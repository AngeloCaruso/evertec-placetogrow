<?php

namespace App\Actions\Roles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreRoleAction
{
    public static function exec(array $data, Model $model): mixed
    {
        DB::transaction(function () use ($data, &$model) {
            $model = $model->create($data);

            $permissions = [...$data['microsite_permissions'], ...$data['user_permissions'], ...$data['role_permissions']];
            $model->permissions()->attach($permissions);
        });

        return $model;
    }
}
