<?php

namespace App\Ai\Tools;

use App\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ListMyOrdersTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Lấy danh sách đơn hàng gần đây của khách hàng đang đăng nhập. Dùng khi khách hỏi tôi có những đơn hàng nào, liệt kê đơn hàng của tôi, đơn gần đây, lịch sử mua hàng.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $user = Auth::user();

        if (!$user) {
            return 'Không thể xác định khách hàng. Vui lòng đăng nhập để xem danh sách đơn hàng.';
        }

        $trangThai = $request['trang_thai'] ?? null;
        $limit = (int) ($request['limit'] ?? 5);
        $limit = max(1, min($limit, 10));

        $query = Order::query()
            ->where('ma_nguoi_dung', $user->id)
            ->orderBy('created_at', 'desc')
            ->select([
                'ma_don_hang',
                'tong_tien_hang',
                'phi_van_chuyen',
                'gia_tri_giam_voucher',
                'tong_thanh_toan',
                'phuong_thuc_thanh_toan',
                'trang_thai',
                'created_at',
            ]);

        if (!empty($trangThai)) {
            if (!in_array($trangThai, OrderStatus::values(), true)) {
                return 'Trạng thái đơn hàng không hợp lệ. Các trạng thái hợp lệ gồm: ' . implode(', ', OrderStatus::values()) . '.';
            }

            $query->where('trang_thai', $trangThai);
        }

        $orders = $query->limit($limit)->get();

        if ($orders->isEmpty()) {
            if (!empty($trangThai)) {
                return "Bạn chưa có đơn hàng nào ở trạng thái: '{$trangThai}'.";
            }

            return 'Bạn chưa có đơn hàng nào.';
        }

        return $orders->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        $orderStatusValues = implode(', ', OrderStatus::values());

        return [
            'trang_thai' => $schema->string()->nullable()->description("Trạng thái đơn hàng cần lọc (tùy chọn). Giá trị hợp lệ: {$orderStatusValues}."),
            'limit' => $schema->integer()->nullable()->description('Số đơn hàng gần đây muốn lấy, tối đa 10. Mặc định là 5.'),
        ];
    }
}
