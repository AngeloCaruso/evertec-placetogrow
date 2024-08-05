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

    public function show(User $user, Microsite $microsite): bool
    {
        return $user->is_admin || ($user->hasPermissionTo(MicrositePermissions::View) && $user->microsite?->id === $microsite->id);
    }

    public function update(User $user, Microsite $microsite): bool
    {
        return $user->is_admin || ($user->hasPermissionTo(MicrositePermissions::Update) && $user->microsite?->id === $microsite->id);
    }

    public function delete(User $user, Microsite $microsite): bool
    {
        return $user->is_admin || ($user->hasPermissionTo(MicrositePermissions::Delete) && $user->microsite?->id === $microsite->id);
    }
}
