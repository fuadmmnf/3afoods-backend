<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all categories
        $categories = Category::all();

        // Array of 20 random food product names
        $foodProductNames = [
            'Apple',
            'Banana',
            'Orange',
            'Strawberry',
            'Blueberry',
            'Carrot',
            'Broccoli',
            'Tomato',
            'Chicken Breast',
            'Salmon Fillet',
            'Bread Loaf',
            'Cheese',
            'Milk',
            'Eggs',
            'Pasta',
            'Rice',
            'Avocado',
            'Spinach',
            'Lemon',
            'Mutton'
        ];

        // Create 20 food-related products with random names
        for ($i = 1; $i <= 20; $i++) {
            // Randomly select a category
            $category = $categories->random();

            // Determine product type
            $type = ($i <= 10) ? 'retail' : 'wholesale';
            $productName = $foodProductNames[$i-1];
            Product::create([
                'category_id' => $category->id,
                'title' => "$productName",
                'desc' => "Description for Delicious $type $productName Lorem ipsum dolor sit amet, cupiditate dolore doloribus",
                'type' => $type,
                'img' => "product_image_$i.jpg",
                'price' => mt_rand(10, 100),
                'unit' => $category->unit,
            ]);
        }
    }
}
