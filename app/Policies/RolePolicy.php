<?php

namespace App\Policies;

use App\Enums\Roles\RolePermissions;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(RolePermissions::ViewAny);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(RolePermissions::Create);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasAnyPermission([RolePermissions::Update, RolePermissions::View]);
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(RolePermissions::Delete);
    }
}
