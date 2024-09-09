<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Imports\ImportPermissions;
use App\Models\DataImport;
use App\Models\User;

class DataImportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(ImportPermissions::ViewAny);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(ImportPermissions::Create);
    }

    public function show(User $user, DataImport $payment): bool
    {
        // $payments = GetAllPaymentsWithAclAction::exec($user, $payment);
        return $user->is_admin || ($user->hasPermissionTo(ImportPermissions::View));
    }
}
