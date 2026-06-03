<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FlashSales;
use App\Models\FlashSaleItem;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductDetailController extends Controller {
    public function viewProductDetail(string $ma_san_pham) {
        $productDetail = Product::where('ma_san_pham', $ma_san_pham)->firstOrFail();
        $variants = ProductVariant::with(['activeFlashSaleItem.campaign'])
            ->where('ma_san_pham', $productDetail->ma_san_pham)
            ->get();
        $relatedProducts = Product::where('ma_danh_muc', $productDetail->ma_danh_muc)
            ->where('ma_san_pham', '!=', $productDetail->ma_san_pham)
            ->where('trang_thai', 'active')
            ->limit(4)
            ->get();

        $productCategory = Category::where('ma_danh_muc', $productDetail->ma_danh_muc)->first();
        $categoryName = $productCategory ? $productCategory->ten_danh_muc : 'Sản phẩm';

        return view('homeUI.productDetail', compact('productDetail', 'variants', 'relatedProducts', 'categoryName'));
    }
}
?>