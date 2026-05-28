<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FlashSales;
use App\Models\FlashSaleItem;
use Illuminate\Http\Request;

class ProductDetailController extends Controller {
    public function viewProductDetail(string $ma_san_pham) {
        $productDetail = Product::where('ma_san_pham', $ma_san_pham)->firstOrFail();
        $variants = ProductVariant::where('ma_san_pham', $productDetail->ma_san_pham)->get();

        // Lấy thông tin flash sale đang hoạt động
        $now = now();
        $activeCampaigns = FlashSales::where('trang_thai', 'active')
            ->where('bat_dau', '<=', $now)
            ->where('ket_thuc', '>=', $now)
            ->get();

        $activeCampaignIds = $activeCampaigns->pluck('ma_flash_sales');
        $variantIds = $variants->pluck('ma_bien_the');

        $flashSaleItems = FlashSaleItem::whereIn('ma_flash_sales', $activeCampaignIds)
            ->whereIn('ma_bien_the', $variantIds)
            ->where('trang_thai', 'active')
            ->get()
            ->keyBy('ma_bien_the');

        foreach ($variants as $variant) {
            /** @var ProductVariant $variant */
            $flashItem = $flashSaleItems->get($variant->ma_bien_the);
            if ($flashItem) {
                $variant->setAttribute('flash_sale_info', $flashItem);
                $variant->setAttribute('flash_sale_campaign', $activeCampaigns->firstWhere('ma_flash_sales', $flashItem->ma_flash_sales));
            } else {
                $variant->setAttribute('flash_sale_info', null);
                $variant->setAttribute('flash_sale_campaign', null);
            }
        }
        return view('homeUI.productDetail', compact('productDetail', 'variants'));
    }
}
?>