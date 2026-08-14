<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get Dashboard analytics metrics.
     */
    public function getDashboardMetrics(): array
    {
        $today = Carbon::today();

        $totalSales = Sale::where('status', 'completed')->sum('grand_total');
        $totalPurchases = Purchase::sum('total_amount');
        $totalProducts = Product::count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->count();
        $totalCustomers = \App\Models\Customer::count();
        $totalSuppliers = \App\Models\Supplier::count();

        $todaySales = Sale::where('status', 'completed')
            ->whereDate('sale_date', $today)
            ->sum('grand_total');

        $todayExpenses = Expense::whereDate('expense_date', $today)->sum('amount');

        // Today COGS
        $todayCOGS = SaleItem::whereHas('sale', function ($q) use ($today) {
            $q->where('status', 'completed')->whereDate('sale_date', $today);
        })
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->sum(DB::raw('sale_items.quantity * products.purchase_price'));

        $todayProfit = max(0, $todaySales - $todayCOGS - $todayExpenses);

        return [
            'total_sales' => $totalSales,
            'total_purchases' => $totalPurchases,
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
            'total_customers' => $totalCustomers,
            'total_suppliers' => $totalSuppliers,
            'today_sales' => $todaySales,
            'today_expenses' => $todayExpenses,
            'today_profit' => $todayProfit,
        ];
    }

    /**
     * Get chart sales aggregated data for given period.
     */
    public function getSalesChartData(string $period = 'this_month'): array
    {
        $labels = [];
        $data = [];

        if ($period === 'today') {
            for ($i = 0; $i < 24; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $sales = Sale::where('status', 'completed')
                    ->whereDate('sale_date', Carbon::today())
                    ->whereRaw('HOUR(sale_date) = ?', [$i])
                    ->sum('grand_total');
                $data[] = floatval($sales);
            }
        } elseif ($period === 'this_week') {
            $startOfWeek = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i);
                $labels[] = $date->format('D, M d');
                $sales = Sale::where('status', 'completed')
                    ->whereDate('sale_date', $date->toDateString())
                    ->sum('grand_total');
                $data[] = floatval($sales);
            }
        } elseif ($period === 'this_year') {
            for ($month = 1; $month <= 12; $month++) {
                $labels[] = Carbon::create(null, $month, 1)->format('M');
                $sales = Sale::where('status', 'completed')
                    ->whereYear('sale_date', Carbon::now()->year)
                    ->whereMonth('sale_date', $month)
                    ->sum('grand_total');
                $data[] = floatval($sales);
            }
        } else {
            // Default: this_month (day by day)
            $daysInMonth = Carbon::now()->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $labels[] = "Day {$day}";
                $sales = Sale::where('status', 'completed')
                    ->whereYear('sale_date', Carbon::now()->year)
                    ->whereMonth('sale_date', Carbon::now()->month)
                    ->whereDay('sale_date', $day)
                    ->sum('grand_total');
                $data[] = floatval($sales);
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Profit Report calculation.
     */
    public function getProfitReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $salesQuery = Sale::where('status', 'completed');
        $expenseQuery = Expense::query();
        $saleItemsQuery = SaleItem::whereHas('sale', function ($q) {
            $q->where('status', 'completed');
        });

        if ($dateFrom) {
            $salesQuery->whereDate('sale_date', '>=', $dateFrom);
            $expenseQuery->whereDate('expense_date', '>=', $dateFrom);
            $saleItemsQuery->whereHas('sale', function ($q) use ($dateFrom) {
                $q->whereDate('sale_date', '>=', $dateFrom);
            });
        }
        if ($dateTo) {
            $salesQuery->whereDate('sale_date', '<=', $dateTo);
            $expenseQuery->whereDate('expense_date', '<=', $dateTo);
            $saleItemsQuery->whereHas('sale', function ($q) use ($dateTo) {
                $q->whereDate('sale_date', '<=', $dateTo);
            });
        }

        $revenue = $salesQuery->sum('grand_total');
        $expenses = $expenseQuery->sum('amount');

        $cogs = $saleItemsQuery->join('products', 'sale_items.product_id', '=', 'products.id')
            ->sum(DB::raw('sale_items.quantity * products.purchase_price'));

        $grossProfit = $revenue - $cogs;
        $netProfit = $grossProfit - $expenses;

        return [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
        ];
    }

    /**
     * Best selling products.
     */
    public function getBestSellingProducts(int $limit = 10, ?string $dateFrom = null, ?string $dateTo = null)
    {
        $query = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_revenue'))
            ->whereHas('sale', function ($q) use ($dateFrom, $dateTo) {
                $q->where('status', 'completed');
                if ($dateFrom) $q->whereDate('sale_date', '>=', $dateFrom);
                if ($dateTo) $q->whereDate('sale_date', '<=', $dateTo);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product.category')
            ->limit($limit);

        return $query->get();
    }
}
