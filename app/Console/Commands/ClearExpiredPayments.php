<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UpdatePaymentStatus;
use App\Models\Payment;
use Illuminate\Console\Command;

class ClearExpiredPayments extends Command
{
    protected $signature = 'payments:clear-expired';
    protected $description = 'A command to clear expired payments';

    public function handle(): void
    {
        $this->info('Clearing expired payments...');

        $expiredPayments = Payment::where('expires_at', '<', now())->whereNotNull('gateway_status')->get();
        $expiredPayments = $expiredPayments->filter(fn(Payment $payment) => $payment->status_is_pending);

        $expiredPayments->each(fn(Payment $payment) => UpdatePaymentStatus::dispatch($payment));

        $this->info('Expired payments cleared successfully!');
    }
}
