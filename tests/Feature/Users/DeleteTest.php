<?php

namespace Tests\Feature\Users;

use App\Actions\Users\DestroyUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_delete_users(): void
    {
        $this->actingAs(User::factory()->create());
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
