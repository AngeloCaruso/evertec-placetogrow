<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

interface BaseActionInterface
{
    /**
     * @param array<string, mixed> $data
     * @param Model $model
     * @return mixed
     */
    public static function exec(array $data, Model $model): mixed;
}
