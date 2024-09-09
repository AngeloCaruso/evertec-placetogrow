<?php

namespace Tests\Feature\Import;

use App\Enums\Imports\ImportPermissions;
use App\Models\DataImport;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', ImportPermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_logged_user_can_see_imports_index(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        DataImport::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('data-imports.index'));
        $response->assertStatus(200);
    }
}
