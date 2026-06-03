<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FlashSales;
use App\Models\FlashSaleItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\BannerImage;
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function viewHome() {
        $products = Product::where('trang_thai', 'active')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $banner_images = BannerImage::latest()->get();

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
        return view('homeUI.home', compact('brands', 'categories', 'products', 'flashSales', 'banner_images'));
    }

    public function viewHomeProducts() {
        $products = Product::where('trang_thai', 'active')->latest()->get();
        $categories = Category::latest()->get();
        $brands = Brand::latest()->get();
        return view('homeUI.listProduct', compact('products', 'categories', 'brands'));
    }

    public function viewHomeNews() {
        return view('homeUI.news');
    }
}
