<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Enums\Microsites\MicrositeType;
use Illuminate\Database\Eloquent\Model;

class ProcessPaymentAction
{
    public static function exec(Model $model): Model
    {
        $payment = $model->toArray();

        if ($model->microsite->type === MicrositeType::Billing) {
            $payment['amount'] = $model->total_amount;
        }

        $gateway = $model->gateway->getStrategy();
        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($payment)
            ->prepareBody()
            ->send();

        $model->payment_url = $gateway->getRedirectUrl();
        $model->request_id = $gateway->getRequestId();

        $model->update();

        return $model;
    }
}
