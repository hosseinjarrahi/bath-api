<?php

namespace Database\Seeders;

use Modules\Auth\Models\User;
use Modules\Auth\Models\UserLevelPermission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Hossein',
            'username' => 'admin',
            'user_level' => 1,
            'password' => bcrypt('password'),
        ]);

        UserLevelPermission::create([
            'name' => 'admin',
            'permission_do' => ["*"],
        ]);

        $this->call([
            // CameraDutySeeder::class,
            // CameraSeeder::class,
            // DoorSeeder::class,
            // ServiceSeeder::class,
            // TruckLoadTypeSeeder::class,
            // StationTariffSeeder::class,
            StationSeeder::class
        ]);
    }
}
