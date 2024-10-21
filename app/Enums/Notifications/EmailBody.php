<?php

declare(strict_types=1);

namespace App\Enums\Notifications;

enum EmailBody: string
{
    case CollectPreAlert = 'Your subscription payment will be collected in 2 minutes.';
    case CollectAlert = 'Your payment has been collected.';
    case CollectFailed = 'There was an issue collecting your payment.';
    case SubscriptionSuspended = 'Your subscription has been suspended because we where unable to collect your payment.';
    case SubscriptionEnding = 'Your subscription is ending tomorrow. Please, update your payment method or subscribe again.';
    case PaymentDeadline = 'You have a pending bill that is expiring in 5 hours. Please, pay it as soon as possible to avoid penalties in the final amount.';
    case ExpiredReport = 'A new report of expired payments is available.';
}
