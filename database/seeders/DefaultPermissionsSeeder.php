<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Acl\AccessControlListPermissions;
use App\Enums\Imports\ImportPermissions;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Payments\PaymentPermissions;
use App\Enums\Roles\RolePermissions;
use App\Enums\Subscriptions\SubscriptionPermissions;
use App\Enums\Users\UserPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class DefaultPermissionsSeeder extends Seeder
{
    use RefreshesPermissionCache;

    public function run(): void
    {
        $micrositesPermissions = MicrositePermissions::cases();
        $userPermissions = UserPermissions::cases();
        $rolePermissions = RolePermissions::cases();
        $aclPermissions = AccessControlListPermissions::cases();
        $paymentPermissions = PaymentPermissions::cases();
        $subscriptionPermissions = SubscriptionPermissions::cases();
        $importPermissions = ImportPermissions::cases();

        foreach ([
            ...$micrositesPermissions,
            ...$userPermissions,
            ...$rolePermissions,
            ...$aclPermissions,
            ...$paymentPermissions,
            ...$subscriptionPermissions,
            ...$importPermissions,
        ] as $permission) {
            Permission::query()
                ->firstOrCreate([
                    'name' => $permission->value,
                ]);
        }
    }
}
