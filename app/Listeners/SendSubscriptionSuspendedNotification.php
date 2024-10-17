<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\Notifications\EmailBody;
use App\Events\SubscriptionSuspended;
use App\Notifications\PaymentCollectNotification;
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
