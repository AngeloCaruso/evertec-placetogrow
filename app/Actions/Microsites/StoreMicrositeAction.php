<?php

namespace App\Actions\Microsites;

use App\Actions\BaseActionInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class StoreMicrositeAction implements BaseActionInterface
{
    public static function exec(array | Collection $data, Model $model): mixed
    {
        return $model->create($data);
    }
}
