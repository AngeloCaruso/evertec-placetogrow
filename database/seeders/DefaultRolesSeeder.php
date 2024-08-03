<?php

namespace Database\Seeders;

use App\Enums\Acl\AccessControlListPermissions;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Roles\RolePermissions;
use App\Enums\System\DefaultRoles;
use App\Enums\Users\UserPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DefaultRolesSeeder extends Seeder
{
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
            ...AccessControlListPermissions::cases()
        ]);
    }
}
