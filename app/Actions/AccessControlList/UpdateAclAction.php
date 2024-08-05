<?php

declare(strict_types=1);

namespace App\Actions\AccessControlList;

use Illuminate\Database\Eloquent\Model;

class UpdateAclAction
{
    public static function exec(array $data, Model $model): Model
    {
        $model->fill($data)->update();
        return $model;
    }
}
