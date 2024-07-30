<?php

namespace App\Enums\Gateways\Status;

enum PaypalStatus: string
{
    case Default = 'default';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
