<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseActionInterface
{
    /**
     * @param array<string, mixed> $data
     * @param Model $model
     * @return mixed
     */
    public static function exec(array $data, Model $model): mixed;
}
