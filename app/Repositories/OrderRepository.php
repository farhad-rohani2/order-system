<?php


namespace App\Repositories;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function getUserOrders(int $userId)
    {
        return Order::with('items.product')->where('user_id', $userId)->latest()->get();
    }

    public function find(int $id): ?Order
    {
        return Order::with('items.product')->find($id);
    }

    public function query()
    {
        return Order::query();
    }
}

