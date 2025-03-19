<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat roles
        $manager = Role::create(['name' => 'Manager']);
        $kepalaDivisi = Role::create(['name' => 'Kepala Divisi']);
        $purchasing = Role::create(['name' => 'Purchasing']);
        $inventory = Role::create(['name' => 'Inventory']);
        $delivery = Role::create(['name' => 'Delivery']);


        // Buat permissions
        $permissions = [
            'manage projects',
            'create SPB',
            'approve SPB',
            'manage inventory',
            'handle delivery',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $manager->givePermissionTo(['manage projects']);
        $kepalaDivisi->givePermissionTo(['create SPB']);
        $purchasing->givePermissionTo(['approve SPB']);
        $inventory->givePermissionTo(['manage inventory']);
        $delivery->givePermissionTo(['handle delivery']);
    }
}
