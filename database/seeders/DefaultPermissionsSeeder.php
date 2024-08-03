<?php

namespace Database\Seeders;

use App\Enums\Acl\AccessControlListPermissions;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Roles\RolePermissions;
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

        foreach ([
            ...$micrositesPermissions,
            ...$userPermissions,
            ...$rolePermissions,
            ...$aclPermissions
        ] as $permission) {
            Permission::query()
                ->firstOrCreate([
                    'name' => $permission->value,
                ]);
        }
    }
}
