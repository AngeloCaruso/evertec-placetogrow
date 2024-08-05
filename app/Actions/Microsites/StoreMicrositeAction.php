<?php

namespace App\Actions\Microsites;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;

class StoreMicrositeAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->create($data);
    }
}
