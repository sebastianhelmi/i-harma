<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item_categories = [
            [
                'name' => 'electrical',
                'description' => 'Electrical'
            ],
            [
                'name' => 'mechanical',
                'description' => 'Mechanical'
            ],
        ];

        DB::table('item_categories')->insert($item_categories);
    }
}
