<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\System\SystemQueues;
use App\Jobs\RunSubscriptionCollect;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ProcessCollectAction
{
    public static function exec(Model $model): Payment
    {
        $model->refresh();
        $gateway = $model->gateway->getStrategy();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($model->toArray())
            ->loadInstrument(Crypt::decryptString($model->token))
            ->prepareBody()
            ->sendCollectPayment();

        $status = $model->gateway->getGatewayStatuses()::tryFrom($gateway->status);
        $model->gateway_status = $status ? $status->value : null;

        self::queueNextCollect($model);

        return self::buildPayment($model, $gateway);
    }

    private static function buildPayment(Subscription $subscription, $gateway): Payment
    {
        $payment = new Payment();
        $payment->fill($subscription->toArray());
        $payment->subscription_id = $subscription->id;
        $payment->request_id = $gateway->getRequestId();
        $payment->save();

        return $payment;
    }

    private static function queueNextCollect(Subscription $model): void
    {
        RunSubscriptionCollect::dispatchIf($model->active && !$model->expires_at->isPast(), $model)
            ->onQueue(SystemQueues::Subscriptions->value)
            ->delay($model->is_paid_monthly ? now()->addMonth() : now()->addYear());
    }
}
