<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'scientific_name' => fake()->name(),
            'commercial_name'=> fake()->name(),
            'quantity' => fake()->randomNumber(),
            'price'=> fake()->randomFloat(),
            'expiration_date' => fake()->date(),
            'image' => fake()->name(),
            'company_id' => fake()->numberBetween(1,3),
            'admin_id' => fake()->numberBetween(1,2),
        ];
    }
}
