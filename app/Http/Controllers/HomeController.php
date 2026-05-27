<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FlashSaleItem;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function index() {
        $products = Product::where('trang_thai', 'active')->latest()->take(8)->get();
        return view('home', compact('products'));
    }

    public function viewHome() {
        $products = Product::where('trang_thai', 'active')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();

        $flashSaleItems = FlashSaleItem::active()->with('variant.product')->get();
        return view('homeUI.home', compact('brands', 'categories', 'products', 'flashSaleItems'));
    }
}
?>