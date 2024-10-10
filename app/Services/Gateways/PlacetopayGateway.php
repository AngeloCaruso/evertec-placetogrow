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
    public array $payer = [];
    public array $instrument = [];

    public ?string $returnUrl = null;
    public ?string $processUrl = null;
    public ?string $requestId = null;

    public function __construct() {}

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

    public function loadPayer(array $payer): self
    {
        $this->payer = [
            'document' => $payer['document'],
            'documentType' => strtoupper($payer['document_type']),
            'name' => $payer['name'],
            'surname' => $payer['surname'],
            'email' => $payer['email'],
        ];

        return $this;
    }

    public function loadInstrument(string $token): self
    {
        $this->instrument = [
            'token' => [
                'token' => $token,
            ],
        ];

        return $this;
    }

    public function prepareBody(): self
    {
        $this->addIfNotEmpty('locale', $this->locale)
            ->addIfNotEmpty('auth', $this->auth)
            ->addIfNotEmpty('payment', $this->payment)
            ->addIfNotEmpty('subscription', $this->subscription)
            ->addIfNotEmpty('payer', $this->payer)
            ->addIfNotEmpty('instrument', $this->instrument)
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
                Log::error($data);
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
        $this->sendRequest("api/session/$this->requestId");

        if (!$this->sessionData) {
            return $this;
        }

        $this->subscriptionData = $this->sessionData['subscription'] ?? null;

        if (!$this->subscriptionData) {
            return $this;
        }

        $this->status = $this->subscriptionData['status']['status'];

        return $this;
    }

    public function sendCollectPayment(): self
    {
        $this->sendRequest("api/collect");

        if (
            !$this->sessionData ||
            !isset($this->sessionData['status']['status']) ||
            !isset($this->sessionData['requestId'])
        ) {
            return $this;
        }

        $this->status = $this->sessionData['status']['status'];
        $this->requestId = (string) $this->sessionData['requestId'];

        return $this;
    }

    public function sendInvalidateToken(): self
    {
        $this->sendRequest("api/instrument/invalidate");

        if (!$this->sessionData) {
            return $this;
        }

        $this->status = $this->sessionData['status']['status'];

        return $this;
    }

    private function sendRequest(string $path): void
    {
        try {
            $response = Http::post("$this->url/$path", $this->body);
            $data = $response->json();

            $this->sessionData = $data;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->sessionData = null;
        }
    }

    private function addIfNotEmpty(string $key, string | array | null $value): self
    {
        if (!empty($value)) {
            $this->body[$key] = $value;
        }

        return $this;
    }
}
