<?php

namespace Tests\Feature\Roles;

use App\Actions\Roles\UpdateRoleAction;
use App\Enums\Roles\RolePermissions;
use App\Livewire\Roles\EditRole;
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

        $permission = Permission::firstWhere('name', RolePermissions::Update);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_roles_update_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $role = Role::factory()->create();

        $response = $this->get(route('roles.edit', $role));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_roles_update_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $role = Role::factory()->create();

        Livewire::test(EditRole::class, ['role' => $role])
            ->assertSee('Name')
            ->assertSee('Guard name')
            ->assertSee('Microsites Permissions')
            ->assertSee('User Permissions')
            ->assertSee('Role Permissions')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_update_roles(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $now = now()->timestamp;

        $role = Role::factory()->create();
        $permissions = Permission::factory()->count(3)->create();

        $updateData = [
            'name' => "{$role->name} $now",
            'microsite_permissions' => $permissions->pluck('id')->toArray(),
        ];

        Livewire::test(EditRole::class, ['role' => $role])
            ->fillForm($updateData)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('roles', [
            'name' => $updateData['name'],
        ]);

        $role->refresh();

        $this->assertTrue($role->hasAllPermissions($permissions->pluck('name')->toArray()));
    }

    public function test_update_role_action(): void
    {
        $now = now()->timestamp;
        $role = Role::factory()->create();
        $permissions = Permission::factory()->count(3)->create();
        $role->syncPermissions($permissions->pluck('name')->toArray());

        $data = [
            'name' => "{$role->name} $now",
            'microsite_permissions' => $permissions->first()->pluck('id')->toArray(),
            'user_permissions' => [],
            'role_permissions' =>  $permissions->first()->pluck('id')->toArray(),
        ];

        $role = UpdateRoleAction::exec($data, $role);

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);

        $this->assertTrue($role->hasAllPermissions($permissions->first()->pluck('name')->toArray()));
    }
}
