<?php

namespace App\Actions\Microsites;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;

class UpdateMicrositeAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        $model->fill($data)->update();
        return $model;
    }
}
