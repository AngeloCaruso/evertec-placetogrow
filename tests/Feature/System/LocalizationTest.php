<?php

declare(strict_types=1);

namespace Tests\Feature\System;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    public function test_logged_user_can_change_locale(): void
    {
        $this->actingAs(User::factory()->create());
        Session::put('locale', 'es');

        $response = $this->get(route('locale', 'en'));
        $response->assertStatus(302);

        $this->assertTrue(Session::get('locale') === 'en');
    }
}
