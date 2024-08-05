<?php

declare(strict_types=1);

namespace App\Actions\Microsites;

use Illuminate\Database\Eloquent\Model;

class UpdateMicrositeAction
{
    public static function exec(array $data, Model $model): Model
    {
        $model->fill($data)->update();
        return $model;
    }
}
