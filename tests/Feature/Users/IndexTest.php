<?php

namespace Tests\Feature\Users;

use App\Enums\Users\UserPermissions;
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

        $this->testRole = Role::factory()->create();
        $permission = Permission::factory()->create(['name' => UserPermissions::ViewAny]);
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_users(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
    }
}
