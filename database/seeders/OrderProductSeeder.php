<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Truncate the order_products table
        OrderProduct::query()->delete();

        // Get all orders and products
        $orders = Order::all();
        $products = Product::all();

        // Create order items for each order
        foreach ($orders as $order) {
            // Get at least 2 products for each order
            $orderProductsCount = mt_rand(2, 5);

            // Randomly select products and add them to the order
            for ($i = 1; $i <= $orderProductsCount; $i++) {
                $product = $products->random();

                // Random quantity between 1 and 5
                $quantity = mt_rand(1, 5);

                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }
        }
    }
}
