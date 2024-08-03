<?php

namespace Tests\Feature\Payment;

use App\Actions\Payments\UpdatePaymentStatusAction;
use App\Jobs\UpdatePaymentStatus;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_update_placetopay_payment_action()
    {
        $requestId = 1;
        Http::fake([
            config('services.placetopay.url') . "/api/session/$requestId" => Http::response([
                'status' => ['status' => 'APPROVED'],
            ], 200)
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

        $payment->refresh();

        $this->assertEquals('APPROVED', $payment->gateway_status);
    }

    public function test_payment_is_not_updated_if_not_in_pending_status()
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

    public function test_payment_update_throws_exception()
    {
        $requestId = 1;
        Http::fake([
            config('services.placetopay.url') . "/api/session/$requestId" => Http::sequence()
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

        $this->assertNull($payment->gateway_status);
    }

    public function test_status_is_updated_with_the_queue()
    {
        Http::fake([
            config('services.placetopay.url') . "/api/session/1" => Http::response([
                'status' => ['status' => 'APPROVED'],
            ], 200)
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
}
