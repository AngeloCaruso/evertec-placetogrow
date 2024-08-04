<?php

namespace App\Actions\Roles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UpdateRoleAction
{
    public static function exec(array $data, Model $model): mixed
    {
        DB::transaction(function () use ($data, &$model) {
            $model->fill($data);
            $model->update();

            $permissions = [
                ...$data['microsite_permissions'],
                ...$data['user_permissions'],
                ...$data['role_permissions'],
                ...$data['acl_permissions'],
                ...$data['payment_permissions'],
            ];

            $model->permissions()->sync($permissions);
        });

        return $model;
    }
}
