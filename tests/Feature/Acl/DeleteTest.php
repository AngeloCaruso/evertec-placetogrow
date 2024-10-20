<?php

declare(strict_types=1);

namespace Tests\Feature\Acl;

use App\Actions\AccessControlList\DestroyAclAction;
use App\Enums\Acl\AccessControlListPermissions;
use App\Models\AccessControlList;
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

        $permission = Permission::firstWhere('name', AccessControlListPermissions::Delete);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_delete_user_action(): void
    {
        $user = User::factory()->create();

        $acl = AccessControlList::factory()
            ->user($user)
            ->create();

        DestroyAclAction::exec([], $acl);

        $this->assertDatabaseMissing('access_control_lists', [
            'id' => $acl->id,
        ]);
    }
}
