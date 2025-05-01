<?php
namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    public function index()
    {
        $products = Cache::remember('products.all', 60, fn () => $this->productService->list());
        return response()->json($products);
    }

    public function show(int $id)
    {
        $product = $this->productService->get($id);
        return $product ? response()->json($product) : response()->json(['message' => 'Not Found'], 404);
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'price', 'stock']);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = $this->productService->create($data);
        Cache::forget('products.all');
        return response()->json($product, 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->only(['name', 'price', 'stock']);

        $validator = Validator::make($data, [
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updated = $this->productService->update($id, $data);
        if ($updated) {
            Cache::forget('products.all');
            return response()->json(['message' => 'Updated']);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }

    public function destroy(int $id)
    {
        $deleted = $this->productService->delete($id);
        if ($deleted) {
            Cache::forget('products.all');
            return response()->json(['message' => 'Deleted']);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }
}
