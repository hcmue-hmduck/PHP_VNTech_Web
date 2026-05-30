<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FlashSaleItem;
use App\Models\Order;
use App\OrderStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller {
    public function viewAdminDashboard() {
        $deliveredOrdersCount = Order::where('trang_thai', OrderStatus::DELIVERED->value)->count();
        $pendingOrdersCount = Order::where('trang_thai', OrderStatus::PENDING_CONFIRMATION->value)->count();
        $total_sales = (float) (string) Order::where('trang_thai', OrderStatus::DELIVERED->value)->sum('tong_thanh_toan');
 
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
 
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
 
        $thisMonthTotal = (float) (string) Order::where('trang_thai', OrderStatus::DELIVERED->value)->whereBetween('updated_at', [$startOfThisMonth, $endOfThisMonth])->sum('tong_thanh_toan');
        $lastMonthTotal = (float) (string) Order::where('trang_thai', OrderStatus::DELIVERED->value)->whereBetween('updated_at', [$startOfLastMonth, $endOfLastMonth])->sum('tong_thanh_toan');
 
        if ($lastMonthTotal > 0) {
            $growth = (($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
        } else {
            $growth = $thisMonthTotal > 0 ? 100 : 0;
        }
 
        $growth = round($growth, 1);
 
        // Tính doanh thu 7 ngày gần nhất để vẽ biểu đồ
        $revenues = [];
        $labels = [];
        $dayLabels = [
            0 => 'CN',
            1 => 'T2',
            2 => 'T3',
            3 => 'T4',
            4 => 'T5',
            5 => 'T6',
            6 => 'T7',
        ];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();
            
            $dailyTotal = (float) (string) Order::where('trang_thai', OrderStatus::DELIVERED->value)
                ->whereBetween('updated_at', [$startOfDay, $endOfDay])
                ->sum('tong_thanh_toan');
                
            $revenues[] = $dailyTotal;
            $labels[] = $dayLabels[$date->dayOfWeek] . ' (' . $date->format('d/m') . ')';
        }
        $latestOrders = Order::orderBy('created_at', 'desc')->take(5)->get();
 
        $products = Product::count();
        $product_variants = ProductVariant::count();
        
        $inStockVariantsCount = ProductVariant::where('so_luong_ton_kho', '>', 0)->count();
        $inStockRate = $product_variants > 0 ? round(($inStockVariantsCount / $product_variants) * 100) : 0;
 
        $totalOrdersCount = Order::count();
        $completionRate = $totalOrdersCount > 0 ? round(($deliveredOrdersCount / $totalOrdersCount) * 100) : 0;
        $flashSaleItems = FlashSaleItem::active()->with('variant.product')->get();
        return view('adminUI.dashboardAdmin', compact(
            'products', 
            'product_variants', 
            'flashSaleItems', 
            'deliveredOrdersCount', 
            'pendingOrdersCount', 
            'total_sales',
            'totalOrdersCount',
            'completionRate',
            'inStockRate',
            'growth',
            'revenues',
            'labels',
            'latestOrders'
        ));
    }
}