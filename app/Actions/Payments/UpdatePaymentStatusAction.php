<?php

namespace App\Actions\Payments;

use Illuminate\Database\Eloquent\Model;

class UpdatePaymentStatusAction
{
    public static function exec(Model $model): mixed
    {
        $gatewayType = $model->gateway;

        if ($model->gateway_status !== $gatewayType->getGatewayStatuses()::Pending->value) {
            return $model;
        }

        $gateway = $gatewayType->getStrategy();
        $gateway->setRequestId($model->request_id)
            ->loadConfig()
            ->loadAuth()
            ->prepareBody()
            ->getStatus();

        $model->gateway_status = $gatewayType->getGatewayStatuses()::tryFrom($gateway->status)->value;
        $model->update();

        return $model;
    }
}
