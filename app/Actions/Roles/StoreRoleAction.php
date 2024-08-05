<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreRoleAction implements BaseActionInterface
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
