<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class OrderItem extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_chi_tiet_don_hang',
        'ma_flash_sales',
        'ma_don_hang',
        'ma_bien_the',
        'ten_bien_the',
        'link_anh_dai_dien',
        'so_luong',
        'gia_ban',
        'thanh_tien',
    ];

    protected $casts = [
        'so_luong'               => 'integer',
        'gia_ban'                => 'decimal:0',
        'thanh_tien'             => 'decimal:0',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'ma_bien_the', 'ma_bien_the');
    }
}
