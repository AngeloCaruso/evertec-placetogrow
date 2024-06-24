<?php

namespace App\Enums\Microsites;

enum MicrositeType: string
{
    case Basic = 'basic';
    case Billing = 'billing';
    case Subscription = 'subscription';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
