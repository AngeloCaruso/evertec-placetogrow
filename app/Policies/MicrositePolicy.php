<?php

declare(strict_types=1);

namespace App\Policies;

use App\Actions\AccessControlList\ApplyAclAction;
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

    public function show(User $user, Microsite $microsite): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::View) && ApplyAclAction::exec($user, $microsite);
    }

    public function update(User $user, Microsite $microsite): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Update) && ApplyAclAction::exec($user, $microsite);
    }

    public function delete(User $user, Microsite $microsite): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Delete) && ApplyAclAction::exec($user, $microsite);
    }

    public function dashboard(User $user): bool
    {
        return $user->hasPermissionTo(MicrositePermissions::Dashboard);
    }
}
