<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchProductsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Tìm kiếm sản phẩm trong cửa hàng theo tên hoặc từ khóa (tên nhãn hàng). Trả về danh sách sản phẩm gồm mã sản phẩm, tên sản phẩm, mô tả ngắn và giá bán thấp nhất.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $tenSanPham = $request['ten_san_pham'] ?? null;
        $tenThuongHieu = $request['ten_thuong_hieu'] ?? null;
        $tenDanhMuc = $request['ten_danh_muc'] ?? null;

        // At least one search parameter must be provided
        if (empty($tenSanPham) && empty($tenThuongHieu) && empty($tenDanhMuc)) {
            return 'Vui lòng cung cấp ít nhất một tiêu chí tìm kiếm: tên sản phẩm, danh mục, hoặc thương hiệu.';
        }

        $query = Product::query();

        // If category name is provided, find category first then search by category code
        if (!empty($tenDanhMuc)) {
            $category = \App\Models\Category::where('ten_danh_muc', 'like', '%' . $tenDanhMuc . '%')->first();
            if ($category) {
                $query->where('ma_danh_muc', $category->ma_danh_muc);
            } else {
                return "Không tìm thấy danh mục: '{$tenDanhMuc}'.";
            }
        }

        // If brand name is provided, find brand first then search by brand code
        if (!empty($tenThuongHieu)) {
            $brand = \App\Models\Brand::where('ten_thuong_hieu', 'like', '%' . $tenThuongHieu . '%')->first();
            if ($brand) {
                $query->where('ma_thuong_hieu', $brand->ma_thuong_hieu);
            } else {
                return "Không tìm thấy thương hiệu: '{$tenThuongHieu}'.";
            }
        }

        // Search by product name if provided
        if (!empty($tenSanPham)) {
            $query->where('ten_san_pham', 'like', '%' . $tenSanPham . '%');
        }

        $query->limit(5);
        $products = $query->get();

        if ($products->isEmpty()) {
            $searchInfo = [];
            if (!empty($tenDanhMuc)) $searchInfo[] = "danh mục '{$tenDanhMuc}'";
            if (!empty($tenThuongHieu)) $searchInfo[] = "thương hiệu '{$tenThuongHieu}'";
            if (!empty($tenSanPham)) $searchInfo[] = "sản phẩm '{$tenSanPham}'";
            $searchStr = implode(' - ', $searchInfo);
            return "Không tìm thấy sản phẩm nào phù hợp với: {$searchStr}.";
        }

        return $products->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ten_san_pham' => $schema->string()->nullable()->description('Tên/keyword sản phẩm (tùy chọn). Chỉ dùng khi khách nêu model/từ khóa cụ thể (ví dụ: vivobook, thinkpad, gaming, i5). KHÔNG truyền "laptop/điện thoại" vào đây.'),
            'ten_danh_muc' => $schema->string()->nullable()->description('Tên danh mục (tùy chọn). Ví dụ: laptop, điện thoại, phụ kiện'),
            'ten_thuong_hieu' => $schema->string()->nullable()->description('Tên thương hiệu (tùy chọn). Ví dụ: asus, dell, hp, apple, samsung'),
        ];
    }
}
