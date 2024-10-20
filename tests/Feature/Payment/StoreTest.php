<?php

declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Actions\Payments\StorePaymentAction;
use App\Enums\Microsites\MicrositeType;
use App\Enums\Payments\PaymentType;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_store_controller_redirects_correctly(): void
    {
        $requestId = 1;
        $processUrl = "https://placetopay.com/session/$requestId";

        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::response([
                'status' => ['status' => 'OK'],
                'requestId' => $requestId,
                'processUrl' => $processUrl,
            ], 200),
        ]);

        $data = Payment::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('public.payments.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect($processUrl);
    }

    public function test_payment_store_controller_redirects_correctly_with_billing(): void
    {
        $requestId = 1;
        $processUrl = "https://placetopay.com/session/$requestId";

        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::response([
                'status' => ['status' => 'OK'],
                'requestId' => $requestId,
                'processUrl' => $processUrl,
            ], 200),
        ]);

        $microsite = Microsite::factory()->type(MicrositeType::Billing)->create();
        $payment = Payment::factory()
            ->fakeReference()
            ->create([
                'microsite_id' => $microsite->id,
            ])
            ->toArray();

        $response = $this->post(route('public.payments.store'), $payment);

        $response->assertStatus(302);
        $response->assertRedirect($processUrl);
    }

    public function test_payment_store_controller_redirects_back_when_a_error_occurs(): void
    {
        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::response([
                'status' => ['status' => 'FAILED'],
            ], 401),
        ]);

        $data = Payment::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('public.payments.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('public.microsite.index'));
        $response->assertSessionHas('error', 'An error ocurred trying to process the payment. Please try again later.');
    }

    public function test_payment_store_action(): void
    {
        $data = Payment::factory()->make()->toArray();
        StorePaymentAction::exec($data, new Payment());

        $this->assertDatabaseHas('payments', $data);
    }

    public function test_payment_store_action_with_billing(): void
    {
        $microsite = Microsite::factory()->type(MicrositeType::Billing)->create();
        $payment = Payment::factory()->fakeReference()->create(['microsite_id' => $microsite->id]);

        StorePaymentAction::exec($payment->toArray(), new Payment());

        $this->assertDatabaseHas('payments', $payment->only('reference'));
    }
}
