<?php

namespace App\Actions\Users;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class GetAllUsersAction implements BaseActionInterface
{
    public static function exec(array | Collection $data, Model $model): mixed
    {
        return $model->query()->get();
    }
}
