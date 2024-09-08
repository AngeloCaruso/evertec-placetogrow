<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GetAllSubscriptionsAction
{
    public static function exec(array $data, Model $model): Collection
    {
        return $model->query()->get();
    }
}
