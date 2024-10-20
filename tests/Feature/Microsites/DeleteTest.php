<?php

declare(strict_types=1);

namespace Tests\Feature\Microsites;

use App\Actions\Microsites\DestroyMicrositeAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\System\AccessRules;
use App\Models\AccessControlList;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public Role $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', MicrositePermissions::Delete);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_delete_microsite(): void
    {
        $site = Microsite::factory()->create();
        $user = User::factory()->create()->assignRole($this->testRole);

        AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Allow->value)
            ->controllableType(Microsite::class)
            ->controllableId($site->id)
            ->create();

        $this->actingAs($user);

        $response = $this->delete(route('microsites.destroy', $site));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('microsites', [
            'id' => $site->id,
        ]);
    }

    public function test_destroy_microsite_action(): void
    {
        $site = Microsite::factory()->create();

        $this->assertDatabaseHas('microsites', [
            'slug' => $site->slug,
        ]);

        DestroyMicrositeAction::exec([], $site);

        $this->assertDatabaseMissing('microsites', [
            'slug' => $site->slug,
        ]);
    }

    public function test_destroy_action_does_not_destroy_users(): void
    {
        $site = Microsite::factory()->create();
        $user = User::factory()->create();
        $user->microsite()->associate($site);

        $this->assertDatabaseHas('microsites', [
            'slug' => $site->slug,
        ]);

        DestroyMicrositeAction::exec([], $site);

        $this->assertDatabaseMissing('microsites', [
            'slug' => $site->slug,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $user->refresh();

        $this->assertNull($user->microsite_id);
    }
}
