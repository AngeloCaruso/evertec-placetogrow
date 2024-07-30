<?php

namespace App\Actions\Payments;

use Illuminate\Database\Eloquent\Model;

class StorePaymentAction
{
    public static function exec(array $data, Model $model): mixed
    {
        $model->fill($data);
        $model->expires_at = now()->addHours($model->microsite->expiration_payment_time)->format('c');
        $model->return_url = route('public.microsite.show', $model->microsite->id);
        $model->save();

        return $model;
    }
}
