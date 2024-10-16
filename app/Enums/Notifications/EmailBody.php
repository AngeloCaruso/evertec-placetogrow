<?php

declare(strict_types=1);

namespace App\Enums\Notifications;

enum EmailBody: string
{
    case CollectPreAlert = 'Your subscription payment will be collected in 2 minutes.';
    case CollectAlert = 'Your payment has been collected.';
    case CollectFailed = 'Your payment has failed.';
    case SubscriptionSuspended = 'Your subscription has been suspended due to a constant fail of collect.';
}
