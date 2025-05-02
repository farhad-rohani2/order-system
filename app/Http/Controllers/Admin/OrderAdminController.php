<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderAdminController extends Controller
{
    public function __construct(protected OrderRepositoryInterface $orderRepo) {}

    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = $this->orderRepo->query();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->with(['user', 'items.product'])->latest()->paginate(10);

        return response()->json($orders);
    }

    public function updateStatus(int $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = $this->orderRepo->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated']);
    }
}
