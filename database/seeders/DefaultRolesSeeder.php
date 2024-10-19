<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Acl\AccessControlListPermissions;
use App\Enums\Dashboard\DashboardPermissions;
use App\Enums\Imports\ImportPermissions;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Payments\PaymentPermissions;
use App\Enums\Roles\RolePermissions;
use App\Enums\Subscriptions\SubscriptionPermissions;
use App\Enums\System\DefaultRoles;
use App\Enums\Users\UserPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class DefaultRolesSeeder extends Seeder
{
    use RefreshesPermissionCache;

    public function run(): void
    {
        $roles = DefaultRoles::cases();

        foreach ($roles as $role) {
            Role::query()
                ->firstOrCreate([
                    'name' => $role->value,
                ]);
        }

        $admin = Role::query()
            ->where('name', DefaultRoles::Admin)
            ->first();

        $admin->syncPermissions([
            ...MicrositePermissions::cases(),
            ...UserPermissions::cases(),
            ...RolePermissions::cases(),
            ...AccessControlListPermissions::cases(),
            ...PaymentPermissions::cases(),
            ...SubscriptionPermissions::cases(),
            ...ImportPermissions::cases(),
        ]);

        $guest = Role::query()
            ->where('name', DefaultRoles::Guest)
            ->first();

        $guest->syncPermissions([...PaymentPermissions::cases(), ...SubscriptionPermissions::cases()]);
    }
}
