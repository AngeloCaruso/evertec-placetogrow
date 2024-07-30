<?php

namespace App\Enums\Gateways;

use App\Contracts\PaymentStrategy;
use App\Enums\Gateways\Status\PaypalStatus;
use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Services\Gateways\PaypalGateway;
use App\Services\Gateways\PlacetopayGateway;

enum GatewayType: string
{
    case Placetopay = 'placetopay';
    case Paypal = 'paypal';

    public function getStrategy(): PaymentStrategy
    {
        return match ($this) {
            self::Placetopay => new PlacetopayGateway(),
            self::Paypal => new PaypalGateway(),
        };
    }

    public function getGatewayStatuses()
    {
        return match ($this) {
            self::Placetopay => PlacetopayStatus::cases(),
            self::Paypal => PaypalStatus::cases(),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
