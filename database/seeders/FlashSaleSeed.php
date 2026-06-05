<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FlashSales;
use App\Models\FlashSaleItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;

class FlashSaleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlashSales::query()->delete();
        FlashSaleItem::query()->delete();

        $categories = Category::all();
        $selectedCategories = [];
        foreach ($categories as $cat) {
            $productCount = Product::where('ma_danh_muc', $cat->ma_danh_muc)
                ->where('trang_thai', 'active')
                ->count();
            if ($productCount >= 2) {
                $selectedCategories[] = $cat->ma_danh_muc;
            }
            if (count($selectedCategories) >= 5) {
                break;
            }
        }

        if (count($selectedCategories) < 5) {
            $selectedCategories = Product::where('trang_thai', 'active')
                ->distinct('ma_danh_muc')
                ->take(5)
                ->pluck('ma_danh_muc')
                ->toArray();
        }

        if (empty($selectedCategories)) {
            $selectedCategories = Category::take(5)->pluck('ma_danh_muc')->toArray();
        }

        $campaign1 = FlashSales::create([
            'ma_flash_sales' => 'temp',
            'ten_flash_sales' => 'Flash Sale Giờ Vàng',
            'mo_ta' => 'Siêu sale công nghệ giá sốc hàng tuần cùng VNTech.',
            'bat_dau' => now()->subHours(2),
            'ket_thuc' => now()->addDays(2),
            'trang_thai' => 'active',
        ]);
        $campaign1->update(['ma_flash_sales' => (string)$campaign1->_id]);

        $campaign2 = FlashSales::create([
            'ma_flash_sales' => 'temp',
            'ten_flash_sales' => 'Đại Tiệc Công Nghệ Cuối Tuần',
            'mo_ta' => 'Xả kho giá gốc cực hấp dẫn các thiết bị bán chạy.',
            'bat_dau' => now()->subHours(1),
            'ket_thuc' => now()->addDays(3),
            'trang_thai' => 'active',
        ]);
        $campaign2->update(['ma_flash_sales' => (string)$campaign2->_id]);

        // Select products for Campaign 1
        $variantsCampaign1 = [];
        // Select products for Campaign 2
        $variantsCampaign2 = [];

        foreach ($selectedCategories as $catId) {
            // Get active products in this category
            $products = Product::where('ma_danh_muc', $catId)
                ->where('trang_thai', 'active')
                ->get();

            if ($products->count() >= 1) {
                $p1 = $products->first();
                $v1 = ProductVariant::where('ma_san_pham', $p1->ma_san_pham)->where('trang_thai', 'active')->first();
                if ($v1) {
                    $variantsCampaign1[] = $v1;
                }
            }

            if ($products->count() >= 2) {
                $p2 = $products->get(1);
                $v2 = ProductVariant::where('ma_san_pham', $p2->ma_san_pham)->where('trang_thai', 'active')->first();
                if ($v2) {
                    $variantsCampaign2[] = $v2;
                }
            } else if ($products->count() >= 1) {
                // Fallback: get another variant of the same product
                $p1 = $products->first();
                $v2 = ProductVariant::where('ma_san_pham', $p1->ma_san_pham)->where('trang_thai', 'active')->skip(1)->first();
                if ($v2) {
                    $variantsCampaign2[] = $v2;
                } else {
                    // Fallback to any active variant not in campaign 1
                    $alreadyInC1 = collect($variantsCampaign1)->pluck('ma_bien_the')->toArray();
                    $vFallback = ProductVariant::where('trang_thai', 'active')
                        ->whereNotIn('ma_bien_the', $alreadyInC1)
                        ->first();
                    if ($vFallback) {
                        $variantsCampaign2[] = $vFallback;
                    }
                }
            }
        }

        $variantsCampaign1 = array_slice($variantsCampaign1, 0, 5);
        $variantsCampaign2 = array_slice($variantsCampaign2, 0, 5);

        foreach ($variantsCampaign1 as $variant) {
            $discountPrice = round($variant->gia_ban * 0.85); // 15% discount
            $item = FlashSaleItem::create([
                'ma_chi_tiet_flash_sales' => 'temp',
                'ma_flash_sales' => $campaign1->ma_flash_sales,
                'ma_bien_the' => $variant->ma_bien_the,
                'gia_flash_sale' => $discountPrice,
                'so_luong_gioi_han' => rand(20, 50),
                'so_luong_da_ban' => rand(5, 15),
                'gioi_han_moi_nguoi' => 2,
                'trang_thai' => 'active',
            ]);
            $item->update(['ma_chi_tiet_flash_sales' => (string) $item->_id]);
        }

        foreach ($variantsCampaign2 as $variant) {
            $discountPrice = round($variant->gia_ban * 0.90); // 10% discount
            $item = FlashSaleItem::create([
                'ma_chi_tiet_flash_sales' => 'temp',
                'ma_flash_sales' => $campaign2->ma_flash_sales,
                'ma_bien_the' => $variant->ma_bien_the,
                'gia_flash_sale' => $discountPrice,
                'so_luong_gioi_han' => rand(30, 60),
                'so_luong_da_ban' => rand(2, 10),
                'gioi_han_moi_nguoi' => 3,
                'trang_thai' => 'active',
            ]);
            $item->update(['ma_chi_tiet_flash_sales' => (string) $item->_id]);
        }
    }
}
