<?php

declare(strict_types=1);

namespace Tests\Feature\Payment;

use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CommandsTest extends TestCase
{
    public function test_clear_expired_test_command(): void
    {
        Queue::fake();

        $this->artisan('payments:clear-expired')
            ->expectsOutput('Expired payments cleared successfully!')
            ->assertExitCode(0);
    }
}
