<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FlashSaleItem extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_chi_tiet_flash_sales',
        'ma_flash_sales',
        'ma_bien_the',
        'gia_flash_sale',
        'so_luong_gioi_han',
        'so_luong_da_ban',
        'gioi_han_moi_nguoi',
        'trang_thai',
    ];

    protected $casts = [
        'gia_flash_sale'    => 'decimal:0',
        'so_luong_gioi_han' => 'integer',
        'so_luong_da_ban'   => 'integer',
        'gioi_han_moi_nguoi'=> 'integer',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'ma_bien_the', 'ma_bien_the');
    }

    public function campaign()
    {
        return $this->belongsTo(FlashSales::class, 'ma_flash_sales', 'ma_flash_sales');
    }

    /**
     * Scope: Lấy các Flash Sale đang diễn ra
     */
    public function scopeActive(Builder $query)
    {
        $now = now();
        $activeCampaignIds = FlashSales::where('trang_thai', 'active')
            ->where('bat_dau', '<=', $now)
            ->where('ket_thuc', '>=', $now)
            ->pluck('ma_flash_sales')
            ->toArray();

        return $query->whereIn('ma_flash_sales', $activeCampaignIds)
                     ->where('trang_thai', 'active');
    }
}
