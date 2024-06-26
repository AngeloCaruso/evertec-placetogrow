<?php

namespace Tests\Feature\Roles;

use App\Actions\Roles\DestroyRoleAction;
use App\Actions\Users\DestroyUserAction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_delete_roles(): void
    {
        $this->actingAs(User::factory()->create());
        $role = Role::factory()->create();

        $response = $this->delete(route('roles.destroy', $role->id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    public function test_delete_role_action()
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
