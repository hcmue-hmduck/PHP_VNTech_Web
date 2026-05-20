<?php

namespace App;

enum OrderStatus: string
{
    case PENDING_PAYMENT    = 'cho_thanh_toan'; // MoMo chưa thanh toán
    case PENDING_CONFIRMATION = 'cho_xac_nhan';  // Chờ admin xác nhận
    case WAITING_PICKUP     = 'da_xac_nhan';    // Đã xác nhận / Chờ lấy hàng
    case WAITING_DELIVERY   = 'dang_giao_hang'; // Đang vận chuyển / Chờ giao hàng
    case DELIVERED          = 'da_nhan_hang';   // Đã hoàn thành / Đã giao
    case CANCELLED          = 'da_huy';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
