<?php

namespace App\Actions\AccessControlList;

use Illuminate\Database\Eloquent\Model;

class UpdateAclAction
{
    public static function exec(array $data, Model $model): mixed
    {
        $model->fill($data)->update();
        return $model;
    }
}
