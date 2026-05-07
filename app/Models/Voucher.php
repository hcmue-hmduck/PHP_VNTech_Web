<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Voucher extends Model {
    use HasFactory;

    protected $primaryKey = 'ma_voucher';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
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
    ];

    protected $casts = [
        'gia_tri_giam'       => 'decimal:0',
        'muc_giam_toi_da'    => 'decimal:0',
        'don_hang_toi_thieu' => 'decimal:0',
        'tong_luot_dung'     => 'integer',
        'da_dung'            => 'integer',
        'bat_dau'            => 'datetime',
        'ket_thuc'           => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active')
                     ->where('bat_dau', '<=', now())
                     ->where('ket_thuc', '>=', now());
    }

    public function isAvailable()
    {
        return $this->da_dung < $this->tong_luot_dung;
    }
}
