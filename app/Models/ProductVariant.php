<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ProductVariant extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_san_pham',
        'ma_bien_the',
        'ten_bien_the',
        'ma_sku',
        'gia_ban',
        'gia_niem_yet',
        'so_luong_ton_kho',
        'thuoc_tinh',
        'link_anh_bien_the',
        'trang_thai',
    ];

    protected $casts = [
        'gia_ban'          => 'decimal:0',
        'gia_niem_yet'     => 'decimal:0',
        'so_luong_ton_kho' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ma_san_pham', 'ma_san_pham');
    }
}
