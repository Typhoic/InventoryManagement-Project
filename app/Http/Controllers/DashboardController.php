<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Ingredient;
use App\Models\IngredientGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter from request (default: 'today')
        $filter = $request->get('filter', 'today');
        
        // Calculate date range based on filter
        $query = Order::where('status', 'completed');
        
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                $periodLabel = 'Today - ' . Carbon::today()->format('F j, Y');
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                $periodLabel = 'This Week (' . Carbon::now()->startOfWeek()->format('M j') . ' - ' . Carbon::now()->endOfWeek()->format('M j, Y') . ')';
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                $periodLabel = Carbon::now()->format('F Y');
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                $periodLabel = Carbon::now()->format('Y');
                break;
            case 'all':
            default:
                // No filter - all time
                $periodLabel = 'All Time';
                break;
        }

        // Sales Order Data 
        $totalOrders = (clone $query)->count();
        $dineIn = (clone $query)->where('channel', 'dine_in')->count();
        $catering = (clone $query)->where('channel', 'catering')->count();
        $goFood = (clone $query)->where('channel', 'go_food')->count();
        $grabFood = (clone $query)->where('channel', 'grab_food')->count();

        // Item Details Data (always show current stock)
        $allItems = Ingredient::count();
        $lowStockItems = Ingredient::lowStock()->count();
        $itemGroups = IngredientGroup::count();
        
        // Calculate active items percentage
        $activeItemsCount = Ingredient::where('current_stock', '>', 0)->count();
        $activeItemsPercentage = $allItems > 0 ? round(($activeItemsCount / $allItems) * 100) : 0;

        // Top Selling Menu 
        $topSelling = $this->getTopSellingMenu($filter);

        // Sales Summary 
        $totalSales = (clone $query)->sum('total_amount');
        
        // Chart Data
        $salesChartData = $this->getSalesChartData($filter);

        return view('dashboard', compact(
            'totalOrders', 'dineIn', 'catering', 'goFood', 'grabFood',
            'allItems', 'lowStockItems', 'itemGroups', 'activeItemsPercentage',
            'topSelling', 'totalSales', 'salesChartData',
            'filter', 'periodLabel'
        ));
    }

    /**
     * Get top 5 selling menu items
     */
    private function getTopSellingMenu(string $filter)
    {
        $query = MenuItem::select(
                'menu_items.id',
                'menu_items.name',
                'menu_items.price',
                'menu_items.image_url',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            )
            ->leftJoin('order_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.status', '=', 'completed');
            });

        // Apply date filter
        switch ($filter) {
            case 'today':
                $query->whereDate('orders.created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('orders.created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth('orders.created_at', Carbon::now()->month)
                      ->whereYear('orders.created_at', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('orders.created_at', Carbon::now()->year);
                break;
        }

        return $query
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.price', 'menu_items.image_url')
            ->orderBy('total_sold', 'DESC')
            ->limit(5)
            ->get();
    }

    /**
     * Get sales data for chart
     */
    private function getSalesChartData(string $filter): array
    {
        $query = Order::where('status', 'completed');

        switch ($filter) {
            case 'today':
                $data = (clone $query)
                    ->whereDate('created_at', Carbon::today())
                    ->selectRaw('HOUR(created_at) as label, SUM(total_amount) as total')
                    ->groupByRaw('HOUR(created_at)')
                    ->orderByRaw('HOUR(created_at)')
                    ->get();
                    
                // Format hour labels
                $data = $data->map(function ($item) {
                    $item->label = sprintf('%02d:00', $item->label);
                    return $item;
                });
                break;

            case 'week':
                $data = (clone $query)
                    ->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ])
                    ->selectRaw('DATE(created_at) as label, SUM(total_amount) as total')
                    ->groupByRaw('DATE(created_at)')
                    ->orderByRaw('DATE(created_at)')
                    ->get();
                    
                // Format date labels
                $data = $data->map(function ($item) {
                    $item->label = Carbon::parse($item->label)->format('D, M j');
                    return $item;
                });
                break;

            case 'month':
                $data = (clone $query)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->selectRaw('DATE(created_at) as label, SUM(total_amount) as total')
                    ->groupByRaw('DATE(created_at)')
                    ->orderByRaw('DATE(created_at)')
                    ->get();
                    
                // Format date labels
                $data = $data->map(function ($item) {
                    $item->label = Carbon::parse($item->label)->format('M j');
                    return $item;
                });
                break;

            case 'year':
                $data = (clone $query)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->selectRaw('MONTH(created_at) as label, SUM(total_amount) as total')
                    ->groupByRaw('MONTH(created_at)')
                    ->orderByRaw('MONTH(created_at)')
                    ->get();
                    
                // Format month labels
                $data = $data->map(function ($item) {
                    $item->label = Carbon::create()->month($item->label)->format('M');
                    return $item;
                });
                break;

            default: // 'all'
                $data = (clone $query)
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as label, SUM(total_amount) as total')
                    ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
                    ->orderByRaw('DATE_FORMAT(created_at, "%Y-%m")')
                    ->get();
                    
                // Format month-year labels
                $data = $data->map(function ($item) {
                    $item->label = Carbon::parse($item->label . '-01')->format('M Y');
                    return $item;
                });
                break;
        }

        return [
            'labels' => $data->pluck('label')->toArray(),
            'values' => $data->pluck('total')->toArray(),
        ];
    }
}