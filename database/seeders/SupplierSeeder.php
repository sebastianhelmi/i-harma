<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Semen Indonesia',
                'address' => 'Jl. Industri No. 1, Jakarta',
                'phone' => '021-555555',
                'email' => 'sales@semenindonesia.com',
                'description' => 'Supplier semen dan material bangunan'
            ],
            [
                'name' => 'PT Besi Jaya',
                'address' => 'Jl. Logam No. 10, Bekasi',
                'phone' => '021-666666',
                'email' => 'sales@besijaya.com',
                'description' => 'Supplier besi dan baja'
            ],
            [
                'name' => 'PT Bata Merah Jaya',
                'address' => 'Jl. Material No. 15, Tangerang',
                'phone' => '021-777777',
                'email' => 'sales@batamerahjaya.com',
                'description' => 'Supplier bata dan material bangunan'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }
    }
}
