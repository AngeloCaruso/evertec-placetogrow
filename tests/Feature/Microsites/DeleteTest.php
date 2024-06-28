<?php

namespace Tests\Feature\Microsites;

use App\Actions\Microsites\DestroyMicrositeAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Models\Microsite;
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

        $permission = Permission::firstWhere('name', MicrositePermissions::Delete);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_delete_microsite(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $site = Microsite::factory()->create();

        $response = $this->delete(route('microsites.destroy', $site->id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('microsites', [
            'id' => $site->id,
        ]);
    }

    public function test_destroy_microsite_action(): void
    {
        $site = Microsite::factory()->create();

        $this->assertDatabaseHas('microsites', [
            'id' => $site->id,
        ]);

        DestroyMicrositeAction::exec([], $site);

        $this->assertDatabaseMissing('microsites', [
            'id' => $site->id,
        ]);
    }

    public function test_destroy_action_does_not_destroy_users(): void {
        $site = Microsite::factory()->create();
        $user = User::factory()->create();
        $user->microsite()->associate($site);

        $this->assertDatabaseHas('microsites', [
            'id' => $site->id,
        ]);

        DestroyMicrositeAction::exec([], $site);

        $this->assertDatabaseMissing('microsites', [
            'id' => $site->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $user->refresh();

        $this->assertNull($user->microsite_id);
    }
}
