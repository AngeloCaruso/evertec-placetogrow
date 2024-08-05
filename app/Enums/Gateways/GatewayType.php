<?php

namespace App\Enums\Gateways;

use App\Contracts\PaymentStrategy;
use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Services\Gateways\PlacetopayGateway;

enum GatewayType: string
{
    case Placetopay = 'placetopay';

    public function getStrategy(): PaymentStrategy
    {
        return match ($this) {
            self::Placetopay => new PlacetopayGateway(),
        };
    }

    public function getGatewayStatuses()
    {
        return match ($this) {
            self::Placetopay => PlacetopayStatus::class,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
