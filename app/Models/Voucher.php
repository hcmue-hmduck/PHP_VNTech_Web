<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;

#[Fillable([
    'ma_voucher',
    'mo_ta',
    'loai_voucher',
    'hinh_thuc_giam',
    'gia_tri_giam',
    'muc_giam_toi_da',
    'don_hang_toi_thieu',
    'tong_luot_dung',
    'da_dung',
    'bat_dau',
    'ket_thuc',
    'trang_thai',
])]

class Voucher extends Model {
    use HasFactory;

    protected $casts = [
        'gia_tri_giam'     => 'decimal:0',
        'muc_giam_toi_da'  => 'decimal:0',
        'don_hang_toi_thieu'=> 'decimal:0',
        'tong_luot_dung'   => 'integer',
        'da_dung'          => 'integer',
        'bat_dau'          => 'datetime',
        'ket_thuc'         => 'datetime',
    ];

    // Scope: Chỉ lấy voucher còn hiệu lực
    // Lưu ý: Kiểm tra da_dung < tong_luot_dung thực hiện ở Controller
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('trang_thai', 'active')
                     ->where('bat_dau', '<=', now())
                     ->where('ket_thuc', '>=', now());
    }
}

?>
