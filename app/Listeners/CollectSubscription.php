<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\Microsites\SubscriptionCollectType;
use App\Enums\Notifications\EmailBody;
use App\Enums\System\SystemQueues;
use App\Events\SubscriptionApproved;
use App\Jobs\RunSubscriptionCollect;
use App\Notifications\PaymentCollectNotification;
use App\Notifications\PaymentDeadlineNotification;
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
                    (new PaymentCollectNotification(EmailBody::CollectPreAlert->value, $event->subscription))
                        ->delay($event->subscription->is_paid_monthly ? now()->addMonth()->subHour() : now()->addYear()->subHour()),
                );

            RunSubscriptionCollect::dispatch($event->subscription)
                ->onQueue(SystemQueues::Subscriptions->value)
                ->delay($event->subscription->is_paid_monthly ? now()->addMonth() : now()->addYear());
        }

        Notification::route('mail', $event->subscription->email)
            ->notify(
                (new PaymentDeadlineNotification(EmailBody::SubscriptionEnding->value, $event->subscription->microsite->name, $event->subscription->microsite->type->value))
                    ->delay($event->subscription->valid_until_date->subHour()),
            );
    }
}
