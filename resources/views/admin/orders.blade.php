
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin - Orders') }}
        </h2>
    </x-slot>

    <div x-data="orderAdmin()" x-init="fetchOrders()" class="max-w-4xl mx-auto px-4 py-6">
        <template x-if="orders.length === 0">
            <p class="text-gray-600">No orders found.</p>
        </template>

        <div x-show="orders.length > 0" class="space-y-4">
            <template x-for="order in orders" :key="order.id">
                <div class="bg-white shadow p-4 rounded">
                    <p><strong>Order #</strong> <span x-text="order.id"></span> | <strong>User:</strong> <span
                            x-text="order.user_id"></span></p>
                    <p><strong>Total:</strong> $<span x-text="order.total_price"></span></p>
                    <p><strong>Status:</strong> <span x-text="order.status"></span></p>

                    <select x-model="order.status" @change="updateStatus(order)" class="mt-2 border px-2 py-1">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

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
        function orderAdmin() {
            return {
                orders: [],
                fetchOrders() {
                    fetch('/api/admin/orders', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer {{session('token')}}`
                        }
                    })
                        .then(res => res.json())
                        .then(data => this.orders = data.data)
                        .catch(err => console.error('Error:', err));
                },
                updateStatus(order) {
                    fetch(`/api/admin/orders/${order.id}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer {{session('token')}}`
                        },
                        body: JSON.stringify({ status: order.status })
                    })
                        .then(() => this.fetchOrders())
                        .catch(err => console.error('Error:', err));
                }
            }
        }
    </script>
</x-app-layout>
