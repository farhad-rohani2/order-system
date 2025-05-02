<?php

use App\Models\User;
use App\Models\Order;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    Sanctum::actingAs($this->admin);
});

test('admin can list orders with optional status filter', function () {
    Order::factory()->count(2)->create(['status' => 'pending']);
    Order::factory()->count(1)->create(['status' => 'completed']);

    $this->getJson('/api/admin/orders')
        ->assertOk()
        ->assertJsonCount(3, 'data');

    $this->getJson('/api/admin/orders?status=completed')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('admin can update order status', function () {
    $order = Order::factory()->create(['status' => 'pending']);

    $this->putJson("/api/admin/orders/{$order->id}/status", ['status' => 'completed'])
        ->assertOk()
        ->assertJson(['message' => 'Order status updated']);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'completed',
    ]);
});

test('invalid status returns validation error', function () {
    $order = Order::factory()->create(['status' => 'pending']);

    $this->putJson("/api/admin/orders/{$order->id}/status", ['status' => 'invalid'])
        ->assertStatus(422)
        ->assertJsonStructure(['errors']);
});

test('updating non-existent order returns 404', function () {
    $this->putJson('/api/admin/orders/999/status', ['status' => 'cancelled'])
        ->assertNotFound()
        ->assertJson(['message' => 'Order not found']);
});
