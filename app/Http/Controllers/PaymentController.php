<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payments\GetAllPaymentsAction;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Payment::class);
        $payments = GetAllPaymentsAction::exec([], new Payment());
        return view('livewire.payment.views.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        Gate::authorize('show', $payment);
        return view('livewire.payment.views.show', compact('payment'));
    }
}
