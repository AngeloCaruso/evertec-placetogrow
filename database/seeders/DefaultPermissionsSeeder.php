<?php

namespace Database\Seeders;

use App\Enums\Microsites\MicrositePermissions;
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

        foreach ($micrositesPermissions as $permission) {
            Permission::query()
                ->firstOrCreate([
                    'name' => $permission->value,
                ]);
        }
    }
}
