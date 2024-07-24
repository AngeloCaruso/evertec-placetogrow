<?php

namespace Tests\Feature\Microsites;

use App\Enums\Microsites\MicrositePermissions;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', MicrositePermissions::View);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_has_permission_to_see_microsites(): void
    {
        $site = Microsite::factory()->create();
        $user = User::factory()->create()->assignRole($this->testRole);
        $user->microsite()
            ->associate($site)
            ->save();

        $this->actingAs($user);

        $response = $this->get(route('microsites.show', $site->id));
        $response->assertStatus(200);
    }

    public function test_logged_user_cant_see_non_assigned_site(): void
    {
        $site = Microsite::factory()->create();
        $user = User::factory()->create()->assignRole($this->testRole);

        $this->actingAs($user);

        $response = $this->get(route('microsites.show', $site->id));
        $response->assertStatus(403);
    }
}
