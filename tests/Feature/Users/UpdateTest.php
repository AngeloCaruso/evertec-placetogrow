<?php

namespace Tests\Feature\Users;

use App\Actions\Users\UpdateUserAction;
use App\Enums\System\AccessRules;
use App\Enums\Users\UserPermissions;
use App\Livewire\Users\EditUser;
use App\Models\AccessControlList;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;
    public $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', UserPermissions::Update);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_admin_user_can_see_users_update_form(): void
    {
        $userToUpdate = User::factory()->create();

        $adminRole = Role::factory()->admin()->create();
        $adminRole->givePermissionTo($this->permission);

        $user = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($user);

        $response = $this->get(route('users.edit', $userToUpdate));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_users_update_form(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);
        $userToUpdate = User::factory()->create();

        AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Allow->value)
            ->controllableType(User::class)
            ->controllableId($userToUpdate->id)
            ->create();

        $response = $this->get(route('users.edit', $userToUpdate));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_users_update_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $user = User::factory()->create();

        Livewire::test(EditUser::class, ['user' => $user])
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Rol')
            ->assertSee('Password')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_update_user(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $now = now()->timestamp;

        $user = User::factory()->create();
        $currentRole = Role::factory()->create();
        $user->syncRoles([$currentRole->name]);

        $newMicrosite = Microsite::factory()->create();

        $newRole = Role::factory()->create();

        $updateData = [
            'name' => "{$user->name} $now",
            'roles' => [$newRole->id],
            'microsite_id' => $newMicrosite->id,
        ];

        Livewire::test(EditUser::class, ['user' => $user])
            ->fillForm($updateData)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => $updateData['name'],
        ]);

        $user->refresh();
        $this->assertTrue($user->hasRole($newRole->name));
        $this->assertNotNull($user->microsite);
    }

    public function test_logged_user_can_submit_and_update_user_with_password(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $now = now()->timestamp;

        $user = User::factory()->create();
        $currentRole = Role::factory()->create();
        $user->syncRoles([$currentRole->name]);

        $newRole = Role::factory()->create();

        $updateData = [
            'name' => "{$user->name} $now",
            'password' => 'new_password',
            'roles' => [$newRole->id],
        ];

        Livewire::test(EditUser::class, ['user' => $user])
            ->fillForm($updateData)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => $updateData['name'],
        ]);

        $user->refresh();

        $this->assertTrue(Hash::check($updateData['password'], $user->password));
        $this->assertTrue($user->hasRole($newRole->name));
    }

    public function test_update_user_action(): void
    {
        $now = now()->timestamp;
        $user = User::factory()->create();
        $currentRole = Role::factory()->create();
        $user->syncRoles([$currentRole->name]);

        $newRole = Role::factory()->create();
        $user->syncRoles([$currentRole->name]);

        $newMicrosite = Microsite::factory()->create();

        $data = [
            'name' => "{$user->name} $now",
            'roles' => [$newRole->id],
            'microsite_id' => $newMicrosite->id,

        ];

        $user = UpdateUserAction::exec($data, $user);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
        ]);

        $this->assertTrue($user->hasRole($newRole->name));
        $this->assertNotNull($user->microsite);
    }
}
