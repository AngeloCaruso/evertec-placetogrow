<?php

namespace App\Actions\Payments;

use Illuminate\Database\Eloquent\Model;

class ProcessPaymentAction
{
    public static function exec(Model $model): Model
    {
        $gateway = $model->gateway->getStrategy();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($model->toArray())
            ->prepareBody()
            ->send();

        $model->payment_url = $gateway->getRedirectUrl();
        $model->request_id = $gateway->getRequestId();

        $model->update();

        return $model;
    }
}
