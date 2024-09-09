<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Enums\Microsites\MicrositeType;
use App\Enums\Payments\PaymentType;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class StorePaymentAction
{
    public static function exec(array $data, Model $model): Model
    {
        $now = now();
        if (isset($data['reference'])) {
            $payment = Payment::where('reference', $data['reference'])->first();
            if ($payment && $payment->microsite->type === MicrositeType::Billing) {
                $payment->fill($data);
                $payment->return_url = route('public.payments.show', $data['reference']);
                $payment->expires_at = $now->addHours($payment->microsite->expiration_payment_time)->format('c');
                $payment->gateway_status = $payment->gateway->getGatewayStatuses()::Pending;
                $payment->save();
                return $payment;
            }
        }

        $model->fill($data);

        if ($model->payment_type === PaymentType::Subscription) {
            $model->save();
            return $model;
        }

        $reference = $model->microsite->slug . '-' . $now->format('YmdHis');

        $model->reference = $reference;
        $model->expires_at = $now->addHours($model->microsite->expiration_payment_time)->format('c');
        $model->gateway_status = $model->gateway->getGatewayStatuses()::Pending;
        $model->return_url = route('public.payments.show', $reference);

        $model->save();

        return $model;
    }
}
