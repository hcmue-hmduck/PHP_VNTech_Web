<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_san_pham',
        'ten_san_pham',
        'ma_danh_muc',
        'ma_thuong_hieu',
        'mo_ta_ngan',
        'mo_ta_chi_tiet',
        'link_anh_dai_dien',
        'trang_thai',
        'hinh_anh',
        'thong_so_ky_thuat_chung',
        'thong_tin_them',
        'kiem_tra_bien_the',
        'luot_xem',
        'gia_thap_nhat',
        'so_sao_trung_binh',
        'so_luot_danh_gia',
        'tong_so_sao',
        'tong_luot_ban',
    ];

    public function uniqueIds(): array
    {
        return ['ma_san_pham'];
    }

    protected $casts = [
        'gia_thap_nhat' => 'decimal:0',
        'luot_xem' => 'integer',
        'so_sao_trung_binh' => 'decimal:2',
        'so_luot_danh_gia' => 'integer',
        'tong_so_sao' => 'integer',
        'tong_luot_ban' => 'integer',
        'kiem_tra_bien_the' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'ma_san_pham';
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function getFlashSaleInfoAttribute()
    {
        foreach ($this->variants as $variant) {
            if ($variant->flash_sale_info) {
                return $variant->flash_sale_info;
            }
        }
        return null;
    }
}
