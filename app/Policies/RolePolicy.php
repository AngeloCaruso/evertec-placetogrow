<?php

declare(strict_types=1);

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

    public function show(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(RolePermissions::View);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(RolePermissions::Update);
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(RolePermissions::Delete);
    }
}
