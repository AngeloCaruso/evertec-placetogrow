<?php

declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Actions\Payments\UpdatePaymentStatusAction;
use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Enums\Notifications\EmailBody;
use App\Enums\System\SystemQueues;
use App\Events\PaymentCollected;
use App\Jobs\UpdatePaymentStatus;
use App\Listeners\SendPaymentCollectNotification;
use App\Models\Payment;
use App\Notifications\PaymentCollectNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        Queue::fake();
        Event::fake([PaymentCollected::class]);
    }

    public function test_update_placetopay_payment_action(): void
    {
        $requestId = 1;
        Http::fake([
            config('services.placetopay.url') . "/api/session/$requestId" => Http::response([
                'status' => ['status' => 'APPROVED'],
            ], 200),
        ]);

        $payment = Payment::factory()
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId($requestId)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        UpdatePaymentStatusAction::exec($payment);
        Event::assertDispatched(PaymentCollected::class);

        $payment->refresh();

        $this->assertEquals('APPROVED', $payment->gateway_status);
    }

    public function test_payment_is_not_updated_if_not_in_pending_status(): void
    {
        $payment = Payment::factory()
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        UpdatePaymentStatusAction::exec($payment);

        $payment->refresh();

        $this->assertEquals('APPROVED', $payment->gateway_status);
    }

    public function test_payment_update_throws_exception(): void
    {
        $requestId = 1;
        Http::fake([
            config('services.placetopay.url') . "/api/session/$requestId" => Http::sequence(),
        ]);

        Log::shouldReceive('error')->once();

        $payment = Payment::factory()
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId($requestId)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        UpdatePaymentStatusAction::exec($payment);

        $payment->refresh();

        $this->assertEquals(PlacetopayStatus::Pending, $payment->status);
    }

    public function test_status_is_updated_with_the_queue(): void
    {
        Http::fake([
            config('services.placetopay.url') . "/api/session/1" => Http::response([
                'status' => ['status' => 'APPROVED'],
            ], 200),
        ]);

        $payment = Payment::factory()
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        Queue::fake();

        UpdatePaymentStatus::dispatch($payment)->onQueue('payments');

        Queue::assertPushedOn('payments', UpdatePaymentStatus::class);
    }

    public function test_send_payment_collect_notification_listener(): void
    {
        $payment = Payment::factory()
            ->withPlacetopayGateway()
            ->approved()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $listener = new SendPaymentCollectNotification();
        $listener->handle(new PaymentCollected($payment, EmailBody::CollectAlert));

        Notification::assertSentOnDemand(PaymentCollectNotification::class);
    }
}
