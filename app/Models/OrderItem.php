<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class OrderItem extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_don_hang',
        'ma_bien_the',
        'ten_san_pham',
        'ten_bien_the',
        'hinh_anh',
        'so_luong',
        'gia_niem_yet',
        'don_gia',
        'ma_bien_the_flash_sale',
        'so_tien_giam_flash_sale',
        'thanh_tien',
    ];

    protected $casts = [
        'so_luong'               => 'integer',
        'gia_niem_yet'           => 'decimal:0',
        'don_gia'                => 'decimal:0',
        'so_tien_giam_flash_sale'=> 'decimal:0',
        'thanh_tien'             => 'decimal:0',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'ma_bien_the', 'ma_sku');
    }
}
