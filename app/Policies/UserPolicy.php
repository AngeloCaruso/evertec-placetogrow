<?php

namespace App\Policies;

use App\Enums\Users\UserPermissions;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(UserPermissions::ViewAny);
    }
    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo(UserPermissions::View);
    }
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(UserPermissions::Create);
    }
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo(UserPermissions::Update);
    }
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo(UserPermissions::Delete);
    }
}
