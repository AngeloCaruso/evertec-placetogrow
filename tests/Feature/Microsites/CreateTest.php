<?php

namespace Tests\Feature\Microsites;

use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_see_microsites_creation_from(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('microsites.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_create_microsites(): void
    {
        $this->actingAs(User::factory()->create());

        $site = Microsite::factory()->make()->toArray();
        $response = $this->post(route('microsites.store'), $site);

        $response->assertStatus(302);
        $response->assertRedirect(route('microsites.index'));
    }
}
