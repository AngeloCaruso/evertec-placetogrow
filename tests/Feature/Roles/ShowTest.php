<?php

namespace Tests\Feature\Roles;

use App\Enums\Roles\RolePermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', RolePermissions::View);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_roles_update_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $role = Role::factory()->create();

        $response = $this->get(route('roles.show', $role));
        $response->assertStatus(200);
    }
}
