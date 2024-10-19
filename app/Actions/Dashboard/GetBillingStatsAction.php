<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Actions\Payments\GetAllPaymentsWithAclAction;
use App\Enums\Microsites\MicrositeType;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class GetBillingStatsAction
{
    public static function exec(): array
    {
        $data = [
            'paidBills' => 0,
            'unpaidBills' => 0,
            'expiredBills' => 0,
            'rejectedBills' => 0,
            'totalBills' => 0,
            'emptyData' => false,
        ];

        $payments = GetAllPaymentsWithAclAction::exec(Auth::user(), new Payment(), ['type' => MicrositeType::Billing])->get();

        if ($payments->isEmpty()) {
            $data['emptyData'] = true;
            return $data;
        }

        $data['totalBills'] = $payments->count();

        $payments->each(function (Payment $payment) use (&$data) {
            if ($payment->is_paid) {
                $data['paidBills']++;
            } elseif ($payment->is_rejected) {
                $data['rejectedBills']++;
            } elseif ($payment->is_expired) {
                $data['expiredBills']++;
            } else {
                $data['unpaidBills']++;
            }
        });

        return $data;
    }
}
