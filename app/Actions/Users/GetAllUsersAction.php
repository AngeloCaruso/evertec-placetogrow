<?php

namespace App\Actions\Users;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;

class GetAllUsersAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->query()->get();
    }
}
