<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use App\Enums\System\AccessRules;
use App\Enums\Users\UserPermissions;
use App\Models\AccessControlList;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', UserPermissions::View);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_admin_user_can_see_user_details(): void
    {
        $userToSee = User::factory()->create();

        $adminRole = Role::factory()->admin()->create();
        $adminRole->givePermissionTo($this->permission);

        $user = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($user);

        $response = $this->get(route('users.show', $userToSee));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_user_details(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);
        $userToSee = User::factory()->create();

        AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Allow->value)
            ->controllableType(User::class)
            ->controllableId($userToSee->id)
            ->create();


        $response = $this->get(route('users.show', $userToSee));
        $response->assertStatus(200);
    }
}
