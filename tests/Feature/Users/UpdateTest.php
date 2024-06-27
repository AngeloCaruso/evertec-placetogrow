<?php

namespace Tests\Feature\Users;

use App\Actions\Users\UpdateUserAction;
use App\Enums\System\DefaultRoles;
use App\Livewire\Users\EditUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_see_users_update_form(): void
    {
        $this->actingAs(User::factory()->create());
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_users_update_form_fields(): void
    {
        $this->actingAs(User::factory()->create());
        $user = User::factory()->create();

        Livewire::test(EditUser::class, ['user' => $user])
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Rol')
            ->assertSee('Password')
            ->assertSee('Submit');
    }

    public function test_logged_user_can_submit_and_update_users(): void
    {
        $this->actingAs(User::factory()->create());
        $this->seed();
        $now = now()->timestamp;

        $user = User::factory()->create();
        $user->syncRoles([DefaultRoles::Guest]);

        $updateData = [
            'name' => "{$user->name} $now",
            'roles' => [Role::where('name', DefaultRoles::Admin->value)->first()->id],
        ];

        Livewire::test(EditUser::class, ['user' => $user])
            ->fillForm($updateData)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => $updateData['name'],
        ]);

        $user->refresh();
        $this->assertTrue($user->hasRole(DefaultRoles::Admin));
    }

    public function test_update_user_action(): void
    {
        $this->seed();
        $now = now()->timestamp;
        $user = User::factory()->create();
        $user->syncRoles([DefaultRoles::Guest]);

        $data = [
            'name' => "{$user->name} $now",
            'roles' => [Role::where('name', DefaultRoles::Admin)->first()->id],
        ];

        $user = UpdateUserAction::exec($data, $user);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
        ]);

        $this->assertTrue($user->hasRole(DefaultRoles::Admin));
    }
}
