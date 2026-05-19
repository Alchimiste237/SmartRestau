<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an Admin
        User::factory()->admin()->create([
            'name' => 'System Admin',
            'email' => 'admin@smartrestau.os',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        ]);

        // Create 3 Restaurant Owners with their restaurants
        $owners = User::factory(3)->owner()->create();

        foreach ($owners as $owner) {
            $restaurant = Restaurant::factory()->create([
                'owner_id' => $owner->id,
                'name' => 'Flavor Hub - ' . $owner->name,
            ]);

            // Create Categories for each restaurant
            $categories = [
                'Starters' => 1,
                'Main Courses' => 2,
                'Drinks' => 3,
                'Desserts' => 4,
            ];

            foreach ($categories as $catName => $order) {
                $category = MenuCategory::create([
                    'restaurant_id' => $restaurant->id,
                    'name' => $catName,
                    'display_order' => $order,
                ]);

                // Create 3-5 items for each category
                MenuItem::factory(rand(3, 5))->create([
                    'category_id' => $category->id,
                    'restaurant_id' => $restaurant->id,
                ]);
            }

            // Create 5-10 tables for each restaurant
            for ($i = 1; $i <= rand(5, 10); $i++) {
                RestaurantTable::create([
                    'restaurant_id' => $restaurant->id,
                    'table_number' => (string) $i,
                    'is_active' => true,
                ]);
            }
        }

        // Create a specific owner for testing purposes
        $testOwner = User::factory()->owner()->create([
            'name' => 'Urban Grill Owner',
            'email' => 'owner@urbangrill.com',
            'password' => \Illuminate\Support\Facades\Hash::make('owner123'),
        ]);

        $testRestaurant = Restaurant::factory()->create([
            'owner_id' => $testOwner->id,
            'name' => 'Urban Grill',
            'business_type' => 'Premium Grill',
            'location' => 'Bastos, Yaoundé',
        ]);

        $starterCat = MenuCategory::create([
            'restaurant_id' => $testRestaurant->id,
            'name' => 'Starters',
            'display_order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $starterCat->id,
            'restaurant_id' => $testRestaurant->id,
            'name' => 'Grilled Chicken Wings',
            'description' => 'Spicy grilled wings served with hot sauce',
            'price' => 2500,
            'is_available' => true,
        ]);

        $mainCat = MenuCategory::create([
            'restaurant_id' => $testRestaurant->id,
            'name' => 'Main Dishes',
            'display_order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $mainCat->id,
            'restaurant_id' => $testRestaurant->id,
            'name' => 'Braised Fish',
            'description' => 'Freshly braised fish with local spices',
            'price' => 4500,
            'is_available' => true,
        ]);

        RestaurantTable::create([
            'restaurant_id' => $testRestaurant->id,
            'table_number' => '12',
            'is_active' => true,
        ]);
    }
}
