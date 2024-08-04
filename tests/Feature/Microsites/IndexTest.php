<?php

namespace Tests\Feature\Microsites;

use App\Actions\AccessControlList\StoreAclAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Microsites\MicrositeType;
use App\Enums\System\AccessRules;
use App\Livewire\Microsites\ListMicrosites;
use App\Models\AccessControlList;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', MicrositePermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_logged_user_can_see_microsites(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('microsites.index'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_microsites_via_acl(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);
        $sites = Microsite::factory()->count(5)->create();
        $sitesDenied = Microsite::factory()->count(5)->create();

        $acl = AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Allow->value)
            ->controllableType(Microsite::class)
            ->controllableIds($sites->pluck('id')->toArray())
            ->make();

        $deniedAcl = AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Deny->value)
            ->controllableType(Microsite::class)
            ->controllableIds($sitesDenied->pluck('id')->toArray())
            ->make();

        StoreAclAction::exec($acl->toArray(), new AccessControlList());
        StoreAclAction::exec($deniedAcl->toArray(), new AccessControlList());

        Livewire::test(ListMicrosites::class)
            ->assertCanSeeTableRecords($sites)
            ->assertCanNotSeeTableRecords($sitesDenied);
    }

    public function test_admin_user_can_see_all_microsites(): void
    {
        $adminRole = Role::factory()->admin()->create();
        $adminRole->givePermissionTo($this->permission);

        $this->actingAs(User::factory()->create()->assignRole($adminRole));

        $sites = Microsite::factory()->count(5)->create();

        Livewire::test(ListMicrosites::class)
            ->assertCanSeeTableRecords($sites);
    }

    public function test_active_scope()
    {
        $activeSites = Microsite::factory()->active()->count(5)->create();
        $inactiveSites = Microsite::factory()->inactive()->count(3)->create();

        $current = Microsite::query()->active()->get();

        $this->assertEquals($activeSites->count(), $current->count());
        $this->assertNotEquals($inactiveSites->count(), $current->count());
    }

    public function test_type_scope()
    {
        $billingSites = Microsite::factory()->type(MicrositeType::Billing)->count(2)->create();
        $donationSites = Microsite::factory()->type(MicrositeType::Donation)->count(3)->create();

        $current = Microsite::query()->type(MicrositeType::Billing->value)->get();
        $this->assertEquals($billingSites->count(), $current->count());

        $current = Microsite::query()->type(MicrositeType::Donation->value)->get();
        $this->assertEquals($donationSites->count(), $current->count());
    }

    public function test_search_scope()
    {
        $sites = Microsite::factory()->count(5)->create();
        $search = $sites->random()->name;

        $current = Microsite::query()->search($search)->get();
        $this->assertEquals(1, $current->count());
    }
}
