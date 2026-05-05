<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Product;

#[Fillable([
    'ma_san_pham',
    'ma_sku',
    'gia_ban',
    'gia_niem_yet',
    'so_luong_ton_kho',
    'thuoc_tinh',
    'link_anh_bien_the',
    'trang_thai',
])]

class ProductVariant extends Model {
    use HasFactory;

    protected $casts = [
        'gia_ban'          => 'decimal:0',
        'gia_niem_yet'     => 'decimal:0',
        'so_luong_ton_kho' => 'integer',
        'thuoc_tinh'       => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ma_san_pham', 'ma_san_pham');
    }
}

?>
