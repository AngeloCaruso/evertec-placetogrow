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

    public function view(User $user, Microsite $microsite): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::View);
    }
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Create);
    }

    public function update(User $user, Microsite $microsite): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Update);
    }

    public function delete(User $user, Microsite $microsite): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Delete);
    }
}
