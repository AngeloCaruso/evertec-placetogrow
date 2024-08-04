<?php

namespace App\Actions\Payments;

use Illuminate\Database\Eloquent\Model;

class StorePaymentAction
{
    public static function exec(array $data, Model $model): mixed
    {
        $now = now();
        $model->fill($data);

        $reference = $model->microsite->slug . '-' . $now->format('YmdHis');

        $model->reference = $reference;
        $model->expires_at = $now->addHours($model->microsite->expiration_payment_time)->format('c');
        $model->return_url = route('public.payments.show', $reference);
        $model->gateway_status = $model->gateway->getGatewayStatuses()::Pending;

        $model->save();

        return $model;
    }
}
