<?php

namespace App\Http\Controllers;

use App\Actions\Subscriptions\GetAllSubscriptionsAction;
use App\Models\Subscription;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Subscription::class);
        $subscriptions = GetAllSubscriptionsAction::exec([], new Subscription());
        return view('livewire.subscriptions.views.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription): View
    {
        Gate::authorize('show', $subscription);
        return view('livewire.subscriptions.views.show', compact('subscription'));
    }
}
