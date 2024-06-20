<?php

namespace Tests\Feature\Microsites;

use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_logged_user_can_see_microsites(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/microsites');
        $response->assertStatus(200);
    }
}
