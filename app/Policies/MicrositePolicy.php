<?php

namespace App\Policies;

use App\Enums\Microsites\MicrositePermissions;
use App\Models\Microsite;
use App\Models\User;

class MicrositePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::ViewAny);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Create);
    }

    public function update(User $user, Microsite $microsite): bool
    {
        return $user->hasAnyPermission([MicrositePermissions::Update, MicrositePermissions::View]);
    }

    public function delete(User $user, Microsite $microsite): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Delete);
    }
}
