<?php

namespace Tests\Feature\Users;

use App\Actions\AccessControlList\StoreAclAction;
use App\Enums\System\AccessRules;
use App\Enums\Users\UserPermissions;
use App\Livewire\Users\ListUsers;
use App\Models\AccessControlList;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', UserPermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_logged_user_can_see_users(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_users_via_acl(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);
        $users = User::factory()->count(5)->create();
        $usersDenied = User::factory()->count(5)->create();

        $acl = AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Allow->value)
            ->controllableType(User::class)
            ->controllableIds($users->pluck('id')->toArray())
            ->make();

        $deniedAcl = AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Deny->value)
            ->controllableType(User::class)
            ->controllableIds($usersDenied->pluck('id')->toArray())
            ->make();

        StoreAclAction::exec($acl->toArray(), new AccessControlList());
        StoreAclAction::exec($deniedAcl->toArray(), new AccessControlList());

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords($users)
            ->assertCanNotSeeTableRecords($usersDenied);
    }

    public function test_admin_user_can_see_all_microsites(): void
    {
        $adminRole = Role::factory()->admin()->create();
        $adminRole->givePermissionTo($this->permission);

        $this->actingAs(User::factory()->create()->assignRole($adminRole));

        $users = User::factory()->count(5)->create();

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords($users);
    }
}
