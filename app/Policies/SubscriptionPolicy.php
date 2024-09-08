<?php

declare(strict_types=1);

namespace App\Policies;

use App\Actions\Subscriptions\GetAllSubscriptionsWithAclAction;
use App\Enums\Subscriptions\SubscriptionPermissions;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(SubscriptionPermissions::ViewAny);
    }

    public function show(User $user, Subscription $subscription): bool
    {
        $subscriptions = GetAllSubscriptionsWithAclAction::exec($user, $subscription);
        return $user->is_admin || ($user->hasPermissionTo(SubscriptionPermissions::View) && $subscriptions->get()->contains($subscription));
    }
}
