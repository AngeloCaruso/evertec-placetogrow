<?php

declare(strict_types=1);

namespace Tests\Feature\Roles;

use App\Actions\Roles\StoreRoleAction;
use App\Enums\Roles\RolePermissions;
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

    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', RolePermissions::Create);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_access_roles_creation_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('roles.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_roles_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        Livewire::test(CreateRole::class)
            ->assertSee('Name')
            ->assertSee('Guard name')
            ->assertSee('Microsites Permissions')
            ->assertSee('User Permissions')
            ->assertSee('Role Permissions')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_create_roles(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $newRole = Role::factory()->make();
        $permissions = Permission::factory()->count(3)->create();
        $newRole->microsite_permissions = $permissions->pluck('id')->toArray();

        Livewire::test(CreateRole::class)
            ->fillForm($newRole->toArray())
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('roles', [
            'name' => $newRole['name'],
            'guard_name' => $newRole['guard_name'],
        ]);

        $role = Role::where('name', $newRole['name'])->first();

        $this->assertTrue($role->hasAllPermissions($permissions->pluck('name')->toArray()));
    }

    public function test_create_role_action(): void
    {
        $data = Role::factory()->make();
        $permissions = Permission::factory()->count(3)->create();
        $data->microsite_permissions = $permissions->pluck('id')->toArray();
        $data->user_permissions = [];
        $data->role_permissions = [];
        $data->acl_permissions = [];
        $data->payment_permissions = [];

        $role = StoreRoleAction::exec($data->toArray(), new Role());

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        $role = Role::where('name', $data['name'])->first();

        $this->assertTrue($role->hasAllPermissions($permissions->pluck('name')->toArray()));
    }
}
