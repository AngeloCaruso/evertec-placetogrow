<?php

namespace App\Actions\Microsites;

use Illuminate\Database\Eloquent\Model;

class StoreMicrositeAction
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->create($data);
    }
}
