<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Actions\Subscriptions\GetAllSubscriptionsWithAclAction;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class GetSubscriptionStatsAction
{
    public static function exec(): array
    {
        $data = [
            'active' => 0,
            'inactive' => 0,
            'emptyData' => false,
            'monthData' => [
                'Jan' => 0,
                'Feb' => 0,
                'Mar' => 0,
                'Apr' => 0,
                'May' => 0,
                'Jun' => 0,
                'Jul' => 0,
                'Aug' => 0,
                'Sep' => 0,
                'Oct' => 0,
                'Nov' => 0,
                'Dec' => 0,
            ],
        ];

        $subscriptions = GetAllSubscriptionsWithAclAction::exec(Auth::user(), new Subscription())->get();

        if ($subscriptions->isEmpty()) {
            $data['emptyData'] = true;
            return $data;
        }

        $subscriptions->each(function ($subscription) use (&$data) {
            if ($subscription->active) {
                $data['active']++;
            } else {
                $data['inactive']++;
            }

            $month = date('M', strtotime($subscription->created_at));
            $data['monthData'][$month]++;
        });

        return $data;
    }
}
