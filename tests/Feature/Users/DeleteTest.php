<?php

namespace Tests\Feature\Users;

use App\Actions\Users\DestroyUserAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Roles\RolePermissions;
use App\Enums\Users\UserPermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', UserPermissions::Delete);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_delete_users(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user->id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_delete_user_action()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        DestroyUserAction::exec([], $user);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
