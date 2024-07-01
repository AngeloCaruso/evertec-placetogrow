<?php

namespace Tests\Feature\Roles;

use App\Enums\Roles\RolePermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', RolePermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_roles(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('roles.index'));
        $response->assertStatus(200);
    }
}
