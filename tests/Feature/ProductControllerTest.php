<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_can_list_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');
        $response->assertOk()->assertJsonCount(3);
    }

    public function test_can_create_product()
    {
        $payload = ['name' => 'Test Product', 'price' => 10.5, 'stock' => 100];

        $response = $this->postJson('/api/products', $payload);
        $response->assertCreated()->assertJsonFragment(['name' => 'Test Product']);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertOk()->assertJson(['message' => 'Updated']);
        $this->assertDatabaseHas('products', ['name' => 'Updated Name']);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertOk()->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_validation_error_on_create()
    {
        $response = $this->postJson('/api/products', []);
        $response->assertStatus(422)->assertJsonStructure(['errors']);
    }
}
