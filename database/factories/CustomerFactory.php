<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // This will generate a realistic-looking name, e.g., "John Doe"
            'customer_name' => fake()->name(),

            // This will generate a unique and safe email address
            'customer_email' => fake()->unique()->safeEmail(),

            // This will generate a fake phone number
            'customer_phone' => fake()->phoneNumber(),

            // Generate a random placeholder avatar
            'customer_image' => 'https://i.pravatar.cc/150?u=' . fake()->userName(),
        ];
    }
}
