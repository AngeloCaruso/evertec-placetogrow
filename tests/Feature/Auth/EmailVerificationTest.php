<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
    }

    public function test_email_verification_should_be_redirected(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    }

    public function test_email_redirect_if_email_is_already_verified(): void
    {
        $user = User::factory()->verified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_redirect_if_email_already_verified(): void
    {
        $user = User::factory()->verified()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_sends_verification_email_if_not_verified()
    {
        // Disable notification sending
        Notification::fake();

        // Create a user with an unverified email
        $user = User::factory()->unverified()->create();

        // Act as this user and send a POST request to the route
        $response = $this->actingAs($user)->post(route('verification.send'));

        // Assert the notification was sent
        Notification::assertSentTo(
            [$user],
            \Illuminate\Auth\Notifications\VerifyEmail::class
        );

        // Assert the response redirects back with a status message
        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-link-sent');
    }
}
