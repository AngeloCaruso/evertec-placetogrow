<?php

namespace App\Policies;

use App\Enums\Acl\AccessControlListPermissions;
use App\Models\AccessControlList;
use App\Models\User;

class AccessControlListPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(AccessControlListPermissions::ViewAny);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(AccessControlListPermissions::Create);
    }

    public function update(User $user, AccessControlList $accessControlList): bool
    {
        return $user->hasPermissionTo(AccessControlListPermissions::Update);
    }

    public function delete(User $user, AccessControlList $accessControlList): bool
    {
        return $user->hasPermissionTo(AccessControlListPermissions::Delete);
    }
}
