<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Project Manager',
                'email' => 'pm@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 1, // Project Manager role
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 2, // Admin role
            ],
            [
                'name' => 'Purchasing Staff',
                'email' => 'purchasing@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 3, // Purchasing role
            ],
            [
                'name' => 'Kepala Divisi Electrical',
                'email' => 'kadivel@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 4, // Kepala Divisi role
                'division_id' => 1, // Kepala Divisi role
            ],
            [
                'name' => 'Kepala Divisi Mechanical',
                'email' => 'kadivmec@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 4, // Kepala Divisi role
                'division_id' => 2, // Kepala Divisi role
            ],
            [
                'name' => 'Kepala Divisi',
                'email' => 'kadiv@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 4, // Kepala Divisi role
            ],
            [
                'name' => 'Delivery',
                'email' => 'delivery@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 5,
            ],
            [
                'name' => 'Inventory',
                'email' => 'inventory@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 6,
            ],
            [
                'name' => 'sipil staf',
                'email' => 'sipily@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 4,
                'division_id' => 3, // Kepala Divisi role
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
