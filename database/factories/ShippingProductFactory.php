<?php

namespace Database\Factories;

use App\Models\ShippingProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingProduct>
 */
class ShippingProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ShippingProduct::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'business_name' => $this->faker->company,
            'avn' => $this->faker->word,
            'contact_info' => $this->faker->phoneNumber,
            'website_name' => $this->faker->optional()->url,
            'file' => $this->faker->word,
            'additional_information' => $this->faker->paragraph,
        ];
    }
}
