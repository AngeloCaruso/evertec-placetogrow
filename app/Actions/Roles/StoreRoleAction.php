<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreRoleAction
{
    public static function exec(array $data, Model $model): Model
    {
        DB::transaction(function () use ($data, &$model) {
            $model = $model->create($data);

            $permissions = [
                ...$data['microsite_permissions'],
                ...$data['user_permissions'],
                ...$data['role_permissions'],
                ...$data['acl_permissions'],
                ...$data['payment_permissions'],
                ...$data['subscription_permissions'],
                ...$data['data-import_permissions'],
            ];
            $model->permissions()->attach($permissions);
        });

        return $model;
    }
}
