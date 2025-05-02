<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepo,
        protected ProductRepositoryInterface $productRepo
    ) {}

    public function placeOrder(array $items, int $userId)
    {
        return DB::transaction(function () use ($items, $userId) {
            $total = 0;
            foreach ($items as $item) {
                $product = $this->productRepo->find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Product not available or insufficient stock.");
                }
                $total += $product->price * $item['quantity'];
            }

            $order = $this->orderRepo->create([
                'user_id' => $userId,
                'status' => 'pending',
                'total_price' => $total
            ]);

            foreach ($items as $item) {
                $product = $this->productRepo->find($item['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);
                $product->decrement('stock', $item['quantity']);
            }

            return $order;
        });
    }

    public function listUserOrders(int $userId)
    {
        return $this->orderRepo->getUserOrders($userId);
    }
}
