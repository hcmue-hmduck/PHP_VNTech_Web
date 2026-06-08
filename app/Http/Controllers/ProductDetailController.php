<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FlashSales;
use App\Models\FlashSaleItem;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductDetailController extends Controller {
    public function viewProductDetail(Request $request, string $ma_san_pham, ?string $ma_bien_the = null) {
        $productDetail = Product::where('ma_san_pham', $ma_san_pham)->firstOrFail();
        
        $variants = ProductVariant::with(['activeFlashSaleItem.campaign'])
            ->where('ma_san_pham', $productDetail->ma_san_pham)
            ->get();
            
        // Pre-process display names of variants
        foreach ($variants as $variant) {
            $thong_tin_bien_the = '';
            if (isset($variant->thong_so_ky_thuat_rieng) && is_array($variant->thong_so_ky_thuat_rieng)) {
                foreach($variant->thong_so_ky_thuat_rieng as $specItem) {
                    $thong_tin_bien_the .= $specItem['gia_tri'] . '/';
                }
            } 
            $variant->thong_tin_hien_thi = rtrim($thong_tin_bien_the, '/') ?: $variant->ten_bien_the;
        }

        $selectedVariant = null;
        if ($ma_bien_the) {
            $selectedVariant = $variants->firstWhere('ma_bien_the', $ma_bien_the);
        }
        if (!$selectedVariant) {
            $selectedVariant = $variants->first();
        }
        if (!$selectedVariant) {
            abort(404, 'Product variant not found');
        }

        $flashSaleInfo = $selectedVariant->flash_sale_info;
        $flashSaleCampaign = $selectedVariant->flash_sale_campaign;
        $isFlashSaleActive = $flashSaleInfo && $flashSaleCampaign;
        
        // Calculate discount specifications
        $daBan = (int)($flashSaleInfo->so_luong_da_ban ?? 0);
        $gioiHan = max(1, (int)($flashSaleInfo->so_luong_gioi_han ?? 1));
        $soLuongFlashConLai = max(0, $gioiHan - $daBan);
        $percent = min(100, round(($daBan / $gioiHan) * 100));
        
        $endTimeStr = '';
        if ($isFlashSaleActive) {
            $endTimeStr = is_string($flashSaleCampaign->ket_thuc) 
                ? $flashSaleCampaign->ket_thuc 
                : ($flashSaleCampaign->ket_thuc instanceof \Carbon\Carbon 
                    ? $flashSaleCampaign->ket_thuc->toIso8601String() 
                    : (string)$flashSaleCampaign->ket_thuc);
        }

        $originalPrice = $selectedVariant->gia_niem_yet ?: ($selectedVariant->gia_ban * 1.25);
        $currentPrice = $isFlashSaleActive ? $flashSaleInfo->gia_flash_sale : $selectedVariant->gia_ban;
        $savingsPercent = $originalPrice > $currentPrice ? round((($originalPrice - $currentPrice) / $originalPrice) * 100) : 0;
        $tietKiemVal = $originalPrice - $currentPrice;
        
        $averageRating = (float) ($productDetail->so_sao_trung_binh ?? 0);
        $reviewsCount = (int) ($productDetail->so_luot_danh_gia ?? 0);

        // Pre-format gallery images
        $galleryImages = array_merge([$selectedVariant->link_anh_bien_the ?: $productDetail->link_anh_dai_dien], $productDetail->hinh_anh ?? []);

        // Pre-format thong_so_ky_thuat_chung
        $formattedChung = [];
        if (isset($productDetail->thong_so_ky_thuat_chung) && is_array($productDetail->thong_so_ky_thuat_chung)) {
            foreach ($productDetail->thong_so_ky_thuat_chung as $row) {
                $rowTen = is_array($row) ? ($row['ten'] ?? '') : ($row->ten ?? '');
                $rowVal = is_array($row) ? ($row['gia_tri'] ?? '') : ($row->gia_tri ?? '');
                if (!empty($rowTen) || !empty($rowVal)) {
                    $formattedChung[] = ['ten' => $rowTen, 'gia_tri' => $rowVal];
                }
            }
        }

        // Pre-format thong_so_ky_thuat_rieng for selected variant
        $formattedRieng = [];
        if (isset($selectedVariant->thong_so_ky_thuat_rieng) && is_array($selectedVariant->thong_so_ky_thuat_rieng)) {
            foreach ($selectedVariant->thong_so_ky_thuat_rieng as $spec) {
                $specTen = is_array($spec) ? ($spec['ten'] ?? '') : ($spec->ten ?? '');
                $specVal = is_array($spec) ? ($spec['gia_tri'] ?? '') : ($spec->gia_tri ?? '');
                if (!empty($specTen) || !empty($specVal)) {
                    $formattedRieng[] = ['ten' => $specTen, 'gia_tri' => $specVal];
                }
            }
        }

        // Pre-format thong_tin_them
        $formattedThem = [];
        if (isset($productDetail->thong_tin_them) && is_array($productDetail->thong_tin_them)) {
            foreach ($productDetail->thong_tin_them as $row) {
                $rowTen = is_array($row) ? ($row['ten'] ?? '') : ($row->ten ?? '');
                $rowVal = is_array($row) ? ($row['gia_tri'] ?? '') : ($row->gia_tri ?? '');
                if (!empty($rowTen) || !empty($rowVal)) {
                    $formattedThem[] = ['ten' => $rowTen, 'gia_tri' => $rowVal];
                }
            }
        }
        $hasThongTinThem = !empty($formattedThem);

        // Fetch related products and pre-process their prices
        $relatedProducts = Product::with('variants')
            ->where('ma_danh_muc', $productDetail->ma_danh_muc)
            ->where('ma_san_pham', '!=', $productDetail->ma_san_pham)
            ->where('trang_thai', 'active')
            ->limit(4)
            ->get();
        foreach ($relatedProducts as $prod) {
            $prod->original_price = $prod->gia_thap_nhat * 1.25;
            $prod->current_price = $prod->gia_thap_nhat;
            $firstVariant = $prod->variants->first();
            $prod->default_ma_bien_the = $firstVariant ? $firstVariant->ma_bien_the : 'default';
        }

        $productCategory = Category::where('ma_danh_muc', $productDetail->ma_danh_muc)->first();
        $categoryName = $productCategory ? $productCategory->ten_danh_muc : 'Sản phẩm';

        return view('homeUI.productDetail', compact(
            'productDetail', 'variants', 'selectedVariant', 'flashSaleInfo', 'flashSaleCampaign',
            'isFlashSaleActive', 'daBan', 'gioiHan', 'soLuongFlashConLai', 'percent', 'endTimeStr',
            'originalPrice', 'currentPrice', 'savingsPercent', 'tietKiemVal', 'averageRating',
            'reviewsCount', 'galleryImages', 'formattedChung', 'formattedRieng', 'formattedThem',
            'hasThongTinThem', 'relatedProducts', 'categoryName'
        ));
    }
}