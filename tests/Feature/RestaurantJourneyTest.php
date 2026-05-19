<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantJourneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_customer_journey()
    {
        $this->withoutVite();
        // 1. Setup: Restaurant with a table and a menu item
        $owner = User::factory()->owner()->create();
        $restaurant = Restaurant::factory()->create(['owner_id' => $owner->id]);
        $category = \App\Models\MenuCategory::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Main Dishes',
            'display_order' => 1,
        ]);
        $table = RestaurantTable::create([
            'restaurant_id' => $restaurant->id,
            'table_number' => '5',
            'is_active' => true,
        ]);
        $item = MenuItem::factory()->create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id,
            'price' => 1500,
        ]);

        // 2. Customer visits the menu page
        $response = $this->get(route('customer.menu', [$restaurant->id, $table->id]));
        $response->assertStatus(200);

        // 2.5. Customer authenticates via social auth (guest registration) - NOT using actingAs
        // This simulates a guest customer doing one-time registration
        $response = $this->postJson(route('auth.customer.social'), [
            'name' => 'Guest Customer',
        ]);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // 3. Customer places an order
        $orderData = [
            'items' => [
                [
                    'id' => $item->id,
                    'quantity' => 2,
                    'notes' => 'Extra spicy'
                ]
            ],
            'payment_method' => 'cash',
            '_token' => csrf_token()
        ];

        $response = $this->postJson(route('customer.order.process', [$restaurant->id, $table->id]), $orderData);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'restaurant_id' => $restaurant->id,
            'table_id' => $table->id,
            'total_price' => 3000,
            'status' => 'pending'
        ]);

        $orderId = $response->json('order_id');

        // 4. Owner checks the dashboard (via API)
        $this->actingAs($owner);
        $response = $this->get(route('owner.api.orders.live'));
        $response->assertStatus(200);
        $response->assertJsonPath('orders.0.id', $orderId);
        $response->assertJsonPath('orders.0.status', 'pending');

        // 5. Owner starts preparing the order
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class);
        $response = $this->post(route('owner.orders.update-status', $orderId), [
            'status' => 'preparing'
        ]);
        $response->assertStatus(302);
        
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 'preparing'
        ]);

        // 6. Customer tracks the order
        $this->get(route('customer.order-tracking', $orderId))
            ->assertStatus(200)
            ->assertSee('preparing');

        // 7. Owner marks as served
        $this->post(route('owner.orders.update-status', $orderId), [
            'status' => 'served'
        ]);
        
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 'served'
        ]);

        // 8. Stats check
        $response = $this->get(route('owner.api.orders.live'));
        $response->assertJsonPath('stats.revenue', 3000);
    }
}
