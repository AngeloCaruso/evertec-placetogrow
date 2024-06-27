<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class StoreRoleAction implements BaseActionInterface
{
    public static function exec(array | Collection $data, Model $model): mixed
    {
        $role = $model->create($data);
        $permissions = $data['microsite_permissions'] ?? [];
        $role->permissions()->attach($permissions);
        return $role;
    }
}
