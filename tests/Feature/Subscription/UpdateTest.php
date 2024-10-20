<?php

declare(strict_types=1);

namespace Tests\Feature\Subscription;

use App\Actions\Subscriptions\UpdateSubscriptionDataAction;
use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Enums\Microsites\SubscriptionCollectType;
use App\Enums\System\SystemQueues;
use App\Events\SubscriptionApproved;
use App\Jobs\RunSubscriptionCollect;
use App\Jobs\UpdateSubscriptionStatus;
use App\Models\Microsite;
use App\Models\Subscription;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake([SubscriptionApproved::class]);
    }

    public function test_update_placetopay_subscription_action(): void
    {
        $requestId = 1;
        Http::fake([
            config('services.placetopay.url') . "/api/session/$requestId" => Http::response([
                'status' => ['status' => 'APPROVED'],
                "subscription" => [
                    "status" => [
                        "status" => "OK",
                        "reason" => "00",
                        "message" => "La petición ha sido aprobada exitosamente",
                        "date" => "2022-07-27T14:51:27-05:00",
                    ],
                    "type" => "token",
                    "instrument" => [
                        [
                            "keyword" => "token",
                            "value" =>
                            "a3bfc8e2afb9ac5583922eccd6d2061c1b0592b099f04e352a894f37ae51cf1a",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "subtoken",
                            "value" => "8740257204881111",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "franchise",
                            "value" => "visa",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "franchiseName",
                            "value" => "Visa",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "issuerName",
                            "value" => "JPMORGAN CHASE BANK, N.A.",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "lastDigits",
                            "value" => "1111",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "validUntil",
                            "value" => "2029-12-31",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "installments",
                            "value" => null,
                            "displayOn" => "none",
                        ],
                    ],
                ],
            ], 200),
        ]);

        $site = Microsite::factory()->create(['charge_collect' => SubscriptionCollectType::PayLater->value]);
        $subscription = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        UpdateSubscriptionDataAction::exec($subscription);

        Event::assertDispatched(SubscriptionApproved::class);

        $subscription->refresh();

        $this->assertEquals('APPROVED', $subscription->gateway_status);
    }

    public function test_subscription_is_not_updated_if_not_in_pending_status(): void
    {
        $site = Microsite::factory()->create();
        $subscription = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        UpdateSubscriptionDataAction::exec($subscription);

        $subscription->refresh();

        $this->assertEquals('APPROVED', $subscription->gateway_status);
    }

    public function test_subscription_update_throws_exception(): void
    {
        $requestId = 1;
        Http::fake([
            config('services.placetopay.url') . "/api/session/$requestId" => Http::sequence(),
        ]);

        Log::shouldReceive('error')->once();

        $site = Microsite::factory()->create();
        $subscription = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        UpdateSubscriptionDataAction::exec($subscription);

        $subscription->refresh();

        $this->assertEquals(PlacetopayStatus::Pending, $subscription->status);
    }

    public function test_status_is_updated_with_the_queue(): void
    {
        Http::fake([
            config('services.placetopay.url') . "/api/session/1" => Http::response([
                'status' => ['status' => 'APPROVED'],
                "subscription" => [
                    "status" => [
                        "status" => "OK",
                        "reason" => "00",
                        "message" => "La petición ha sido aprobada exitosamente",
                        "date" => "2022-07-27T14:51:27-05:00",
                    ],
                    "type" => "token",
                    "instrument" => [
                        [
                            "keyword" => "token",
                            "value" =>
                            "a3bfc8e2afb9ac5583922eccd6d2061c1b0592b099f04e352a894f37ae51cf1a",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "subtoken",
                            "value" => "8740257204881111",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "franchise",
                            "value" => "visa",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "franchiseName",
                            "value" => "Visa",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "issuerName",
                            "value" => "JPMORGAN CHASE BANK, N.A.",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "lastDigits",
                            "value" => "1111",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "validUntil",
                            "value" => "2029-12-31",
                            "displayOn" => "none",
                        ],
                        [
                            "keyword" => "installments",
                            "value" => null,
                            "displayOn" => "none",
                        ],
                    ],
                ],
            ], 200),
        ]);

        $site = Microsite::factory()->create();
        $subscription = Subscription::factory()
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        Queue::fake();

        UpdateSubscriptionStatus::dispatch($subscription)->onQueue('subscriptions');

        Queue::assertPushedOn(SystemQueues::Subscriptions->value, UpdateSubscriptionStatus::class);
    }
}
