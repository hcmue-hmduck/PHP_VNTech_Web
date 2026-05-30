<?php

namespace App\Ai\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetOrderDetailsTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Lấy thông tin chi tiết của một đơn hàng. Nếu cung cấp mã đơn hàng (ma_don_hang), sẽ lấy đơn hàng đó. Nếu không cung cấp, sẽ lấy đơn hàng mới nhất của khách hàng hiện tại. Bao gồm thông tin người nhận, địa chỉ giao hàng, các sản phẩm trong đơn, tổng tiền, trạng thái đơn hàng và phương thức thanh toán.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $maDonHang = $request['ma_don_hang'] ?? null;
        
        // Nếu không có mã đơn hàng, lấy đơn hàng mới nhất của user
        if (empty($maDonHang)) {
            $user = Auth::user();
            
            if (!$user) {
                return 'Không thể xác định khách hàng. Vui lòng cung cấp mã đơn hàng.';
            }
            
            // Lấy đơn hàng mới nhất của user
            $order = Order::with('items')
                ->where('ma_nguoi_dung', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$order) {
                return 'Bạn chưa có đơn hàng nào.';
            }
        } else {
            // Tìm đơn hàng theo mã
            $order = Order::with('items')->where('ma_don_hang', $maDonHang)->first();
            
            if (!$order) {
                return "Không tìm thấy đơn hàng với mã: '{$maDonHang}'.";
            }
        }

        return $order->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ma_don_hang' => $schema->string()->nullable()->description('Mã đơn hàng cần lấy thông tin chi tiết. Nếu không cung cấp, sẽ lấy đơn hàng mới nhất của khách hàng.')
        ];
    }
}
