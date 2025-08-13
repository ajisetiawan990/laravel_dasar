<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Category::factory()->count(3)->create()
        ->each(function($category) {
            \App\Models\Product::factory()->count(5)->create([
                'category_id' => $category->id
            ]);
        });
    }
}
