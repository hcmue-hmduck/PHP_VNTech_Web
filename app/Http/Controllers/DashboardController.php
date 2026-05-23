<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FlashSaleItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    private function totalSale(Collection $orders) {
        $total_sale = 0;
        foreach ($orders as $order) {
            $total_sale += $order['tong_tien_hang'];
        }
        return $total_sale;
    }
    public function viewAdminDashboard() {
        $orders = Order::latest()->get();
        $total_sales = $this->totalSale($orders);
        $products = Product::latest()->take(20)->get();
        $flashSaleItems = FlashSaleItem::active()->with('variant.product')->get();
        return view('adminUI.dashboardAdmin', compact('products', 'flashSaleItems', 'orders', 'total_sales'));
    }
}
?>