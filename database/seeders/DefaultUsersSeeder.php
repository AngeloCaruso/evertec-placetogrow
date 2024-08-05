<?php

namespace Database\Seeders;

use App\Enums\System\DefaultRoles;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()
            ->firstOrCreate([
                'email' => config('app.admin_email'),
            ], [
                'name' => 'Admin',
                'password' => bcrypt(config('app.admin_password')),
            ]);

        $guest = User::query()
            ->firstOrCreate([
                'email' => 'guest@mail.com',
            ], [
                'name' => 'Guest',
                'password' => bcrypt('secret'),
            ]);

        $admin->syncRoles([DefaultRoles::Admin]);
        $guest->syncRoles([DefaultRoles::Guest]);
    }
}
