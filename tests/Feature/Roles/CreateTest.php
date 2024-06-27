<?php

namespace Tests\Feature\Roles;

use App\Actions\Roles\StoreRoleAction;
use App\Livewire\Roles\CreateRole;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_access_roles_creation_form(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('roles.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_roles_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateRole::class)
            ->assertSee('Name')
            ->assertSee('Guard name')
            ->assertSee('Microsites Permissions')
            ->assertSee('Submit');
    }

    public function test_logged_user_can_submit_and_create_roles(): void
    {
        $this->actingAs(User::factory()->create());

        $newRole = Role::factory()->make();
        $permissions = Permission::factory()->count(3)->create();
        $newRole->microsite_permissions = $permissions->pluck('id')->toArray();

        Livewire::test(CreateRole::class)
            ->fillForm($newRole->toArray())
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('roles', [
            'name' => $newRole['name'],
            'guard_name' => $newRole['guard_name']
        ]);

        $role = Role::where('name', $newRole['name'])->first();

        $this->assertTrue($role->hasAllPermissions($permissions->pluck('name')->toArray()));
    }

    public function test_create_role_action(): void
    {
        $data = Role::factory()->make();
        $permissions = Permission::factory()->count(3)->create();
        $data->microsite_permissions = $permissions->pluck('id')->toArray();

        $role = StoreRoleAction::exec($data->toArray(), new Role());

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
            'guard_name' => $data['guard_name']
        ]);

        $role = Role::where('name', $data['name'])->first();

        $this->assertTrue($role->hasAllPermissions($permissions->pluck('name')->toArray()));
    }
}
