<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_logged_user_can_see_users(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
    }
}
