<?php

namespace App\Actions\Microsites;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DestroyMicrositeAction implements BaseActionInterface
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->delete();
    }
}
