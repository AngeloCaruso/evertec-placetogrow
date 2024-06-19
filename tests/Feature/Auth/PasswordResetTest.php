<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/' . $notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });
    }

    public function test_password_reset_link_request_with_invalid_email()
    {
        // Mock the Password facade
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => 'nonexistent@example.com'])
            ->andReturn(Password::INVALID_USER);

        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

        // Assert the response throws a validation exception
        $response->assertSessionHasErrors(['email' => trans(Password::INVALID_USER)]);
    }

    public function test_password_reset_link_request_with_invalid_email_format()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'invalid-email-format',
        ]);

        // Assert the response throws a validation exception for invalid email format
        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_reset_with_invalid_email()
    {
        // Mock the Password facade
        Password::shouldReceive('reset')->andReturn(Password::INVALID_USER);

        $response = $this->post(route('password.store'), [
            'token' => 'valid-token',
            'email' => 'nonexistent@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        // Assert the response throws a validation exception
        $response->assertSessionHasErrors(['email' => trans(Password::INVALID_USER)]);
    }
}
