<?php

namespace Tests\Feature\Acl;

use App\Actions\AccessControlList\StoreAclAction;
use App\Enums\Acl\AccessControlListPermissions;
use App\Livewire\AccessControlList\CreateAcl;
use App\Models\AccessControlList;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', AccessControlListPermissions::Create);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_access_acl_creation_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('acl.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_acl_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        Livewire::test(CreateAcl::class)
            ->assertSee('User')
            ->assertSee('Rule')
            ->assertSee('Entity Type')
            ->assertSee('Entity')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_create_acl(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $ids = [1, 2, 3];
        $acl = AccessControlList::factory()
            ->controllableIds($ids)
            ->make();

        Livewire::test(CreateAcl::class)
            ->fillForm($acl->toArray())
            ->call('create')
            ->assertHasNoFormErrors();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('access_control_lists', [
                'user_id' => $acl->user_id,
                'rule' => $acl->rule,
                'controllable_type' => $acl->controllable_type,
                'controllable_id' => $id,
            ]);
        }
    }

    public function test_create_acl_action()
    {
        $user = User::factory()->create();
        $ids = [1, 2, 3];
        $acl = AccessControlList::factory()
            ->user($user)
            ->controllableIds($ids)
            ->make();

        StoreAclAction::exec($acl->toArray(), new AccessControlList());

        foreach ($ids as $id) {
            $this->assertDatabaseHas('access_control_lists', [
                'user_id' => $acl->user_id,
                'rule' => $acl->rule,
                'controllable_type' => $acl->controllable_type,
                'controllable_id' => $id,
            ]);
        }
    }
}
