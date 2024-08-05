<?php

declare(strict_types=1);

namespace Tests\Feature\Acl;

use App\Actions\AccessControlList\UpdateAclAction;
use App\Enums\Acl\AccessControlListPermissions;
use App\Livewire\AccessControlList\EditAcl;
use App\Models\AccessControlList;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', AccessControlListPermissions::Update);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_access_acl_update_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $user = User::factory()->create();
        $acl = AccessControlList::factory()->user($user)->create();

        $response = $this->get(route('acl.edit', $acl));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_acl_update_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $user = User::factory()->create();
        $acl = AccessControlList::factory()->user($user)->create();

        Livewire::test(EditAcl::class, ['acl' => $acl])
            ->assertSee('User')
            ->assertSee('Rule')
            ->assertSee('Entity Type')
            ->assertSee('Entity')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_update_acl(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $acl = AccessControlList::factory()
            ->controllableId(5)
            ->create();

        Livewire::test(EditAcl::class, ['acl' => $acl])
            ->fillForm($acl->toArray())
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('access_control_lists', [
            'user_id' => $acl->user_id,
            'rule' => $acl->rule,
            'controllable_type' => $acl->controllable_type,
            'controllable_id' => 5,
        ]);
    }

    public function test_update_acl_action(): void
    {
        $user = User::factory()->create();
        $acl = AccessControlList::factory()
            ->user($user)
            ->controllableId(6)
            ->create();

        $acl->controllable_id = 10;

        UpdateAclAction::exec($acl->toArray(), $acl);

        $this->assertDatabaseHas('access_control_lists', [
            'user_id' => $acl->user_id,
            'rule' => $acl->rule,
            'controllable_type' => $acl->controllable_type,
            'controllable_id' => 10,
        ]);
    }
}
