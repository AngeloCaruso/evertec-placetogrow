<?php

namespace App\Http\Controllers;

use App\Actions\Payments\GetAllPaymentsAction;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Payment::class);
        $payments = GetAllPaymentsAction::exec([], new Payment());
        return view('livewire.payment.views.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        Gate::authorize('show', $payment);
        return view('livewire.payment.views.show', compact('payment'));
    }
}
