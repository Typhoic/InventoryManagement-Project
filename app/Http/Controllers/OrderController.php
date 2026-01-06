<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
     /**
     * Display list of all orders
     */
    public function index(Request $request)
    {
        $query = Order::with('orderItems.menuItem');
    
        // Filter by channel
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }
    
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Sort
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'date_newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'date_oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_low':
                    $query->orderBy('total_amount', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('total_amount', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Default: newest first
        }
    
        $orders = $query->get();
    
        return view('salesorderclicked', compact('orders'));
    }

    /**
     * Show form for creating new order
     */
    public function create()
    {
        $menuItems = MenuItem::active()->get();
        $channels = Order::CHANNELS;

        return view('orders.create', compact('menuItems', 'channels'));
    }

    /**
     * Store a new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'channel' => 'required|in:dine_in,catering,go_food,grab_food',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Check ingredient availability
        foreach ($validated['items'] as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            if (!$menuItem->canMake($item['quantity'])) {
                return back()
                    ->withInput()
                    ->withErrors(['items' => "Not enough ingredients for {$menuItem->name}"]);
            }
        }

        DB::beginTransaction();

        try {
            // Calculate total amount
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                $totalAmount += $menuItem->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'channel' => $validated['channel'],
                'total_amount' => $totalAmount,
                'status' => 'completed',
            ]);

            // Create order items (this will auto-reduce ingredients via boot method)
            foreach ($validated['items'] as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                ]);
            }

            DB::commit();

            return redirect()->route('salesorderclicked')
                ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create order. Please try again.']);
        }
    }

    /**
     * Display single order details
     */
    public function show(Order $order)
    {
        $order->load('orderItems.menuItem');
        return view('orders.show', compact('order'));
    }

    /**
     * Delete an order
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('salesorderclicked')
            ->with('success', 'Order deleted successfully!');
    }
}
