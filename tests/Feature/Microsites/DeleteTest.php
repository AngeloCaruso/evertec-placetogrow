<?php

namespace Tests\Feature\Microsites;

use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_delete_microsite(): void
    {
        $this->actingAs(User::factory()->create());
        $site = Microsite::factory()->create();

        $response = $this->delete(route('microsites.destroy', $site->id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('microsites', [
            'id' => $site->id,
        ]);
    }
}
