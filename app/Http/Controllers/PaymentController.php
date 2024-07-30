<?php

namespace App\Http\Controllers;

use App\Actions\Payments\StorePaymentAction;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Models\Payment;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request)
    {
        $payment = StorePaymentAction::exec($request->validated(), new Payment());
        $gateway = $payment->gateway->getStrategy();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($payment->toArray())
            ->prepareBody()
            ->send();

        $paymentUrl = $gateway->getRedirectUrl();

        if (is_null($paymentUrl)) {
            return to_route('public.microsite.index')->with('error', 'Error processing payment');
        }
        return Inertia::location($paymentUrl);
    }
}
