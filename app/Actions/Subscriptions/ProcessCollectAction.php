<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\Notifications\EmailBody;
use App\Enums\System\SystemQueues;
use App\Events\PaymentCollected;
use App\Jobs\RunSubscriptionCollect;
use App\Models\Payment;
use App\Models\Subscription;
use App\Notifications\PaymentCollectNotification;
use App\Services\Gateways\PlacetopayGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

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

        Log::info('Sub collect data:');
        Log::info($gateway->sessionData);

        if (!$gateway->requestId) {
            return null;
        }

        $status = $model->gateway->getGatewayStatuses()::tryFrom($gateway->status);
        $model->gateway_status = $status ? $status->value : null;


        self::queueNextCollect($model);

        return self::buildPayment($model, $gateway);
    }

    private static function buildPayment(Subscription $subscription, PlacetopayGateway $gateway): Payment
    {
        $payment = new Payment();
        $payment->fill($subscription->toArray());
        $payment->subscription_id = $subscription->id;
        $payment->request_id = $gateway->getRequestId();
        $payment->save();

        PaymentCollected::dispatch($payment);

        return $payment;
    }

    private static function queueNextCollect(Subscription $model): void
    {
        Notification::route('mail', $model->email)
            ->notify(
                (new PaymentCollectNotification(EmailBody::CollectPreAlert->value))
                    ->delay($model->is_paid_monthly ? now()->addMonth()->subHour() : now()->addYear()->subHour()),
            );

        RunSubscriptionCollect::dispatchIf($model->active  && !$model->expires_at->isPast(), $model)
            ->onQueue(SystemQueues::Subscriptions->value)
            ->delay($model->is_paid_monthly ? now()->addMonth() : now()->addYear());
    }
}
