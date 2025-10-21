<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' =>  'Laravel', 'slug' => 'laravel'],
            ['name' =>  'VueJS', 'slug' => 'vuejs'],
            ['name' =>  'ReactJS', 'slug' => 'reactjs'],
            ['name' =>  'NodeJS', 'slug' => 'nodejs'],
            ['name' =>  'PHP', 'slug' => 'php'],
            ['name' =>  'Javascript', 'slug' => 'javascript'],
            ['name' =>  'Python', 'slug' => 'python'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                ['slug' => $tag['slug']]
            );
        }
    }
}
