
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div x-data="userOrders()" x-init="fetchOrders()" class="max-w-4xl mx-auto px-4 py-6">
        <template x-if="orders.length === 0">
            <p class="text-gray-600">You have no orders.</p>
        </template>

        <div x-show="orders.length > 0" class="space-y-4">
            <template x-for="order in orders" :key="order.id">
                <div class="bg-white shadow p-4 rounded">
                    <p><strong>Order #</strong> <span x-text="order.id"></span></p>
                    <p><strong>Total:</strong> $<span x-text="order.total_price"></span></p>
                    <p><strong>Status:</strong> <span x-text="order.status"></span></p>

                    <ul class="mt-2 text-sm text-gray-600">
                        <template x-for="item in order.items" :key="item.id">
                            <li>
                                <span x-text="item.product.name"></span> × <span x-text="item.quantity"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>
        </div>
    </div>

    <script>
        @php
              if ($token=session('token')){
              }else{
                  $user = Auth::user();
                  $token = $user->createToken('product-page')->plainTextToken;
                  session(['token'=>$token]);
              }
        @endphp
        function userOrders() {
            return {
                orders: [],
                fetchOrders() {
                    fetch('/api/orders', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer {{$token}}`
                        }
                    })
                        .then(res => res.json())
                        .then(data => this.orders = data)
                        .catch(err => console.error('Error:', err));
                }
            }
        }
    </script>
</x-app-layout>
