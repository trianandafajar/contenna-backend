<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' =>  'Technology Stack', 'slug' => 'technology-stack', 'status' => '1'],
            ['name' =>  'Knowledge', 'slug' => 'knowledge', 'status' => '1'],
        ];

        foreach ($categories as $item) {
            Category::firstOrCreate(
                ['name' => $item['name']],
                ['slug' => $item['slug']],
                ['status' => $item['status']]
            );
        }
    }
}
