<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\WeeklyReportItem;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    /**
     * Get chart data for daily sales (last 7 days)
     * Response: { labels: [dates], data: [amounts], units: [quantities] }
     */
    public function dailySales()
    {
        $past7Days = SaleItem::selectRaw('DATE(created_at) as date, SUM(quantity) as total_qty, SUM(quantity * unit_price) as total_amount')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $dataAmounts = [];
        $dataUnits = [];

        foreach ($past7Days as $row) {
            $labels[] = $row->date;
            $dataAmounts[] = (float) $row->total_amount;
            $dataUnits[] = (int) $row->total_qty;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $dataAmounts,
            'units' => $dataUnits,
        ]);
    }

    /**
     * Get chart data for weekly sales (last 8 weeks)
     * Response: { labels: [week_starts], data: [amounts] }
     */
    public function weeklySales()
    {
        $past8Weeks = WeeklyReportItem::selectRaw('DATE_SUB(DATE(created_at), INTERVAL DAYOFWEEK(created_at)-1 DAY) as week_start, SUM(quantity * unit_price) as total_amount')
            ->where('created_at', '>=', now()->subWeeks(8))
            ->groupBy('week_start')
            ->orderBy('week_start')
            ->get();

        $labels = [];
        $data = [];

        foreach ($past8Weeks as $row) {
            $labels[] = $row->week_start;
            $data[] = (float) $row->total_amount;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * Get chart data for product mix (top 5 products by quantity sold)
     * Response: { labels: [product_names], data: [quantities] }
     */
    public function productMix()
    {
        $topProducts = SaleItem::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_qty')
            ->where('created_at', '>=', now()->subWeeks(4))
            ->groupBy('product_id')
            ->orderByRaw('total_qty DESC')
            ->limit(5)
            ->get();

        $labels = [];
        $data = [];

        foreach ($topProducts as $row) {
            if ($row->product) {
                $labels[] = $row->product->name;
                $data[] = (int) $row->total_qty;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
