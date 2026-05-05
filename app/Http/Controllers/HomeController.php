<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FlashSaleItem;
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function index() {
        $products = Product::latest()->take(8)->get();
        return view('home', compact('products'));
    }

    public function viewHome() {
        $products = Product::latest()->take(20)->get();
        $flashSaleItems = FlashSaleItem::active()->with('variant.product')->get();
        return view('homeUI.home', compact('products', 'flashSaleItems'));
    }
}
?>