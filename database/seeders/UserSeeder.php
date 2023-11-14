<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//         Create two retail users
        User::create([
            'name' => 'Abdul Monir',
            'usertype' => 'retail',
            'phone' => '12345678',
            'email' => 'retail_user1@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Hannan Ahmed',
            'usertype' => 'retail',
            'phone' => '12345678',
            'email' => 'retail_user2@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create two wholesale users
        User::create([
            'name' => 'FoodInventory',
            'usertype' => 'wholesale',
            'phone' => '12345678',
            'email' => 'wholesale_user1@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'FoodSupplier',
            'usertype' => 'wholesale',
            'phone' => '12345678',
            'email' => 'wholesale_user2@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create an admin user
        $adminUser = User::create([
            'name' => 'admin',
            'usertype' => 'admin',
            'phone' => '1234567890',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin12345'),
        ]);

        // Assign the "admin" role to the admin user
        $adminRole = Role::create(['name' => 'admin']);
        $adminUser->assignRole($adminRole);

    }
}



