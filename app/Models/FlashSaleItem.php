<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FlashSaleItem extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_bien_the',
        'gia_flash_sale',
        'so_luong_gioi_han',
        'so_luong_da_ban',
        'gioi_han_moi_nguoi',
        'bat_dau',
        'ket_thuc',
        'trang_thai',
    ];

    protected $casts = [
        'gia_flash_sale'    => 'decimal:0',
        'so_luong_gioi_han' => 'integer',
        'so_luong_da_ban'   => 'integer',
        'gioi_han_moi_nguoi'=> 'integer',
        'bat_dau'           => 'datetime',
        'ket_thuc'          => 'datetime',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'ma_bien_the', 'ma_sku');
    }

    /**
     * Scope: Lấy các Flash Sale đang diễn ra
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('trang_thai', 'active')
                     ->where('bat_dau', '<=', now())
                     ->where('ket_thuc', '>=', now());
    }
}
