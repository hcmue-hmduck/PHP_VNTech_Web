<?php

namespace App\Ai\Tools;

use App\Models\Voucher;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ListVouchersTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Lấy danh sách mã giảm giá/voucher hiện đang còn hiệu lực của VNTech. Dùng khi khách hỏi có mã giảm giá, voucher, coupon, freeship, ưu đãi giảm phí ship hoặc giảm hóa đơn. Nếu khách hỏi sản phẩm đang giảm giá/flash sale thì dùng ListFlashSaleProductsTool trước.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $loaiVoucher = $request['loai_voucher'] ?? null;

        $query = Voucher::active()
            ->orderBy('ket_thuc')
            ->select([
                'ma_voucher',
                'mo_ta',
                'loai_voucher',
                'hinh_thuc_giam',
                'gia_tri_giam',
                'muc_giam_toi_da',
                'don_hang_toi_thieu',
                'tong_luot_dung',
                'da_dung',
                'ket_thuc',
            ]);

        if (!empty($loaiVoucher)) {
            $query->where('loai_voucher', $loaiVoucher);
        }

        $vouchers = $query->get()
            ->filter(fn (Voucher $voucher) => $voucher->isAvailable())
            ->take(10)
            ->values();

        if ($vouchers->isEmpty()) {
            if (!empty($loaiVoucher)) {
                return "Hiện chưa có voucher còn hiệu lực cho loại: '{$loaiVoucher}'.";
            }

            return 'Hiện chưa có voucher/mã giảm giá nào còn hiệu lực.';
        }

        return $vouchers->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'loai_voucher' => $schema->string()->nullable()->description('Loại voucher cần lọc (tùy chọn): bill để giảm hóa đơn, shipping để giảm phí vận chuyển. Để trống khi khách hỏi chung mã giảm giá.'),
        ];
    }
}
