<?php

declare(strict_types=1);

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
}
