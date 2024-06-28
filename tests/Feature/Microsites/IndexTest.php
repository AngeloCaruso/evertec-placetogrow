<?php

namespace Tests\Feature\Microsites;

use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Roles\RolePermissions;
use App\Enums\Users\UserPermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DefaultPermissionsSeeder;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', MicrositePermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_microsites(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('microsites.index'));
        $response->assertStatus(200);
    }
}
