<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentStrategy;
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
    public ?string $status = null;
    public ?string $expiration = null;

    public array $auth = [];
    public array $body = [];
    public array $payment = [];

    public ?string $returnUrl = null;
    public ?string $processUrl = null;
    public ?string $requestId = null;

    public function __construct()
    {
    }

    public function loadConfig()
    {
        $this->url = config('services.placetopay.url');
        $this->key = config('services.placetopay.key');
        $this->secret = config('services.placetopay.secret');
        $this->locale = 'es_CO';
        $this->ipAddress = request()->ip();
        $this->userAgent = request()->header('User-Agent');

        return $this;
    }

    public function loadAuth()
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

    public function loadPayment(array $payment)
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

    public function prepareBody()
    {
        $this->addIfNotEmpty('locale', $this->locale)
            ->addIfNotEmpty('auth', $this->auth)
            ->addIfNotEmpty('payment', $this->payment)
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
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->processUrl = null;
            $this->requestId = null;
        }

        $this->processUrl = $data['processUrl'] ?? null;
        $this->requestId = $data['requestId'] ?? null;

        return $this;
    }

    public function getRedirectUrl()
    {
        return $this->processUrl;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId(string $requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function getStatus()
    {
        try {
            $response = Http::post("$this->url/api/session/$this->requestId", $this->body);
            $data = $response->json();

            if (!$response->ok()) {
                $this->status = null;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->status = null;
        }

        $this->status = $data['status']['status'] ?? null;

        return $this;
    }

    private function addIfNotEmpty(string $key, $value): self
    {
        if (!empty($value)) {
            $this->body[$key] = $value;
        }

        return $this;
    }
}
