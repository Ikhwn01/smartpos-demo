<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $metrics = $this->reportService->getDashboardMetrics();
        $period = $request->get('period', 'this_month');
        $chartData = $this->reportService->getSalesChartData($period);

        $recentSales = Sale::with(['customer', 'user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('metrics', 'chartData', 'recentSales', 'lowStockProducts', 'period'));
    }

    public function chartData(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $data = $this->reportService->getSalesChartData($period);
        return response()->json($data);
    }
}
