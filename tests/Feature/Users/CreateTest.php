<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use App\Actions\Users\StoreUserAction;
use App\Enums\Users\UserPermissions;
use App\Livewire\Users\CreateUser;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public Role $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', UserPermissions::Create);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_access_users_creation_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('users.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_users_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        Livewire::test(CreateUser::class)
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Rol')
            ->assertSee('Password')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_create_users(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $newRole = Role::factory()->create();
        $newMicrosite = Microsite::factory()->create();

        $newUser = User::factory()
            ->make(['roles' => [$newRole->id]])
            ->toArray();
        $newUser['password'] = 'test1234';
        $newUser['microsite_id'] = $newMicrosite->id;

        Livewire::test(CreateUser::class)
            ->fillForm($newUser)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => $newUser['name'],
            'email' => $newUser['email'],
        ]);

        $user = User::where('email', $newUser['email'])->first();

        $this->assertTrue($user->hasRole($newRole->name));
        $this->assertNotNull($user->microsite);
    }

    public function test_create_user_action(): void
    {
        $newRole = Role::factory()->create();
        $newMicrosite = Microsite::factory()->create();

        $data = User::factory()
            ->make(['roles' => $newRole->id])
            ->toArray();
        $data['password'] = 'test1234';
        $data['microsite_id'] = $newMicrosite->id;

        $user = StoreUserAction::exec($data, new User());

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $this->assertTrue($user->hasRole($newRole->name));
        $this->assertNotNull($user->microsite);
    }
}
