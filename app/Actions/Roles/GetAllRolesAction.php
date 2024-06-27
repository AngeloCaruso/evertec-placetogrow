<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class GetAllRolesAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->query()->get();
    }
}
