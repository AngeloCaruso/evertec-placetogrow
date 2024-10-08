<?php

declare(strict_types=1);

namespace App\Contracts;

interface PaymentStrategy
{
    public function loadConfig();
    public function loadAuth();
    public function loadPayment(array $payment);
    public function prepareBody();
    public function send();
    public function getStatus();
}
