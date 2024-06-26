<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DestroyRoleAction implements BaseActionInterface
{
    public static function exec(array | Collection $data, Model $model): mixed
    {
        return $model->delete();
    }
}
