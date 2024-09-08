<?php

declare(strict_types=1);

namespace Tests\Feature\Subscription;

use App\Actions\Subscriptions\ProcessCollectAction;
use App\Actions\Subscriptions\StoreSubscriptionAction;
use App\Enums\Microsites\SubscriptionCollectType;
use App\Enums\System\SystemQueues;
use App\Jobs\RunSubscriptionCollect;
use App\Models\Microsite;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
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

    public function test_subscription_store_controller_runs_collect_when_payment_is_later(): void
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

        $site = Microsite::factory()->create([
            'charge_collect' => SubscriptionCollectType::PayLater,
        ]);
        $data = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->make()
            ->toArray();

        Queue::fake();

        $response = $this->post(route('public.subscription.store'), $data);

        Queue::assertPushedOn(SystemQueues::Subscriptions->value, RunSubscriptionCollect::class);

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

    public function test_process_collect_action(): void
    {
        $user = User::factory()->create();
        $site = Microsite::factory()->create();
        $subscription = Subscription::factory()
            ->withEmail($user->email)
            ->withMicrosite($site)
            ->fakeReference()
            ->withPlacetopayGateway()
            ->fakeToken()
            ->requestId(1)
            ->approved()
            ->fakeReturnUrl()
            ->create(['expires_at' => now()->subMonth()]);

        Http::fake([
            config('services.placetopay.url') . '/api/collect' => Http::response([
                "requestId" => 1,
                "status" => [
                    "status" => "APPROVED",
                    "reason" => "00",
                    "message" => "La petición ha sido aprobada exitosamente",
                    "date" => "2021-11-30T15:49:47-05:00",
                ],
                "request" => [
                    "locale" => "es_CO",
                    "payer" => [
                        "document" => "1033332222",
                        "documentType" => "CC",
                        "name" => "Name",
                        "surname" => "LastName",
                        "email" => "dnetix1@app.com",
                        "mobile" => "3111111111",
                        "address" => ["postalCode" => "12345"],
                    ],
                    "payment" => [
                        "reference" => "1122334455",
                        "description" => "Prueba",
                        "amount" => ["currency" => "USD", "total" => 100],
                        "allowPartial" => false,
                        "subscribe" => false,
                    ],
                    "returnUrl" => "https://redirection.test/home",
                    "ipAddress" => "127.0.0.1",
                    "userAgent" => "PlacetoPay Sandbox",
                    "expiration" => "2021-12-30T00:00:00-05:00",
                ],
                "payment" => [
                    [
                        "status" => [
                            "status" => "APPROVED",
                            "reason" => "00",
                            "message" => "Aprobada",
                            "date" => "2021-11-30T15:49:36-05:00",
                        ],
                        "internalReference" => 1,
                        "paymentMethod" => "visa",
                        "paymentMethodName" => "Visa",
                        "issuerName" => "JPMORGAN CHASE BANK, N.A.",
                        "amount" => [
                            "from" => ["currency" => "USD", "total" => 100],
                            "to" => ["currency" => "USD", "total" => 100],
                            "factor" => 1,
                        ],
                        "authorization" => "000000",
                        "reference" => "1122334455",
                        "receipt" => "241516",
                        "franchise" => "DF_VS",
                        "refunded" => false,
                        "processorFields" => [
                            [
                                "keyword" => "lastDigits",
                                "value" => "1111",
                                "displayOn" => "none",
                            ],
                        ],
                    ],
                ],
                "subscription" => null,
            ], 200)
        ]);

        ProcessCollectAction::exec($subscription);

        $this->assertDatabaseHas('payments', ['subscription_id' => $subscription->id]);
    }

    public function test_process_collect_action_triggers_next_collect(): void
    {
        $user = User::factory()->create();
        $site = Microsite::factory()->create(['is_paid_monthly' => true]);
        $subscription = Subscription::factory()
            ->withEmail($user->email)
            ->withMicrosite($site)
            ->fakeReference()
            ->withPlacetopayGateway()
            ->fakeToken()
            ->requestId(1)
            ->approved()
            ->fakeReturnUrl()
            ->create(['expires_at' => now()->addYear()]);

        Http::fake([
            config('services.placetopay.url') . '/api/collect' => Http::response([
                "requestId" => 1,
                "status" => [
                    "status" => "APPROVED",
                    "reason" => "00",
                    "message" => "La petición ha sido aprobada exitosamente",
                    "date" => "2021-11-30T15:49:47-05:00",
                ],
                "request" => [
                    "locale" => "es_CO",
                    "payer" => [
                        "document" => "1033332222",
                        "documentType" => "CC",
                        "name" => "Name",
                        "surname" => "LastName",
                        "email" => "dnetix1@app.com",
                        "mobile" => "3111111111",
                        "address" => ["postalCode" => "12345"],
                    ],
                    "payment" => [
                        "reference" => "1122334455",
                        "description" => "Prueba",
                        "amount" => ["currency" => "USD", "total" => 100],
                        "allowPartial" => false,
                        "subscribe" => false,
                    ],
                    "returnUrl" => "https://redirection.test/home",
                    "ipAddress" => "127.0.0.1",
                    "userAgent" => "PlacetoPay Sandbox",
                    "expiration" => "2021-12-30T00:00:00-05:00",
                ],
                "payment" => [
                    [
                        "status" => [
                            "status" => "APPROVED",
                            "reason" => "00",
                            "message" => "Aprobada",
                            "date" => "2021-11-30T15:49:36-05:00",
                        ],
                        "internalReference" => 1,
                        "paymentMethod" => "visa",
                        "paymentMethodName" => "Visa",
                        "issuerName" => "JPMORGAN CHASE BANK, N.A.",
                        "amount" => [
                            "from" => ["currency" => "USD", "total" => 100],
                            "to" => ["currency" => "USD", "total" => 100],
                            "factor" => 1,
                        ],
                        "authorization" => "000000",
                        "reference" => "1122334455",
                        "receipt" => "241516",
                        "franchise" => "DF_VS",
                        "refunded" => false,
                        "processorFields" => [
                            [
                                "keyword" => "lastDigits",
                                "value" => "1111",
                                "displayOn" => "none",
                            ],
                        ],
                    ],
                ],
                "subscription" => null,
            ], 200)
        ]);

        Queue::fake();

        ProcessCollectAction::exec($subscription);

        Queue::assertPushedOn(SystemQueues::Subscriptions->value, RunSubscriptionCollect::class);
    }

    public function test_process_collect_action_does_not_triggers_next_collect(): void
    {
        $user = User::factory()->create();
        $site = Microsite::factory()->create(['is_paid_monthly' => true]);
        $subscription = Subscription::factory()
            ->withEmail($user->email)
            ->withMicrosite($site)
            ->fakeReference()
            ->withPlacetopayGateway()
            ->fakeToken()
            ->requestId(1)
            ->approved()
            ->fakeReturnUrl()
            ->create(['expires_at' => now()->subDay()]);

        Http::fake([
            config('services.placetopay.url') . '/api/collect' => Http::response([
                "requestId" => 1,
                "status" => [
                    "status" => "APPROVED",
                    "reason" => "00",
                    "message" => "La petición ha sido aprobada exitosamente",
                    "date" => "2021-11-30T15:49:47-05:00",
                ],
                "request" => [
                    "locale" => "es_CO",
                    "payer" => [
                        "document" => "1033332222",
                        "documentType" => "CC",
                        "name" => "Name",
                        "surname" => "LastName",
                        "email" => "dnetix1@app.com",
                        "mobile" => "3111111111",
                        "address" => ["postalCode" => "12345"],
                    ],
                    "payment" => [
                        "reference" => "1122334455",
                        "description" => "Prueba",
                        "amount" => ["currency" => "USD", "total" => 100],
                        "allowPartial" => false,
                        "subscribe" => false,
                    ],
                    "returnUrl" => "https://redirection.test/home",
                    "ipAddress" => "127.0.0.1",
                    "userAgent" => "PlacetoPay Sandbox",
                    "expiration" => "2021-12-30T00:00:00-05:00",
                ],
                "payment" => [
                    [
                        "status" => [
                            "status" => "APPROVED",
                            "reason" => "00",
                            "message" => "Aprobada",
                            "date" => "2021-11-30T15:49:36-05:00",
                        ],
                        "internalReference" => 1,
                        "paymentMethod" => "visa",
                        "paymentMethodName" => "Visa",
                        "issuerName" => "JPMORGAN CHASE BANK, N.A.",
                        "amount" => [
                            "from" => ["currency" => "USD", "total" => 100],
                            "to" => ["currency" => "USD", "total" => 100],
                            "factor" => 1,
                        ],
                        "authorization" => "000000",
                        "reference" => "1122334455",
                        "receipt" => "241516",
                        "franchise" => "DF_VS",
                        "refunded" => false,
                        "processorFields" => [
                            [
                                "keyword" => "lastDigits",
                                "value" => "1111",
                                "displayOn" => "none",
                            ],
                        ],
                    ],
                ],
                "subscription" => null,
            ], 200)
        ]);

        Queue::fake();

        ProcessCollectAction::exec($subscription);

        Queue::assertNothingPushed(RunSubscriptionCollect::class);
    }
}
