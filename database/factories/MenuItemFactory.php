<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => MenuCategory::factory(),
            'restaurant_id' => function (array $attributes) {
                return MenuCategory::find($attributes['category_id'])->restaurant_id;
            },
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(500, 5000), // In FCFA
            'is_available' => true,
            'photo_path' => 'placeholders/food.svg',
            'display_order' => 0,
        ];
    }
}
