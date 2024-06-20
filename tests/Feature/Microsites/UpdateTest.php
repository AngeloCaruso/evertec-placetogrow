<?php

namespace Tests\Feature\Microsites;

use App\Models\Microsite;
use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_logged_user_can_see_microsites_update_form(): void
    {
        $this->actingAs(User::factory()->create());
        $site = Microsite::factory()->create();

        $response = $this->get(route('microsites.edit', $site));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_update_microsites(): void
    {
        $this->actingAs(User::factory()->create());
        $site = Microsite::factory()->create();
        $now = now();
        $response = $this->patch(route('microsites.update', $site), [
            'name' => "Updated Name $now->timestamp",
            'category' => "updated-category $now->timestamp",
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('microsites.index'));

        $this->assertDatabaseHas('microsites', [
            'name' => "Updated Name $now->timestamp",
            'category' => "updated-category $now->timestamp",
        ]);
    }
}
