<?php

namespace App\Ai\Tools;

use App\Models\FlashSaleItem;
use App\Models\FlashSales;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ListFlashSaleProductsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'BẮT BUỘC dùng tool này để lấy dữ liệu mới nhất khi khách hỏi hoặc yêu cầu kiểm tra lại sản phẩm đang giảm giá, flash sale, sale, khuyến mãi giờ vàng. Ví dụ: "kiểm tra lại xem có sản phẩm nào đang giảm giá không", "shop đang sale gì", "sản phẩm nào giảm giá".';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $maFlashSales = $request['ma_flash_sales'] ?? null;
        $activeStatuses = ['active', 'ACTIVE'];

        $flashSalesQuery = FlashSales::query()
            ->whereIn('trang_thai', $activeStatuses)
            ->where('bat_dau', '<=', now())
            ->where('ket_thuc', '>=', now())
            ->select([
                'ma_flash_sales',
                'ten_flash_sales',
                'mo_ta',
                'bat_dau',
                'ket_thuc',
            ]);

        if (!empty($maFlashSales)) {
            $flashSalesQuery->where('ma_flash_sales', $maFlashSales);
        }

        $flashSales = $flashSalesQuery->get();

        if ($flashSales->isEmpty()) {
            return 'Hiện chưa có chương trình flash sale nào đang diễn ra.';
        }

        $flashSaleMap = $flashSales->keyBy('ma_flash_sales');

        $itemsQuery = FlashSaleItem::with('variant.product')
            ->whereIn('trang_thai', $activeStatuses)
            ->whereIn('ma_flash_sales', $flashSaleMap->keys()->all());

        $flashSaleItems = $itemsQuery->limit(10)->get();

        if ($flashSaleItems->isEmpty()) {
            $flashSaleItems = FlashSaleItem::with('variant.product')
                ->whereIn('trang_thai', $activeStatuses)
                ->where('bat_dau', '<=', now())
                ->where('ket_thuc', '>=', now())
                ->limit(10)
                ->get();
        }

        $items = $flashSaleItems
            ->map(function (FlashSaleItem $item) use ($flashSaleMap) {
                $variant = $item->variant;
                $product = $variant?->product;
                $flashSale = $flashSaleMap->get($item->ma_flash_sales);

                return [
                    'ma_flash_sales' => $item->ma_flash_sales,
                    'ten_flash_sales' => $flashSale?->ten_flash_sales,
                    'ma_san_pham' => $product?->ma_san_pham,
                    'ten_san_pham' => $product?->ten_san_pham,
                    'ma_bien_the' => $item->ma_bien_the,
                    'ten_bien_the' => $variant?->ten_bien_the,
                    'ten_hien_thi' => $variant?->ten_hien_thi,
                    'gia_niem_yet' => $variant?->gia_niem_yet,
                    'gia_ban' => $variant?->gia_ban,
                    'gia_flash_sale' => $item->gia_flash_sale,
                    'so_luong_con_lai' => max(0, (int) $item->so_luong_gioi_han - (int) $item->so_luong_da_ban),
                    'gioi_han_moi_nguoi' => $item->gioi_han_moi_nguoi,
                    'ket_thuc' => $flashSale?->ket_thuc ?? $item->ket_thuc,
                ];
            })
            ->filter(fn (array $item) => $item['so_luong_con_lai'] > 0)
            ->values();

        if ($items->isEmpty()) {
            return 'Hiện chưa có sản phẩm flash sale nào còn hàng trong chương trình đang diễn ra.';
        }

        return $items->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ma_flash_sales' => $schema->string()->nullable()->description('Mã chương trình flash sale cần lọc (tùy chọn). Để trống khi khách hỏi chung sản phẩm đang sale/khuyến mãi.'),
        ];
    }
}
