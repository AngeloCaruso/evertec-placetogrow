<?php

namespace Tests\Feature\Microsites;

use App\Enums\Microsites\MicrositePermissions;
use App\Enums\System\AccessRules;
use App\Models\AccessControlList;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', MicrositePermissions::View);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_admin_user_has_permission_to_see_microsites(): void
    {
        $adminRole = Role::factory()->admin()->create();
        $adminRole->givePermissionTo($this->permission);

        $site = Microsite::factory()->create();
        $user = User::factory()->create()->assignRole($adminRole);

        $this->actingAs($user);

        $response = $this->get(route('microsites.show', $site));
        $response->assertStatus(200);
    }

    public function test_logged_user_has_permission_to_see_microsites(): void
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

        $response = $this->get(route('microsites.show', $site));
        $response->assertStatus(200);
    }

    public function test_logged_user_cant_see_non_assigned_site(): void
    {
        $site = Microsite::factory()->create();
        $user = User::factory()->create()->assignRole($this->testRole);

        $this->actingAs($user);

        $response = $this->get(route('microsites.show', $site));
        $response->assertStatus(403);
    }
}
