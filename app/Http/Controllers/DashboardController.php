<?php

namespace App\Http\Controllers;

use App\Actions\Payments\GetAllPaymentsAction;
use App\Enums\Microsites\MicrositeType;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $paidBills = 0;
        $unpaidBills = 0;
        $expiredBills = 0;
        $rejectedBills = 0;

        $payments = GetAllPaymentsAction::exec(['type' => MicrositeType::Billing], new Payment());
        $totalBills = $payments->count();

        $payments->each(function (Payment $payment) use (&$paidBills, &$unpaidBills, &$expiredBills, &$rejectedBills) {
            if ($payment->is_paid) {
                $paidBills++;
            } elseif ($payment->is_rejected) {
                $rejectedBills++;
            } elseif ($payment->is_expired) {
                $expiredBills++;
            } else {
                $unpaidBills++;
            }
        });

        return view('dashboard', compact('paidBills', 'unpaidBills', 'expiredBills', 'rejectedBills', 'totalBills'));
    }
}
