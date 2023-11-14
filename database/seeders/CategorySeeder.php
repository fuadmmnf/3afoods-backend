<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create 5 food-related categories
        $foodCategories = [
            'Fruits' => 'kg',
            'Vegetables' => 'kg',
            'Dairy' => 'ltr',
            'Meat' => 'kg',
            'Bakery' => 'unit',
        ];

        foreach ($foodCategories as $categoryName => $unit) {
            Category::create([
                'category_name' => $categoryName,
                'unit' => $unit,
            ]);
        }
    }
}
