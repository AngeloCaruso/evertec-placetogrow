<?php

namespace Tests\Feature\Users;

use App\Enums\Users\UserPermissions;
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

        $permission = Permission::firstWhere('name', UserPermissions::View);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_user_details(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $user = User::factory()->create();

        $response = $this->get(route('users.show', $user));
        $response->assertStatus(200);
    }
}
