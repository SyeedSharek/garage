<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'SVC-' . fake()->unique()->numerify('####'),
            'name' => [
                'en' => fake()->words(3, true),
                'ar' => fake()->words(3, true),
            ],
            'description' => [
                'en' => fake()->sentence(),
                'ar' => fake()->sentence(),
            ],
            'unit_price' => fake()->randomFloat(2, 10, 1000),
            'unit' => 'pcs',
            'is_active' => true,
            'is_custom' => false,
            'sort_order' => 0,
        ];
    }
}
