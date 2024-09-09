<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use Illuminate\Database\Eloquent\Model;

class CancelSubscriptionAction
{
    public static function exec(array $data, Model $model): void
    {
        $model->active = false;
        $model->update();
    }
}
