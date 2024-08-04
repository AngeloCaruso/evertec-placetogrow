<?php

namespace Tests\Feature\Payment;

use App\Http\Resources\MicrositeResource;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public function test_show_payment()
    {
        $payment = Payment::factory()
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $paymentResource = (new PaymentResource($payment))->response()->getData()->data;
        $siteResource = (new MicrositeResource($payment->microsite))->response()->getData()->data;

        $this->get(route('public.payments.show', $payment))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Payment/Info')
                    ->has('payment', function (AssertableInertia $page) use ($paymentResource) {
                        $page
                            ->where('data.reference', $paymentResource->reference)
                            ->where('data.amount', $paymentResource->amount)
                            ->where('data.currency', $paymentResource->currency)
                            ->where('data.date', $paymentResource->date)
                            ->where('data.gateway_status', $paymentResource->gateway_status)
                            ->where('data.full_name', $paymentResource->full_name)
                            ->where('data.email', $paymentResource->email);
                    })
                    ->has('site', function (AssertableInertia $page) use ($siteResource) {
                        $page
                            ->where('data.logo', $siteResource->logo)
                            ->where('data.primary_color', $siteResource->primary_color)
                            ->where('data.name', $siteResource->name);
                    })
            );
    }

    public function test_show_payment_when_payment_does_not_exist()
    {
        $this->get(route('public.payments.show', 'unexisting-reference'))
            ->assertStatus(404);
    }
}
