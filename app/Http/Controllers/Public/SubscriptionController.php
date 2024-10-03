<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Subscriptions\ProcessSubscriptionAction;
use App\Actions\Subscriptions\StoreSubscriptionAction;
use App\Enums\Microsites\SubscriptionCollectType;
use App\Enums\System\SystemQueues;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Resources\MicrositeResource;
use App\Http\Resources\SubscriptionResource;
use App\Jobs\RunSubscriptionCollect;
use App\Jobs\UpdateSubscriptionStatus;
use App\Models\Subscription;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class SubscriptionController extends Controller
{
    public function show(Subscription $reference): Response
    {
        UpdateSubscriptionStatus::dispatchIf($reference->status_is_pending, $reference)
            ->onQueue(SystemQueues::Subscriptions->value);

        return Inertia::render('Subscription/Info', [
            'subscription' => new SubscriptionResource($reference),
            'site' => new MicrositeResource($reference->microsite),
        ]);
    }
    public function store(StoreSubscriptionRequest $request): HttpFoundationResponse
    {
        $subscription = StoreSubscriptionAction::exec($request->validated(), new Subscription());
        $subscription = ProcessSubscriptionAction::exec($subscription);

        RunSubscriptionCollect::dispatchIf($subscription->microsite->charge_collect === SubscriptionCollectType::PayLater, $subscription)
            ->onQueue(SystemQueues::Subscriptions->value)
            ->delay($subscription->is_paid_monthly ? now()->addMonth() : now()->addYear());

        if (is_null($subscription->payment_url)) {
            return to_route('public.microsite.index')->with('error', 'Error processing subscription');
        }

        return Inertia::location($subscription->payment_url);
    }
}
