<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GetAllPaymentsAction
{
    public static function exec(array $data, Model $model): Collection
    {
        return $model
            ->query()
            ->type($data)
            ->get();
    }
}
