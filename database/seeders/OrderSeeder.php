<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {

        // Get all users and categories
        $users = User::all();
        $categories = Category::all();

        // Create 10 orders with related data
        for ($i = 1; $i <= 10; $i++) {
            // Randomly select a user and category
            $user = $users->random();
            // Determine order type
            $type = ($i <= 7) ? 'retail' : 'wholesale';
            // Determine order status
            $status = ($i <= 3) ? 'draft' : 'pending';

            Order::create([
                'user_id' => $user->id,
                'type' => $type,
                'fname' => $user->name,
                'lname' => $user->name,
                'company_name' => ($type == 'wholesale') ? 'Company ' . $i : 'My Retail Shop',
                'address' => "Address for Order $i",
                'phone_num' => $user->phone,
                'email' => $user->email,
                'additional_info' => "Additional Info for Order $i",
                'total_price' => mt_rand(50, 200),
                'status' => $status,
                'created_at' => now()->subDays($i), // Spread orders over the last 10 days
            ]);
        }
    }
}
