<?php

namespace App\Http\Controllers;

use App\Actions\Payments\ProcessPaymentAction;
use App\Actions\Payments\StorePaymentAction;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\MicrositeResource;
use App\Http\Resources\PaymentResource;
use App\Jobs\UpdatePaymentStatus;
use App\Models\Payment;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function show(Payment $reference)
    {
        UpdatePaymentStatus::dispatch($reference)->onQueue('payments');

        return Inertia::render('Payment/Info', [
            'payment' => new PaymentResource($reference),
            'site' => new MicrositeResource($reference->microsite)
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        $payment = StorePaymentAction::exec($request->validated(), new Payment());
        $payment = ProcessPaymentAction::exec($payment);

        if (is_null($payment->payment_url)) {
            return to_route('public.microsite.index')->with('error', 'Error processing payment');
        }

        return Inertia::location($payment->payment_url);
    }
}
