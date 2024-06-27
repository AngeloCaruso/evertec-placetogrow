<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseActionInterface
{
    public static function exec(array | Collection $data, Model $model): mixed;
}
