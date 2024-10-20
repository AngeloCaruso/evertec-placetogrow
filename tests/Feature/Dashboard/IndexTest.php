<?php

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Actions\Dashboard\GetBillingStatsAction;
use App\Actions\Dashboard\GetDonationStatsAction;
use App\Actions\Dashboard\GetSubscriptionStatsAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Microsites\MicrositeType;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public Role $testRole;
    public Permission $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', MicrositePermissions::Dashboard);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_logged_user_can_see_dashboard(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_logged_user_cannot_see_dashboard_without_permission(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('dashboard'));
        $response->assertStatus(403);
    }

    public function test_get_billing_stats_action(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $microsite = Microsite::factory()->type(MicrositeType::Billing)->create();

        Payment::factory()
            ->count(5)
            ->withEmail($user->email)
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['microsite_id' => $microsite->id]);

        Payment::factory()
            ->count(2)
            ->withEmail($user->email)
            ->withPlacetopayGateway()
            ->rejected()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['microsite_id' => $microsite->id]);

        Payment::factory()
            ->count(3)
            ->withEmail($user->email)
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['microsite_id' => $microsite->id, 'limit_date' => now()->subDay()]);

        Payment::factory()
            ->count(6)
            ->withEmail($user->email)
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['microsite_id' => $microsite->id, 'limit_date' => now()->addDay()]);

        $data = GetBillingStatsAction::exec();

        $this->assertEquals(5, $data['paidBills']);
        $this->assertEquals(2, $data['rejectedBills']);
        $this->assertEquals(3, $data['expiredBills']);
        $this->assertEquals(6, $data['unpaidBills']);
    }

    public function test_get_billing_stats_action_with_no_data(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $data = GetBillingStatsAction::exec();

        $this->assertEquals(0, $data['paidBills']);
        $this->assertEquals(0, $data['rejectedBills']);
        $this->assertEquals(0, $data['expiredBills']);
        $this->assertEquals(0, $data['unpaidBills']);
    }

    public function test_get_donation_stats_action(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $microsite1 = Microsite::factory()->type(MicrositeType::Donation)->create();
        $microsite2 = Microsite::factory()->type(MicrositeType::Donation)->create();

        $payments1 = Payment::factory()
            ->count(5)
            ->withEmail($user->email)
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['microsite_id' => $microsite1->id]);

        $payments2 = Payment::factory()
            ->count(2)
            ->withEmail($user->email)
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['microsite_id' => $microsite2->id]);

        $data = GetDonationStatsAction::exec();

        $this->assertArrayHasKey($microsite1->name, $data['sites']);
        $this->assertArrayHasKey($microsite2->name, $data['sites']);

        $this->assertEquals($payments1->sum('amount'), $data['sites'][$microsite1->name]);
        $this->assertEquals($payments2->sum('amount'), $data['sites'][$microsite2->name]);
    }

    public function test_get_donation_stats_action_with_no_data(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $data = GetDonationStatsAction::exec();

        $this->assertEmpty($data['sites']);
    }

    public function test_get_subscription_stats_action(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $microsite = Microsite::factory()->type(MicrositeType::Subscription)->create();

        Subscription::factory()
            ->count(5)
            ->withEmail($user->email)
            ->withMicrosite($microsite)
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['active' => true]);

        Subscription::factory()
            ->count(3)
            ->withEmail($user->email)
            ->withMicrosite($microsite)
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create(['active' => false]);

        $data = GetSubscriptionStatsAction::exec();

        $this->assertEquals(5, $data['active']);
        $this->assertEquals(3, $data['inactive']);
    }

    public function test_get_subscription_stats_action_with_no_data(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $data = GetSubscriptionStatsAction::exec();

        $this->assertEquals(0, $data['active']);
        $this->assertEquals(0, $data['inactive']);
    }
}
