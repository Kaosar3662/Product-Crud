<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'status' => 0],
            ['name' => 'Furniture', 'status' => 0],
            ['name' => 'Clothing', 'status' => 0],
            ['name' => 'Non-Living', 'status' => 1],
            ['name' => 'Living-Things', 'status' => 1],
            ['name' => 'Un-Obtainable', 'status' => 1],
        ];

        foreach ($categories as $categoryData) {
            $categoryData['slug'] = Str::slug($categoryData['name']);
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
    }
}