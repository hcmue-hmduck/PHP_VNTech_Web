<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FlashSales;
use App\Models\FlashSaleItem;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function viewHome() {
        $products = Product::where('trang_thai', 'active')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();

        $now = now();
        $flashSales = FlashSales::where('trang_thai', 'active')
            ->where('bat_dau', '<=', $now)
            ->where('ket_thuc', '>=', $now)
            ->whereHas('flash_sale_items', function ($query) {
                $query->where('trang_thai', 'active');
            })
            ->with([
                'flash_sale_items' => function ($query) {
                    $query->where('trang_thai', 'active');
                }
            ])->get();
        return view('homeUI.home', compact('brands', 'categories', 'products', 'flashSales'));
    }
}
