<?php

namespace Tests\Feature\Payment;

use App\Models\Payment;
use App\Services\Gateways\PlacetopayGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PlacetopayGatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_gateway_object_is_correctly_built()
    {
        $requestId = 1;
        $processUrl = "https://placetopay.com/session/$requestId";

        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::response([
                'status' => ['status' => 'OK'],
                'requestId' => $requestId,
                'processUrl' => $processUrl
            ], 200)
        ]);

        $payment = Payment::factory()
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->make();

        $gateway = new PlacetopayGateway();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($payment->toArray())
            ->prepareBody()
            ->send();

        $this->assertEquals($gateway->url, config('services.placetopay.url'));
        $this->assertEquals($gateway->key, config('services.placetopay.key'));
        $this->assertEquals($gateway->secret, config('services.placetopay.secret'));
        $this->assertIsArray($gateway->auth);
        $this->assertIsArray($gateway->payment);
        $this->assertIsArray($gateway->body);
        $this->assertIsString($gateway->expiration);
        $this->assertIsString($gateway->returnUrl);
        $this->assertIsString($gateway->processUrl);
        $this->assertIsString($gateway->requestId);

        $this->assertEquals($gateway->getRedirectUrl(), $processUrl);
        $this->assertEquals($gateway->getRequestId(), $requestId);
    }

    public function test_gateway_object_when_request_fails()
    {
        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::response([
                'status' => ['status' => 'FAILED'],
            ], 401)
        ]);

        $payment = Payment::factory()
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->make();

        $gateway = new PlacetopayGateway();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($payment->toArray())
            ->prepareBody()
            ->send();

        $this->assertNull($gateway->processUrl);
        $this->assertNull($gateway->requestId);
    }

    public function test_gateway_object_when_an_exception_is_thrown()
    {
        Http::fake([
            config('services.placetopay.url') . '/api/session' => Http::sequence()
        ]);

        Log::shouldReceive('error')->once();

        $payment = Payment::factory()
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->make();

        $gateway = new PlacetopayGateway();

        $gateway->loadConfig()
            ->loadAuth()
            ->loadPayment($payment->toArray())
            ->prepareBody()
            ->send();

        $this->assertNull($gateway->processUrl);
        $this->assertNull($gateway->requestId);
    }
}
