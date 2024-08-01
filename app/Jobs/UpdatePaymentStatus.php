<?php

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

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Payment $payment
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UpdatePaymentStatusAction::exec($this->payment);
    }
}
