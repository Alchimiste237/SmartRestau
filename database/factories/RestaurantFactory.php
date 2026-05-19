<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory()->owner(),
            'name' => fake()->company() . ' Restaurant',
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'contact' => fake()->phoneNumber(),
            'business_type' => fake()->randomElement(['Fast-food', 'Premium', 'Hotel Lounge', 'Bakery']),
            'opening_hours' => [
                'monday' => '08:00 - 22:00',
                'tuesday' => '08:00 - 22:00',
                'wednesday' => '08:00 - 22:00',
                'thursday' => '08:00 - 22:00',
                'friday' => '08:00 - 23:00',
                'saturday' => '09:00 - 23:00',
                'sunday' => '10:00 - 21:00',
            ],
            'logo_path' => 'placeholders/logo.svg',
            'cover_path' => 'placeholders/food.svg',
            'is_active' => true,
        ];
    }
}
