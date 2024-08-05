<?php

namespace App\Actions\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreUserAction
{
    public static function exec(array $data, Model $model): mixed
    {
        DB::transaction(function () use ($data, &$model) {
            $model = $model->create($data);
            $model->roles()->attach($data['roles']);
        });

        return $model;
    }
}
