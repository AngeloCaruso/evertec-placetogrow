<?php

namespace App\Policies;

use App\Actions\AccessControlList\ApplyAclAction;
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

    public function show(User $user, User $model): bool
    {
        return $user->hasPermissionTo(UserPermissions::View) && ApplyAclAction::exec($user, $model);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo(UserPermissions::Update) && ApplyAclAction::exec($user, $model);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo(UserPermissions::Delete) && ApplyAclAction::exec($user, $model);
    }
}
