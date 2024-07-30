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
    public array $auth;
    public array $body;
    public array $payment;
    public string $expiration;
    public string | null $returnUrl;
    public string | null $processUrl;
    public string | null $requestId;
    public string $status;

    public function __construct()
    {
    }

    public function loadConfig()
    {
        $this->url = config('services.placetopay.url');
        $this->key = config('services.placetopay.key');
        $this->secret = config('services.placetopay.secret');

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
        $this->body = [
            'locale' => 'es_CO',
            'auth' => $this->auth,
            'payment' => $this->payment,
            'expiration' => $this->expiration,
            'returnUrl' => $this->returnUrl,
            'ipAddress' => request()->ip(),
            'userAgent' => request()->header('User-Agent'),
        ];

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

    public function getStatus()
    {
        $this->status = 'Getting status from Placetopay';
        return $this;
    }
}
