<?php

namespace App\Http\Controllers\Public;

use App\Actions\Subscriptions\ProcessSubscriptionAction;
use App\Actions\Subscriptions\StoreSubscriptionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Resources\MicrositeResource;
use App\Http\Resources\SubscriptionResource;
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
            ->onQueue('subscriptions');

        return Inertia::render('Subscription/Info', [
            'subscription' => new SubscriptionResource($reference),
            'site' => new MicrositeResource($reference->microsite)
        ]);
    }
    public function store(StoreSubscriptionRequest $request): HttpFoundationResponse
    {
        $subscription = StoreSubscriptionAction::exec($request->validated(), new Subscription());
        $subscription = ProcessSubscriptionAction::exec($subscription);

        if (is_null($subscription->payment_url)) {
            return to_route('public.microsite.index')->with('error', 'Error processing subscription');
        }

        return Inertia::location($subscription->payment_url);
    }
}
