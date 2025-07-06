<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Electrical',
                'code' => 'Elec',
                'description' => 'Electrical Division'
            ],
            [
                'name' => 'Mechanical',
                'code' => 'Mec',
                'description' => 'Mechanical Division'
            ],
             [
                'name' => 'Civil',
                'code' => 'Civ',
                'description' => 'Civil Division'
            ],
        ];

        DB::table('divisions')->insert($divisions);
    }
}
