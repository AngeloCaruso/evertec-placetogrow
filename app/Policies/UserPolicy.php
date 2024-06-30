<?php

namespace App\Policies;

use App\Enums\Users\UserPermissions;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(UserPermissions::ViewAny);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(UserPermissions::Create);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasAnyPermission([UserPermissions::Update, UserPermissions::View]);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo(UserPermissions::Delete);
    }
}
