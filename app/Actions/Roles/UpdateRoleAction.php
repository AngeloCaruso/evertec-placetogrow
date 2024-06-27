<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;

class UpdateRoleAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        $model->fill($data);
        $model->update();

        $permissions = $data['microsite_permissions'] ?? [];
        $model->permissions()->sync($permissions);

        return $model;
    }
}
