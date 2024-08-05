<?php

declare(strict_types=1);

namespace App\Actions\Microsites;

use Illuminate\Database\Eloquent\Model;

class StoreMicrositeAction
{
    public static function exec(array $data, Model $model): Model
    {
        return $model->create($data);
    }
}
