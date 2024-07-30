<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentStrategy;

class PaypalGateway implements PaymentStrategy
{
    public function loadConfig()
    {
        // Load the configuration
    }

    public function loadAuth()
    {
        // Load the authentication
    }

    public function loadPayment(array $payment)
    {
        // Load the payment
    }

    public function prepareBody()
    {
        // Prepare the body
    }

    public function send()
    {
        // Send the request
    }

    public function getRedirectUrl()
    {
        // Get the redirect URL
    }

    public function getStatus()
    {
        // Get the status
    }
}
