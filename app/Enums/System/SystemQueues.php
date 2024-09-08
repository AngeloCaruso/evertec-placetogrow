<?php

namespace App\Enums\System;

enum SystemQueues: string
{
    case Default = 'default';
    case Subscriptions = 'subscriptions';
    case Payments = 'payments';
}
