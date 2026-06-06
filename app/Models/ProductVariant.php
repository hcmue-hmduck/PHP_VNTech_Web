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
        'link_anh_bien_the',
        'gia_ban',
        'gia_niem_yet',
        'so_luong_ton_kho',
        'da_ban',
        'thong_so_ky_thuat_rieng',
        'trang_thai',
    ];

    protected $casts = [
        'gia_ban'          => 'decimal:0',
        'gia_niem_yet'     => 'decimal:0',
        'so_luong_ton_kho' => 'integer',
        'da_ban'           => 'integer',
    ];

    protected $appends = [
        'ten_san_pham',
        'ten_hien_thi',
    ];

    public function getTenSanPhamAttribute(): string
    {
        return $this->product?->ten_san_pham ?? '';
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function getTenHienThiAttribute(): string
    {
        $productName = trim((string) ($this->product?->ten_san_pham ?? ''));
        $variantName = trim((string) ($this->ten_bien_the ?? ''));

        return trim($productName . ' ' . $variantName) ?: $variantName;
    }

    public function activeFlashSaleItem()
    {
        $now = now();
        $activeCampaignIds = FlashSales::where('trang_thai', 'active')
            ->where('bat_dau', '<=', $now)
            ->where('ket_thuc', '>=', $now)
            ->pluck('ma_flash_sales')
            ->toArray();

        return $this->hasOne(FlashSaleItem::class, 'ma_bien_the', 'ma_bien_the')
            ->where('trang_thai', 'active')
            ->whereIn('ma_flash_sales', $activeCampaignIds);
    }

    public function getFlashSaleInfoAttribute()
    {
        return $this->activeFlashSaleItem;
    }

    public function getFlashSaleCampaignAttribute()
    {
        return $this->activeFlashSaleItem?->campaign;
    }
}
