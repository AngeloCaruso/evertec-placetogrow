<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;

class DestroyRoleAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->delete();
    }
}
