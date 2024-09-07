<?php

namespace App\Actions\Subscriptions;

use Illuminate\Database\Eloquent\Model;

class StoreSubscriptionAction
{
    public static function exec(array $data, Model $model): Model
    {
        $now = now();
        $model->fill($data);

        $reference = "SUB-{$model->microsite->slug}-{$now->format('YmdHis')}";

        $model->reference = $reference;
        $model->expires_at = $now->addHours($model->microsite->expiration_payment_time)->format('c');
        $model->return_url = route('public.subscription.show', $reference);
        $model->gateway_status = $model->gateway->getGatewayStatuses()::Pending;

        $model->save();

        return $model;
    }
}
