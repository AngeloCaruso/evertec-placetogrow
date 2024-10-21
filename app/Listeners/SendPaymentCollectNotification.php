<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PaymentCollected;
use App\Notifications\PaymentCollectNotification;
use Illuminate\Support\Facades\Notification;

class SendPaymentCollectNotification
{
    public function __construct() {}

    public function handle(PaymentCollected $event): void
    {
        Notification::route('mail', $event->payment->email)
            ->notify((new PaymentCollectNotification($event->emailBody->value, $event->payment)));
    }
}
