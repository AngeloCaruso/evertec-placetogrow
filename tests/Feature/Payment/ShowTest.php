<?php

namespace Tests\Feature\Payment;

use App\Enums\Payments\PaymentPermissions;
use App\Http\Resources\MicrositeResource;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', PaymentPermissions::View);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

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

    public function test_logged_user_can_see_a_payment_in_admin()
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $payment = Payment::factory()
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->withEmail($user->email)
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $response = $this->get(route('payments.show', $payment));
        $response->assertStatus(200);
    }
}
