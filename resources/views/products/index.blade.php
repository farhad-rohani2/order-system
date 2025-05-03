<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div x-data="productList()" x-init="fetchProducts()" class="max-w-6xl mx-auto px-4 py-6">
        <template x-if="products.length === 0">
            <p class="text-gray-600">No products available.</p>
        </template>

        <div x-show="products.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <template x-for="product in products" :key="product.id">
                <div class="bg-white rounded shadow p-4">
                    <h3 class="font-bold text-lg" x-text="product.name"></h3>
                    <p class="text-gray-600">Price: $<span x-text="product.price"></span></p>
                    <p class="text-sm text-gray-400">Stock: <span x-text="product.stock"></span></p>
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
        function productList() {
            return {
                products: [],
                fetchProducts() {
                    fetch('/api/products', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer {{$token}}`
                        }
                    })
                        .then(res => res.json())
                        .then(data => this.products = data)
                        .catch(err => console.error('Error:', err));
                }
            }
        }
    </script>
</x-app-layout>
