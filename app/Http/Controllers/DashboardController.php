<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetBillingStatsAction;
use App\Actions\Dashboard\GetDonationStatsAction;
use App\Actions\Dashboard\GetSubscriptionStatsAction;
use App\Models\Microsite;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        Gate::authorize('dashboard', Microsite::class);

        $billingStats = GetBillingStatsAction::exec();
        $subscriptionStats = GetSubscriptionStatsAction::exec();
        $donationStats = GetDonationStatsAction::exec();

        return view('dashboard', compact('billingStats', 'subscriptionStats', 'donationStats'));
    }
}
