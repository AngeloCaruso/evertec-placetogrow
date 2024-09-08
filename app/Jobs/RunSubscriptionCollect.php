<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Subscriptions\ProcessCollectAction;
use App\Enums\System\SystemQueues;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunSubscriptionCollect implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function backoff(): array
    {
        return [5, 10, 20];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->subscription->status_is_approved) {
            ProcessCollectAction::exec($this->subscription);
        }
    }
}
