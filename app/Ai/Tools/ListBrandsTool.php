<?php

namespace App\Ai\Tools;

use App\Models\Brand;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ListBrandsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Lấy danh sách thương hiệu mà cửa hàng VNTech đang kinh doanh. Dùng khi khách hỏi shop có những hãng nào, có bán Asus/Dell/Apple/Samsung không, hoặc cần xem thương hiệu trước khi tư vấn sản phẩm.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $tuKhoa = $request['tu_khoa'] ?? null;

        $query = Brand::query()
            ->where('trang_thai', 'active')
            ->orderBy('ten_thuong_hieu')
            ->select([
                'ma_thuong_hieu',
                'ten_thuong_hieu',
                'mo_ta',
            ]);

        if (!empty($tuKhoa)) {
            $query->where('ten_thuong_hieu', 'like', '%' . $tuKhoa . '%');
        }

        $brands = $query->get();

        if ($brands->isEmpty()) {
            if (!empty($tuKhoa)) {
                return "Không tìm thấy thương hiệu nào phù hợp với: '{$tuKhoa}'.";
            }

            return 'Hiện chưa có dữ liệu thương hiệu đang kinh doanh.';
        }

        return $brands->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'tu_khoa' => $schema->string()->nullable()->description('Tên thương hiệu cần kiểm tra/lọc (tùy chọn). Ví dụ: Asus, Dell, Apple, Samsung. Để trống khi khách hỏi chung shop có những hãng nào.'),
        ];
    }
}
