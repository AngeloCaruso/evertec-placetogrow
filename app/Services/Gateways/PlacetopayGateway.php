<?php

declare(strict_types=1);

namespace App\Services\Gateways;

use App\Contracts\PaymentStrategy;
use App\Enums\Gateways\Status\PlacetopayStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlacetopayGateway implements PaymentStrategy
{
    public string $url;
    public string $key;
    public string $secret;
    public string $locale;
    public string $ipAddress;
    public string $userAgent;
    public ?string $expiration = null;
    public ?string $status = null;
    public ?array $subscriptionData = null;
    public ?array $sessionData = null;

    public array $auth = [];
    public array $body = [];
    public array $payment = [];
    public array $subscription = [];

    public ?string $returnUrl = null;
    public ?string $processUrl = null;
    public ?string $requestId = null;

    public function __construct() {}

    public function loadConfig(): self
    {
        $this->url = config('services.placetopay.url');
        $this->key = config('services.placetopay.key');
        $this->secret = config('services.placetopay.secret');
        $this->locale = 'es_CO';
        $this->ipAddress = request()->ip();
        $this->userAgent = request()->header('User-Agent');

        return $this;
    }

    public function loadAuth(): self
    {
        $rawNonce = (string) rand();
        $seed = date('c');
        $tranKey = base64_encode(hash('sha256', $rawNonce . $seed . $this->secret, true));
        $nonce = base64_encode($rawNonce);

        $this->auth = [
            'login' => $this->key,
            'seed' => $seed,
            'nonce' => $nonce,
            'tranKey' => $tranKey,
        ];

        return $this;
    }

    public function loadPayment(array $payment): self
    {
        $this->payment = [
            'reference' => $payment['reference'],
            'description' => $payment['description'],
            'amount' => [
                'currency' => $payment['currency'],
                'total' => $payment['amount'],
            ],
        ];

        $this->expiration = $payment['expires_at'];
        $this->returnUrl = $payment['return_url'];

        return $this;
    }

    public function loadSubscription(array $subscription): self
    {
        $this->subscription = [
            'reference' => $subscription['reference'],
            'description' => $subscription['description'],
        ];

        $this->expiration = $subscription['expires_at'];
        $this->returnUrl = $subscription['return_url'];

        return $this;
    }

    public function prepareBody(): self
    {
        $this->addIfNotEmpty('locale', $this->locale)
            ->addIfNotEmpty('auth', $this->auth)
            ->addIfNotEmpty('payment', $this->payment)
            ->addIfNotEmpty('subscription', $this->subscription)
            ->addIfNotEmpty('expiration', $this->expiration)
            ->addIfNotEmpty('returnUrl', $this->returnUrl)
            ->addIfNotEmpty('ipAddress', $this->ipAddress)
            ->addIfNotEmpty('userAgent', $this->userAgent);

        return $this;
    }

    public function send(): self
    {
        try {
            $response = Http::post("$this->url/api/session", $this->body);
            $data = $response->json();

            if (!$response->ok()) {
                $this->processUrl = null;
            }

            $this->processUrl = $data['processUrl'] ?? null;
            $this->requestId = (string) $data['requestId'] ?? null;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->processUrl = null;
            $this->requestId = null;
        }

        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->processUrl;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function setRequestId(string $requestId): self
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function getStatus(): self
    {
        try {
            $response = Http::post("$this->url/api/session/$this->requestId", $this->body);
            $data = $response->json();

            if (!$response->ok()) {
                $this->status = null;
                return $this;
            }

            $this->status = $data['status']['status'] ?? null;

            if ($this->status === PlacetopayStatus::Rejected && $data['status']['reason'] === 'EX') {
                $this->status = PlacetopayStatus::Expired;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->status = null;
        }

        return $this;
    }

    public function getSubscriptionData(): self
    {
        $this->sendRequest();

        if(!$this->sessionData) {
            return $this;
        }

        $this->subscriptionData = $this->sessionData['subscription'] ?? null;

        if (!$this->subscriptionData) {
            return $this;
        }

        $this->status = $this->subscriptionData['status']['status'];

        return $this;
    }

    private function sendRequest()
    {
        try {
            $response = Http::post("$this->url/api/session/$this->requestId", $this->body);
            $data = $response->json();

            if (!$response->ok()) {
                return $this;
            }

            $this->sessionData = $data;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->sessionData = null;
        }
    }

    private function addIfNotEmpty(string $key, $value): self
    {
        if (!empty($value)) {
            $this->body[$key] = $value;
        }

        return $this;
    }
}
