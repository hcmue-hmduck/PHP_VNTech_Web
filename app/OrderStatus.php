<?php

namespace App;

enum OrderStatus: string
{
    case PENDING_PAYMENT = 'cho_thanh_toan';
    case PENDING_CONFIRMATION = 'cho_xac_nhan';
    case WAITING_PICKUP = 'cho_lay_hang';
    case WAITING_DELIVERY = 'cho_giao_hang';
    case DELIVERED = 'da_giao';
    // case CANCELLED = 'da_huy';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
