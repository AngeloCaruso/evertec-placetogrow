<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Payments\ProcessPaymentAction;
use App\Actions\Payments\StorePaymentAction;
use App\Enums\System\SystemQueues;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\MicrositeResource;
use App\Http\Resources\PaymentResource;
use App\Jobs\UpdatePaymentStatus;
use App\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PaymentController extends Controller
{
    public function show(Payment $reference): Response
    {
        UpdatePaymentStatus::dispatchIf($reference->status_is_pending, $reference)
            ->onQueue(SystemQueues::Payments->value);

        return Inertia::render('Payment/Info', [
            'payment' => new PaymentResource($reference),
            'site' => new MicrositeResource($reference->microsite),
        ]);
    }

    public function store(StorePaymentRequest $request): HttpFoundationResponse
    {
        $payment = StorePaymentAction::exec($request->validated(), new Payment());
        $payment = ProcessPaymentAction::exec($payment);

        if (is_null($payment->payment_url)) {
            return to_route('public.microsite.index')->with('error', 'An error ocurred trying to process the payment. Please try again later.');
        }

        return Inertia::location($payment->payment_url);
    }
}
