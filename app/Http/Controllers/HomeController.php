<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductVariant;
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

        // 1. Lấy danh sách các biến thể bán chạy nhất (sắp xếp theo da_ban giảm dần)
        $bestSellerVariants = ProductVariant::with('product')
            ->where('trang_thai', 'active')
            ->whereHas('product', function($q) {
                $q->where('trang_thai', 'active');
            })
            ->orderBy('da_ban', 'desc')
            ->take(40)
            ->get();

        // 2. Bản đồ danh mục đầy đủ để tra tên danh mục nhanh
        $categoriesMap = [];
        foreach($categories as $cat) {
            $categoriesMap[(string)$cat->ma_danh_muc] = $cat->ten_danh_muc;
        }

        $promoLabels = [
            ['text' => 'Trả Góp 0%', 'bg' => 'bg-amber-500 text-white'],
            ['text' => 'Bảo Hành 24T', 'bg' => 'bg-[#0058bc] text-white'],
            ['text' => 'Chính Hãng 100%', 'bg' => 'bg-emerald-600 text-white'],
            ['text' => 'Freeship Toàn Quốc', 'bg' => 'bg-[#ff5c00] text-white'],
        ];

        // 3. Thu thập danh mục thực tế và danh sách biến thể bán chạy
        $bestSellerCategoriesMap = [];
        $categoryCounts = [];
        $bestSellerProductsList = [];

        foreach($bestSellerVariants as $index => $variant) {
            $prod = $variant->product;
            if (!$prod) continue;

            $maDanhMuc = (string)$prod->ma_danh_muc;
            $categoryName = $categoriesMap[$maDanhMuc] ?? 'Khác';

            // Đếm số lượng xuất hiện để lọc ra các danh mục hot nhất
            $categoryCounts[$maDanhMuc] = ($categoryCounts[$maDanhMuc] ?? 0) + 1;

            // Ghi nhận danh mục này xuất hiện trong top biến thể bán chạy
            if (!isset($bestSellerCategoriesMap[$maDanhMuc])) {
                $bestSellerCategoriesMap[$maDanhMuc] = $categoryName;
            }

            $selectedLabel = $promoLabels[$index % count($promoLabels)];
            $bestSellerProductsList[] = [
                'ma_san_pham' => (string)$prod->ma_san_pham,
                'ma_bien_the' => (string)$variant->ma_bien_the,
                'name' => trim((string)$prod->ten_san_pham . ' ' . (string)$variant->ten_bien_the),
                'ma_danh_muc' => $maDanhMuc,
                'category' => (string)$categoryName,
                'mo_ta_ngan' => (string)($prod->mo_ta_ngan ?? 'Chưa có mô tả ngắn cho sản phẩm này.'),
                'price' => (int)$variant->gia_ban,
                'originalPrice' => (int)$variant->gia_niem_yet,
                'image' => (string)($variant->link_anh_bien_the ?: ($prod->link_anh_dai_dien ?: asset('images/no-image.png'))),
                'promoText' => $selectedLabel['text'],
                'promoBg' => $selectedLabel['bg'],
                'rating' => $prod->so_sao_trung_binh ?? 0,
                'reviewsCount' => $prod->so_luot_danh_gia ?? 0,
                'da_ban' => (int)($variant->da_ban ?? 0),
            ];
        }

        // Sắp xếp các danh mục theo độ phổ biến giảm dần và lấy tối đa 5 danh mục hot nhất
        arsort($categoryCounts);
        $topCategoryIds = array_slice(array_keys($categoryCounts), 0, 6, true);

        // 4. Xây dựng danh sách danh mục tab bán chạy từ các danh mục hot nhất
        $bestSellerCategoriesList = [['id' => 'all', 'name' => 'Tất cả']];
        foreach ($topCategoryIds as $id) {
            if (isset($bestSellerCategoriesMap[$id])) {
                $bestSellerCategoriesList[] = [
                    'id' => $id,
                    'name' => $bestSellerCategoriesMap[$id]
                ];
            }
        }

        return view('homeUI.home', compact(
            'brands', 'categories', 'products', 'flashSales', 'banner_images',
            'bestSellerCategoriesList', 'bestSellerProductsList'
        ));
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

    public function searchSuggest(Request $request) {
        $query = $request->query('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('ten_san_pham', 'LIKE', '%' . $query . '%')
            ->where('trang_thai', 'active')
            ->limit(6)
            ->get();
            
        $result = $products->map(function ($product) {
            return [
                'ten_san_pham' => $product->ten_san_pham,
                'link_anh_dai_dien' => $product->link_anh_dai_dien,
                'gia_thap_nhat' => $product->gia_thap_nhat,
                'url' => route('home.product_detail', ['ma_san_pham' => $product->ma_san_pham])
            ];
        });
        return response()->json($result);
    }
}
