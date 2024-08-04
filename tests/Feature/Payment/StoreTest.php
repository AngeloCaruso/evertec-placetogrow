<?php

namespace Tests\Feature\Payment;

use App\Actions\Payments\StorePaymentAction;
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
                'processUrl' => $processUrl
            ], 200)
        ]);

        $data = Payment::factory()->make()->toArray();
        $response = $this->post(route('public.payments.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect($processUrl);
    }

    public function test_payment_store_controller_redirects_back_when_a_error_occurs(): void
    {
        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::response([
                'status' => ['status' => 'FAILED'],
            ], 401)
        ]);

        $data = Payment::factory()->make()->toArray();
        $response = $this->post(route('public.payments.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('public.microsite.index'));
        $response->assertSessionHas('error', 'Error processing payment');
    }

    public function test_payment_store_action(): void
    {
        $data = Payment::factory()->make()->toArray();
        StorePaymentAction::exec($data, new Payment());

        $this->assertDatabaseHas('payments', $data);
    }
}
