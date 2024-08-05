<?php

namespace App\Actions\AccessControlList;

use Illuminate\Database\Eloquent\Model;

class DestroyAclAction
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->delete();
    }
}
