<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Project Manager'],
            ['name' => 'Admin'],
            ['name' => 'Purchasing'],
            ['name' => 'Kepala Divisi'],
            ['name' => 'Delivery'],
            ['name' => 'Inventory'],
        ];

        DB::table('roles')->insert($roles);
    }
}
