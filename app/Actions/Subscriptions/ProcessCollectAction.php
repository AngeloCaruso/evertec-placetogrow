<?php

namespace App\Actions\Subscriptions;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;

class ProcessCollectAction
{
    public static function exec(Model $model)
    {
        $gateway = $model->gateway->getStrategy();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($model->toArray())
            ->loadInstrument($model->token)
            ->prepareBody()
            ->sendCollectPayment();

        $status = $model->gateway->getGatewayStatuses()::tryFrom($gateway->status);
        $model->gateway_status = $status ? $status->value : null;

        return self::buildPayment($model, $gateway);
    }

    private static function buildPayment(Subscription $subscription, $gateway)
    {
        $payment = new Payment();
        $payment->fill($subscription->toArray());
        $payment->subscription_id = $subscription->id;
        $payment->request_id = $gateway->getRequestId();

        return $payment;
    }
}
