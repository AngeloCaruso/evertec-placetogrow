<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\Gateways\PlacetopayGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ProcessCollectAction
{
    public static function exec(Model $model): ?Payment
    {
        $model->refresh();
        $gateway = $model->gateway->getStrategy();
        $payer = [...$model->additional_attributes, 'email' => $model->email];

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($model->toArray())
            ->loadInstrument(Crypt::decryptString($model->token))
            ->loadPayer($payer)
            ->prepareBody()
            ->sendCollectPayment();

        if (!$gateway->requestId) {
            return null;
        }

        $status = $model->gateway->getGatewayStatuses()::tryFrom($gateway->status);
        $model->gateway_status = $status ? $status->value : null;

        return self::buildPayment($model, $gateway);
    }

    private static function buildPayment(Subscription $subscription, PlacetopayGateway $gateway): Payment
    {
        $payment = new Payment();
        $payment->fill($subscription->toArray());
        $payment->subscription_id = $subscription->id;
        $payment->request_id = $gateway->getRequestId();
        $payment->save();

        return $payment;
    }
}
