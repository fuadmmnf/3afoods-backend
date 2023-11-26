<?php

namespace Database\Seeders;

use App\Models\ShippingProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingProduct::factory()->count(10)->create();
    }
}
