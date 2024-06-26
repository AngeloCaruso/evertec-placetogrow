<?php

namespace Tests\Feature\Users;

use App\Actions\Users\StoreUserAction;
use App\Enums\System\DefaultRoles;
use App\Livewire\Users\CreateUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_access_users_creation_form(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('users.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_users_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateUser::class)
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Rol')
            ->assertSee('Password')
            ->assertSee('Submit');
    }

    public function test_logged_user_can_submit_and_create_users(): void
    {
        $this->actingAs(User::factory()->create());
        $this->seed();

        $newUser = User::factory()
            ->withRoles([DefaultRoles::Guest])
            ->make()
            ->toArray();
        $newUser['password'] = 'test1234';

        Livewire::test(CreateUser::class)
            ->fillForm($newUser)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => $newUser['name'],
            'email' => $newUser['email']
        ]);

        $user = User::where('email', $newUser['email'])->first();

        $this->assertTrue($user->hasRole(DefaultRoles::Guest));
    }

    public function test_create_user_action(): void
    {
        $this->seed();

        $data = User::factory()->withRoles([DefaultRoles::Guest])->make()->toArray();
        $data['password'] = 'test1234';
        $user = StoreUserAction::exec($data, new User());

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        $this->assertTrue($user->hasRole(DefaultRoles::Guest));
    }
}
