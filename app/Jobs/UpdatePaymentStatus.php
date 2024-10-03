<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Payments\UpdatePaymentStatusAction;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePaymentStatus implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Payment $payment,
    ) {}

    public function backoff(): array
    {
        return [5, 10, 20];
    }

    public function handle(): void
    {
        if ($this->payment->status_is_pending) {
            UpdatePaymentStatusAction::exec($this->payment);
        }
    }
}
