<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UpdateRoleAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        DB::transaction(function () use ($data, &$model) {
            $model->fill($data);
            $model->update();

            $permissions = [...$data['microsite_permissions'], ...$data['user_permissions'], ...$data['role_permissions']];
            $model->permissions()->sync($permissions);
        });

        return $model;
    }
}
