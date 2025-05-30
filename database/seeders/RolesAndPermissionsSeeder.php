<?php

namespace Database\Seeders;

use App\Enum\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            PermissionEnum::VIEW_LEADS,
            PermissionEnum::DELETE_LEADS,
            PermissionEnum::REGISTER_USER,
            PermissionEnum::VIEW_STATS,
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $sellerRole = Role::firstOrCreate(['name' => 'seller']);
        $sellerRole->syncPermissions([
            PermissionEnum::VIEW_LEADS,
        ]);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            PermissionEnum::VIEW_LEADS,
            PermissionEnum::DELETE_LEADS,
        ]);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
    }
}
