<?php

namespace App\Ai\Tools;

use App\Models\Category;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ListCategoriesTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Lấy danh sách danh mục sản phẩm mà cửa hàng VNTech đang bán. Dùng khi khách hỏi cửa hàng có bán gì, shop có những mặt hàng/danh mục nào, có bán laptop/điện thoại/phụ kiện không, hoặc cần xem các nhóm sản phẩm trước khi tư vấn.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $tuKhoa = $request['tu_khoa'] ?? null;

        $query = Category::query()
            ->where('trang_thai', 'active')
            ->orderBy('ten_danh_muc')
            ->select([
                'ma_danh_muc',
                'ma_danh_muc_cha',
                'ten_danh_muc',
            ]);

        if (!empty($tuKhoa)) {
            $query->where('ten_danh_muc', 'like', '%' . $tuKhoa . '%');
        }

        $categories = $query->get();

        if ($categories->isEmpty()) {
            if (!empty($tuKhoa)) {
                return "Không tìm thấy danh mục nào phù hợp với: '{$tuKhoa}'.";
            }

            return 'Hiện chưa có dữ liệu danh mục sản phẩm đang bán.';
        }

        return $categories->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'tu_khoa' => $schema->string()->nullable()->description('Từ khóa lọc danh mục (tùy chọn). Ví dụ: laptop, điện thoại, phụ kiện. Để trống khi khách hỏi chung cửa hàng có bán gì.'),
        ];
    }
}
