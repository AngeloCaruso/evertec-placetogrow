<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;

class StoreRoleAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        $role = $model->create($data);
        $permissions = $data['microsite_permissions'] ?? [];
        $role->permissions()->attach($permissions);
        return $role;
    }
}
