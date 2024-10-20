<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Subscriptions\UpdateSubscriptionDataAction;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSubscriptionStatus implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public Subscription $subscription,
    ) {}

    public function backoff(): array
    {
        return [60, 120];
    }

    public function handle(): void
    {
        if ($this->subscription->status_is_pending) {
            UpdateSubscriptionDataAction::exec($this->subscription);
        }
    }
}
