<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Jobs\UpdatePaymentStatus;
use App\Models\Payment;
use Illuminate\Console\Command;

class UpdatePendingPayments extends Command
{
    protected $signature = 'payments:update-pending';
    protected $description = 'A command to update pending payments';

    public function handle(): void
    {
        $expiredPayments = Payment::where('gateway_status', PlacetopayStatus::Pending)->get();
        $expiredPayments->each(fn(Payment $payment) => UpdatePaymentStatus::dispatch($payment));
    }
}
