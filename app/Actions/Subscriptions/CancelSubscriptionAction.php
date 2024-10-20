<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Events\SubscriptionSuspended;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CancelSubscriptionAction
{
    public static function exec(array $data, Model $model): void
    {
        $gateway = $model->gateway->getStrategy();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadInstrument(Crypt::decryptString($model->token))
            ->prepareBody()
            ->sendInvalidateToken();

        $model->expires_at = now();
        $model->active = false;
        $model->token = null;
        $model->sub_token = null;
        $model->update();

        SubscriptionSuspended::dispatch($model);
    }
}
