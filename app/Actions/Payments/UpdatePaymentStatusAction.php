<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Events\PaymentCollected;
use Illuminate\Database\Eloquent\Model;

class UpdatePaymentStatusAction
{
    public static function exec(Model $model): Model
    {
        $model->refresh();
        $gatewayType = $model->gateway;

        if ($model->gateway_status !== $gatewayType->getGatewayStatuses()::Pending->value) {
            return $model;
        }

        $gateway = $gatewayType->getStrategy();
        $gateway->setRequestId((string) $model->request_id)
            ->loadConfig()
            ->loadAuth()
            ->prepareBody()
            ->getStatus();

        if ($gateway->status) {
            $status = $gatewayType->getGatewayStatuses()::tryFrom($gateway->status);
            $model->gateway_status = $status ? $status->value : null;
            $model->update();

            PaymentCollected::dispatch($model);
        }

        return $model;
    }
}
