<?php

namespace App\Actions\Users;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $model->fill($data);
        $model->update();
        $model->roles()->sync($data['roles']);

        return $model;
    }
}
