<?php

namespace Database\Seeders;

use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Roles\RolePermissions;
use App\Enums\Users\UserPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DefaultPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $micrositesPermissions = MicrositePermissions::cases();
        $userPermissions = UserPermissions::cases();
        $rolePermissions = RolePermissions::cases();

        foreach ([...$micrositesPermissions, ...$userPermissions, ...$rolePermissions] as $permission) {
            Permission::query()
                ->firstOrCreate([
                    'name' => $permission->value,
                ]);
        }
    }
}
