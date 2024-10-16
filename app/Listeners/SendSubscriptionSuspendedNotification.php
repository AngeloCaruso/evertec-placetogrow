<?php

namespace App\Listeners;

use App\Enums\Notifications\EmailBody;
use App\Events\SubscriptionSuspended;
use App\Notifications\PaymentCollectNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendSubscriptionSuspendedNotification
{
    public function __construct() {}

    public function handle(SubscriptionSuspended $event): void
    {
        Notification::route('mail', $event->subscription->email)
            ->notify((new PaymentCollectNotification(EmailBody::SubscriptionSuspended->value)));
    }
}
