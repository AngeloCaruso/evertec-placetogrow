<?php

declare(strict_types=1);

namespace App\Contracts;

interface PaymentStrategy
{
    public function loadConfig(): self;
    public function loadAuth(): self;
    public function loadPayment(array $payment): self;
    public function prepareBody(): self;
    public function send(): self;
    public function getStatus(): self;
}
