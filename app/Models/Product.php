<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
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
    'gia_ban',
    'gia_niem_yet',
])]

class Product extends Model {
    use HasFactory;

    protected $casts = [
        'gia_ban' => 'decimal:0',
        'gia_niem_yet' => 'decimal:0',
        'luot_xem' => 'integer',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'ma_san_pham', 'ma_san_pham');
    }
}

?>