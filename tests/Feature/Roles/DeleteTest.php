<?php

declare(strict_types=1);

namespace Tests\Feature\Roles;

use App\Actions\Roles\DestroyRoleAction;
use App\Enums\Roles\RolePermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public Role $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', RolePermissions::Delete);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_delete_roles(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $role = Role::factory()->create();

        $response = $this->delete(route('roles.destroy', $role->id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    public function test_delete_role_action(): void
    {
        $role = Role::factory()->create();
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
        ]);

        DestroyRoleAction::exec([], $role);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }
}
