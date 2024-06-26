<?php

namespace Database\Seeders;

use App\Enums\Microsites\MicrositePermissions;
use App\Enums\System\DefaultRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DefaultRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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

        $admin->syncPermissions(MicrositePermissions::cases());
    }
}
