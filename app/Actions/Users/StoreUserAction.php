<?php

namespace App\Actions\Users;

use App\Actions\BaseActionInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class StoreUserAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        $user = $model->create($data);
        $user->roles()->attach($data['roles']);
        return $user;
    }
}
