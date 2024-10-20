<?php

declare(strict_types=1);

namespace Tests\Feature\Acl;

use App\Enums\Acl\AccessControlListPermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public Role $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', AccessControlListPermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_users(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('acl.index'));
        $response->assertStatus(200);
    }
}
