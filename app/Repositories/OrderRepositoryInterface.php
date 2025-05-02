<?php

namespace App\Repositories;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;
    public function getUserOrders(int $userId);
    public function find(int $id): ?Order;
    public function query();
}
