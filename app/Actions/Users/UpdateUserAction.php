<?php

declare(strict_types=1);

namespace App\Actions\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public static function exec(array $data, Model $model): Model
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        DB::transaction(function () use ($data, &$model) {
            $model->fill($data);
            $model->update();
            $model->roles()->sync($data['roles']);
        });

        return $model;
    }
}
