<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FlashSaleItem;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function index() {
        $products = Product::latest()->take(8)->get();
        return view('home', compact('products'));
    }

    public function viewHome() {
        $products = Product::latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();

        $flashSaleItems = FlashSaleItem::active()->with('variant.product')->get();
        return view('homeUI.home', compact('brands', 'categories', 'products', 'flashSaleItems'));
    }
}
?>