<?php

namespace App\Actions\Subscriptions;

use Illuminate\Database\Eloquent\Model;

class ProcessSubscriptionAction
{
    public static function exec(Model $model): Model
    {
        $gateway = $model->gateway->getStrategy();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadSubscription($model->toArray())
            ->prepareBody()
            ->send();

        $model->payment_url = $gateway->getRedirectUrl();
        $model->request_id = $gateway->getRequestId();

        $model->update();

        return $model;
    }
}
