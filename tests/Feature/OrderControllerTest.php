<?php

use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

test('user can place an order successfully', function () {
    $product = Product::factory()->create(['stock' => 10, 'price' => 20]);

    $response = $this->postJson('/api/orders', [
        'items' => [
            ['product_id' => $product->id, 'quantity' => 2]
        ]
    ]);

    $response->assertCreated()
        ->assertJsonFragment(['product_id' => $product->id, 'quantity' => 2]);

    $this->assertDatabaseHas('orders', ['user_id' => $this->user->id]);
    $this->assertDatabaseHas('order_items', ['product_id' => $product->id]);
    $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 8]);
});

test('order fails if product stock is insufficient', function () {
    $product = Product::factory()->create(['stock' => 1]);

    $response = $this->postJson('/api/orders', [
        'items' => [
            ['product_id' => $product->id, 'quantity' => 5]
        ]
    ]);

    $response->assertStatus(400)
        ->assertJsonFragment(['message' => 'Product not available or insufficient stock.']);

    $this->assertDatabaseMissing('orders', ['user_id' => $this->user->id]);
});

test('validation error is returned on missing items', function () {
    $response = $this->postJson('/api/orders', []);
    $response->assertStatus(422)->assertJsonStructure(['errors']);
});

test('user can list their orders', function () {
    $response = $this->getJson('/api/orders');
    $response->assertOk();
});
