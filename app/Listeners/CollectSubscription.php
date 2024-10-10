<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\Microsites\SubscriptionCollectType;
use App\Enums\Notifications\EmailBody;
use App\Enums\System\SystemQueues;
use App\Events\SubscriptionApproved;
use App\Jobs\RunSubscriptionCollect;
use App\Notifications\PaymentCollectNotification;
use Illuminate\Support\Facades\Notification;

class CollectSubscription
{
    public function __construct() {}

    public function handle(SubscriptionApproved $event): void
    {
        $collectType = $event->subscription->microsite->charge_collect;

        if ($collectType === SubscriptionCollectType::UpFront) {
            RunSubscriptionCollect::dispatch($event->subscription)
                ->onQueue(SystemQueues::Subscriptions->value);
        }

        if ($collectType === SubscriptionCollectType::PayLater) {
            Notification::route('mail', $event->subscription->email)
                ->notify(
                    (new PaymentCollectNotification(EmailBody::CollectPreAlert->value))
                        ->delay($event->subscription->is_paid_monthly ? now()->addMonth()->subHour() : now()->addYear()->subHour()),
                );

            RunSubscriptionCollect::dispatch($event->subscription)
                ->onQueue(SystemQueues::Subscriptions->value)
                ->delay($event->subscription->is_paid_monthly ? now()->addMonth() : now()->addYear());
        }
    }
}
