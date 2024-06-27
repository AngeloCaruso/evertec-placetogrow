<?php

namespace App\Actions\Users;

use App\Actions\BaseActionInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class UpdateUserAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        $model->fill($data);
        $model->update();
        $model->roles()->sync($data['roles']);

        return $model;
    }
}
