<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'), // Ensure the password is hashed
        ]);
        $seller = User::factory()->create([
            'name' => 'Test User',
            'email' => 'seller@seller.com',
            'password' => bcrypt('seller'), // Ensure the password is hashed
        ]);
        $this->call([
            RolesAndPermissionsSeeder::class,
            LeadTableSeeder::class,
        ]);

        // Assign admin role to the test user
        $admin->assignRole('admin');
        $seller->assignRole('seller');
    }
}
