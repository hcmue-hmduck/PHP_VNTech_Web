<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FlashSaleItem;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function viewAdminDashboard() {
        $products = Product::latest()->take(20)->get();
        $flashSaleItems = FlashSaleItem::active()->with('variant.product')->get();
        return view('adminUI.dashboardAdmin', compact('products', 'flashSaleItems'));
    }
}
?>