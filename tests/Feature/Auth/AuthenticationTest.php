<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\View\Components\GuestLayout;
use Database\Seeders\DefaultPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\View\View;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.login');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_navigation_menu_can_be_rendered(): void
    {
        $this->seed(DefaultPermissionsSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertSeeVolt('layout.sidebar-navigation');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('layout.navigation');

        $component->call('logout');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_guest_layout_renders_the_correct_view()
    {
        $component = new GuestLayout();

        $view = $component->render();

        $this->assertInstanceOf(View::class, $view);

        $this->assertEquals('layouts.guest', $view->name());
    }
}
