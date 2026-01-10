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
            ['name' => 'Electronics', 'status' => 1],
            ['name' => 'Furniture', 'status' => 1],
            ['name' => 'Clothing', 'status' => 1],
        ];

        foreach ($categories as $categoryData) {
            $categoryData['slug'] = Str::slug($categoryData['name']);
            Category::create($categoryData);
        }
    }
}