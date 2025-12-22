<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * GET /api/orders
     * List all orders
     */
    public function index()
    {
        $orders = Order::with('orderItems.menuItem')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'count' => $orders->count()
        ]);
    }

    /**
     * POST /api/orders
     * Create a new order with items
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'channel' => 'required|string|in:dine_in,cathering,go_food,grab_food',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        // Create the order
        $order = Order::create([
            'channel' => $validated['channel'],
            'total_amount' => $validated['total_amount'],
            'status' => $validated['status']
        ]);

        // Add order items
        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        $order->load('orderItems.menuItem');

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

    /**
     * GET /api/orders/{id}
     * Get a single order with all items
     */
    public function show($id)
    {
        $order = Order::with('orderItems.menuItem')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * PUT /api/orders/{id}
     * Update order status
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'sometimes|string|in:pending,completed,cancelled',
            'total_amount' => 'sometimes|numeric|min:0'
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * DELETE /api/orders/{id}
     * Delete an order
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Delete order items first
        OrderItem::where('order_id', $order->id)->delete();
        
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }
}
