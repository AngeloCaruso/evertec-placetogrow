<?php

namespace App\Actions\Microsites;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UpdateMicrositeAction implements BaseActionInterface
{
    public static function exec(array | Collection $data, Model $model): mixed
    {
        return $model->fill($data)->update();
    }
}
