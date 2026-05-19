<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductDetailController extends Controller {
    public function viewProductDetail(string $ma_san_pham) {
        $productDetail = Product::where('ma_san_pham', $ma_san_pham)->firstOrFail();
        $variants = ProductVariant::where('ma_san_pham', $productDetail->ma_san_pham)->get();
        return view('homeUI.productDetail', compact('productDetail', 'variants'));
    }
}
?>