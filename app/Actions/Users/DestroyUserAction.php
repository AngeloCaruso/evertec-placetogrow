<?php

namespace App\Actions\Users;

use Illuminate\Database\Eloquent\Model;

class DestroyUserAction
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->delete();
    }
}
