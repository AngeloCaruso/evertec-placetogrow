<?php

declare(strict_types=1);

namespace App\Policies;

use App\Actions\Payments\GetAllPaymentsWithAclAction;
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
        $payments = GetAllPaymentsWithAclAction::exec($user, $payment);
        return $user->is_admin || ($user->hasPermissionTo(PaymentPermissions::View) && $payments->get()->contains($payment));
    }
}
