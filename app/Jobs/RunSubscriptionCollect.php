<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Subscriptions\CancelSubscriptionAction;
use App\Actions\Subscriptions\ProcessCollectAction;
use App\Enums\Notifications\EmailBody;
use App\Enums\System\SystemQueues;
use App\Events\PaymentCollected;
use App\Events\SubscriptionSuspended;
use App\Models\Subscription;
use App\Notifications\PaymentCollectNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class RunSubscriptionCollect implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries;

    public function __construct(
        public Subscription $subscription
    ) {
        $this->tries = $this->subscription->microsite->payment_retries;
    }

    public function handle(): void
    {
        if (!$this->subscription->active || $this->subscription->payment_is_expired) {
            return;
        }

        $payment = ProcessCollectAction::exec($this->subscription);

        if ($payment->gateway_status === $payment->gateway->getGatewayStatuses()::Rejected->value) {
            PaymentCollected::dispatch($payment, EmailBody::CollectFailed);

            $this->release(now()->addMinutes($this->subscription->microsite->payment_retry_interval));
            return;
        }

        if ($payment->gateway_status === $payment->gateway->getGatewayStatuses()::Approved->value) {
            PaymentCollected::dispatch($payment, EmailBody::CollectAlert);

            $this->queueNextCollect($this->subscription);
            return;
        }
    }

    public function failed()
    {
        if ($this->attempts() === $this->tries) {
            $this->subscription->active = false;
            $this->subscription->save();

            CancelSubscriptionAction::exec([], $this->subscription);
            SubscriptionSuspended::dispatch($this->subscription);
        }
    }

    private static function queueNextCollect(Subscription $model): void
    {
        Notification::route('mail', $model->email)
            ->notify(
                (new PaymentCollectNotification(EmailBody::CollectPreAlert->value))
                    ->delay($model->is_paid_monthly ? now()->addMonth()->subHour() : now()->addYear()->subHour()),
            );

        RunSubscriptionCollect::dispatch($model)
            ->onQueue(SystemQueues::Subscriptions->value)
            ->delay($model->is_paid_monthly ? now()->addMonth() : now()->addYear());
    }
}
