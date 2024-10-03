<?php

declare(strict_types=1);

namespace Tests\Feature\Subscription;

use App\Actions\Subscriptions\GetAllSubscriptionsAction;
use App\Enums\Subscriptions\SubscriptionPermissions;
use App\Livewire\Subscriptions\ListSubscriptions;
use App\Models\Microsite;
use App\Models\Subscription;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public Role $testRole;
    public Permission $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', SubscriptionPermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_logged_user_can_see_subscriptions(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $site = Microsite::factory()->create();
        $subscription = Subscription::factory()
            ->count(5)
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $response = $this->get(route('subscriptions.index'));
        $response->assertStatus(200);
    }

    public function test_get_all_subscriptions_action(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $site = Microsite::factory()->create();
        $subscription = Subscription::factory()
            ->count(5)
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $retrieved = GetAllSubscriptionsAction::exec([], new Subscription());

        $this->assertEquals($subscription->count(), $retrieved->count());
    }

    public function test_logged_user_can_see_only_his_subscriptions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user->assignRole($this->testRole));

        $site = Microsite::factory()->create();
        $userSubscriptions = Subscription::factory()
            ->count(3)
            ->withEmail($user->email)
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $otherSubscriptions = Subscription::factory()
            ->count(2)
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        Livewire::test(ListSubscriptions::class)
            ->assertCanSeeTableRecords($userSubscriptions)
            ->assertCanNotSeeTableRecords($otherSubscriptions)
            ->assertCountTableRecords(3);
    }

    public function test_admin_user_can_see_all_subscriptions(): void
    {
        $adminRole = Role::factory()->admin()->create();
        $adminRole->givePermissionTo($this->permission);
        $user = User::factory()->create();
        $this->actingAs($user->assignRole($adminRole));

        $site = Microsite::factory()->create();
        $userSubscriptions = Subscription::factory()
            ->count(3)
            ->withEmail($user->email)
            ->withMicrosite($site)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        Livewire::test(ListSubscriptions::class)
            ->assertCanSeeTableRecords($userSubscriptions)
            ->assertCountTableRecords(3);
    }
}
