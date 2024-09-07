<?php

namespace Tests\Feature\Subscription;

use App\Actions\Subscriptions\StoreSubscriptionAction;
use App\Models\Microsite;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_store_controller_redirects_correctly(): void
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

        $site = Microsite::factory()->create();
        $data = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->make()
            ->toArray();

        $response = $this->post(route('public.subscription.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect($processUrl);
    }

    public function test_subscription_store_controller_redirects_back_when_a_error_occurs(): void
    {
        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::response([
                'status' => ['status' => 'FAILED'],
            ], 401)
        ]);

        $site = Microsite::factory()->create();
        $data = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->make()
            ->toArray();

        $response = $this->post(route('public.subscription.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('public.microsite.index'));
        $response->assertSessionHas('error', 'Error processing subscription');
    }

    public function test_subscription_store_action(): void
    {
        $site = Microsite::factory()->create();
        $data = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->make()
            ->toArray();

        StoreSubscriptionAction::exec($data, new Subscription());

        $this->assertDatabaseHas('subscriptions', $data);
    }
}
