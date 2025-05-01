<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $mock = Mockery::mock(ProductRepositoryInterface::class);
        $mock->shouldReceive('create')
            ->once()
            ->with(["name" => "Test", "price" => 100, "stock" => 5])
            ->andReturn(new Product(["name" => "Test", "price" => 100, "stock" => 5]));

        $service = new ProductService($mock);

        $product = $service->create(["name" => "Test", "price" => 100, "stock" => 5]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals("Test", $product->name);
    }

    public function test_can_fetch_all_products()
    {
        $mock = Mockery::mock(ProductRepositoryInterface::class);
        $mock->shouldReceive('all')->once()->andReturn(collect([new Product()]));

        $service = new ProductService($mock);
        $result = $service->list();

        $this->assertIsIterable($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
