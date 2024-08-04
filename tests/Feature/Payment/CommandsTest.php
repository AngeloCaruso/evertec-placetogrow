<?php

namespace Tests\Feature\Payment;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CommandsTest extends TestCase
{
    public function test_clear_expired_test_command()
    {
        Queue::fake();

        $this->artisan('payments:clear-expired')
            ->expectsOutput('Expired payments cleared successfully!')
            ->assertExitCode(0);
    }
}
