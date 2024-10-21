<?php

declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Enums\Microsites\MicrositeType;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Notifications\PaymentExpiredReportNotification;
use App\Notifications\PaymentExpireTomorrowReportNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CommandsTest extends TestCase
{
    public function setup(): void
    {
        parent::setUp();

        Queue::fake();
        Notification::fake();
    }

    public function test_update_pending_test_command(): void
    {
        $this->artisan('payments:update-pending')
            ->assertExitCode(0);
    }

    public function test_expire_tomorrow_report_command(): void
    {
        $user = User::factory()->create()->assignRole(Role::factory()->admin()->create());
        $user2 = User::factory()->create();

        $microsite = Microsite::factory()->type(MicrositeType::Billing)->create();

        Payment::factory()
            ->fakeReference()
            ->create(['limit_date' => now()->addDay()->format('Y-m-d'), 'microsite_id' => $microsite->id]);

        $this->artisan('payments:expire-tomorrow-report')
            ->assertExitCode(0);

        Notification::assertSentTo(
            $user,
            PaymentExpireTomorrowReportNotification::class
        );
    }

    public function test_expired_payments_report(): void
    {
        $user = User::factory()->create()->assignRole(Role::factory()->admin()->create());
        $user2 = User::factory()->create();

        $microsite = Microsite::factory()->type(MicrositeType::Billing)->create();

        Payment::factory()
            ->fakeReference()
            ->create(['limit_date' => now()->subDay()->format('Y-m-d'), 'microsite_id' => $microsite->id]);

        $this->artisan('payments:expired-report')
            ->assertExitCode(0);

        Notification::assertSentTo(
            $user,
            PaymentExpiredReportNotification::class
        );
    }
}
