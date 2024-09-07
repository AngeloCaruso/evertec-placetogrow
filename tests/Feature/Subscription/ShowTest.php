<?php

declare(strict_types=1);

namespace Tests\Feature\Subscription;

use App\Http\Resources\MicrositeResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\Microsite;
use App\Models\Subscription;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        // $this->permission = Permission::firstWhere('name', PaymentPermissions::View);
        // $this->testRole = Role::factory()->create();
        // $this->testRole->givePermissionTo($this->permission);
    }

    public function test_show_subscription(): void
    {
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

        $subscriptionResource = (new SubscriptionResource($subscription))->response()->getData()->data;
        $siteResource = (new MicrositeResource($subscription->microsite))->response()->getData()->data;

        $this->get(route('public.subscription.show', $subscription))
            ->assertInertia(
                fn(AssertableInertia $page) => $page
                    ->component('Subscription/Info')
                    ->has('subscription', function (AssertableInertia $page) use ($subscriptionResource) {
                        $page
                            ->where('data.reference', $subscriptionResource->reference)
                            // ->where('data.amount', $subscriptionResource->amount)
                            ->where('data.currency', $subscriptionResource->currency)
                            ->where('data.date', $subscriptionResource->date)
                            ->where('data.gateway_status', $subscriptionResource->gateway_status)
                            ->where('data.email', $subscriptionResource->email);
                    })
                    ->has('site', function (AssertableInertia $page) use ($siteResource) {
                        $page
                            ->where('data.logo', $siteResource->logo)
                            ->where('data.primary_color', $siteResource->primary_color)
                            ->where('data.name', $siteResource->name);
                    })
            );
    }

    public function test_show_payment_when_payment_does_not_exist(): void
    {
        $this->get(route('public.payments.show', 'unexisting-reference'))
            ->assertStatus(404);
    }

    // public function test_logged_user_can_see_a_payment_in_admin(): void
    // {
    //     $user = User::factory()->create()->assignRole($this->testRole);
    //     $this->actingAs($user);

    //     $payment = Payment::factory()
    //         ->withPlacetopayGateway()
    //         ->withDefaultStatus()
    //         ->withEmail($user->email)
    //         ->requestId(1)
    //         ->fakeReference()
    //         ->fakeExpiresAt()
    //         ->fakeReturnUrl()
    //         ->create();

    //     $response = $this->get(route('payments.show', $payment));
    //     $response->assertStatus(200);
    // }
}
