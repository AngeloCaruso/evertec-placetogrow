<?php

namespace App\Http\Controllers;

use App\Actions\Payments\GetAllPaymentsAction;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = GetAllPaymentsAction::exec([], new Payment());
        return view('livewire.payment.views.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        return view('livewire.payment.views.show', compact('payment'));
    }
}
