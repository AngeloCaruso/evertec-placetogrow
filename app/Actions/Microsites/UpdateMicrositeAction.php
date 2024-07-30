<?php

namespace App\Actions\Microsites;

use Illuminate\Database\Eloquent\Model;

class UpdateMicrositeAction
{
    public static function exec(array $data, Model $model): mixed
    {
        $model->fill($data)->update();
        return $model;
    }
}
