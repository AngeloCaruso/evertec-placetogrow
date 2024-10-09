<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\Gateways\GatewayType;
use App\Events\SubscriptionApproved;
use App\Models\Subscription;
use App\Services\Gateways\PlacetopayGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
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

        return $model;
    }

    private static function updateSubscription(PlacetopayGateway $gateway, GatewayType $gatewayType, Subscription $model): void
    {
        try {
            if ($gateway->status && $gateway->status === $gatewayType->getGatewayStatuses()::Ok->value) {
                $model->gateway_status = $gatewayType->getGatewayStatuses()::Approved;

                if ($gateway->subscriptionData) {
                    $instrument = collect($gateway->subscriptionData['instrument']);

                    $model->token = Crypt::encryptString($instrument->firstWhere('keyword', 'token')['value']);
                    $model->sub_token = Crypt::encryptString($instrument->firstWhere('keyword', 'subtoken')['value']);
                }

                $model->update();

                SubscriptionApproved::dispatch($model);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
