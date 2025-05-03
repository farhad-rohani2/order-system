<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            Order::factory()->count(2)->create([ 'user_id' => $user->id ])->each(function ($order) use ($products) {
                $items = $products->random(3);
                $total = 0;

                foreach ($items as $product) {
                    $quantity = rand(1, 3);
                    $total += $product->price * $quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ]);
                }

                $order->update(['total_price' => $total]);
            });
        }
    }
}
