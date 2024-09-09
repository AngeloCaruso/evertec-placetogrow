<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\Microsites\SubscriptionCollectType;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UpdateSubscriptionDataAction
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
            ->getSubscriptionData();

        self::updateSubscription($gateway, $gatewayType, $model);
        self::queueCollect($model);

        return $model;
    }

    private static function updateSubscription($gateway, $gatewayType, Subscription $model): void
    {
        try {
            if ($gateway->status && $gateway->status === $gatewayType->getGatewayStatuses()::Ok->value) {
                $model->gateway_status = $gatewayType->getGatewayStatuses()::Approved;

                if ($gateway->subscriptionData) {
                    $instrument = collect($gateway->subscriptionData['instrument']);

                    $model->token = $instrument->firstWhere('keyword', 'token')['value'];
                    $model->sub_token = $instrument->firstWhere('keyword', 'subtoken')['value'];
                }
            }

            $model->update();
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    private static function queueCollect(Subscription $model): void
    {
        $model->refresh();
        if (
            $model->gateway_status === $model->gateway->getGatewayStatuses()::Approved->value &&
            $model->microsite->charge_collect === SubscriptionCollectType::UpFront
        ) {
            ProcessCollectAction::exec($model);
        }
    }
}
