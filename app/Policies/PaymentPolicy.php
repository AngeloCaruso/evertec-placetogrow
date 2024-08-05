<?php

namespace App\Policies;

use App\Enums\Payments\PaymentPermissions;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PaymentPermissions::ViewAny);
    }

    public function show(User $user, Payment $payment): bool
    {
        return $user->is_admin || ($user->hasPermissionTo(PaymentPermissions::View) && $payment->email === $user->email);
    }
}
