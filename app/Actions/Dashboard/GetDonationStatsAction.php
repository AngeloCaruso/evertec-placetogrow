<?php

namespace App\Actions\Dashboard;

use App\Actions\Payments\GetAllPaymentsWithAclAction;
use App\Enums\Gateways\Status\PlacetopayStatus;
use App\Enums\Microsites\MicrositeType;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GetDonationStatsAction
{
    public static function exec(): array
    {
        $payments = GetAllPaymentsWithAclAction::exec(
            Auth::user(),
            new Payment(),
            ['type' => MicrositeType::Donation]
        )
        ->where('gateway_status', PlacetopayStatus::Approved)
        ->get();

        if ($payments->isEmpty()) {
            return ['sites' => [], 'emptyData' => true];
        }

        $sites = $payments->groupBy('microsite.name')
            ->map(function (Collection $sitePayments) {
                return $sitePayments->sum('amount');
            })
            ->toArray();

        return [
            'sites' => $sites,
            'emptyData' => false,
        ];
    }
}
