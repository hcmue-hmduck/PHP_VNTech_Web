<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'ma_san_pham',
        'ten_san_pham',
        'slug',
        'ma_danh_muc',
        'ma_thuong_hieu',
        'mo_ta_ngan',
        'mo_ta_chi_tiet',
        'link_anh_dai_dien',
        'trang_thai',
        'hinh_anh',
        'thuoc_tinh_chung',
        'variants',
        'luot_xem',
        'gia_thap_nhat',
    ];

    public function uniqueIds(): array
    {
        return ['ma_san_pham'];
    }

    protected $casts = [
        'gia_thap_nhat' => 'decimal:0',
        'luot_xem' => 'integer',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'ma_san_pham', 'ma_san_pham');
    }
}