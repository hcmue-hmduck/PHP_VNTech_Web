<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_don_hang',
        'ma_nguoi_dung',
        'ho_ten_nguoi_nhan',
        'so_dien_thoai_nhan',
        'dia_chi_giao_hang',
        'ghi_chu',
        'ma_voucher',
        'tong_tien_hang',
        'phi_van_chuyen',
        'gia_tri_giam_voucher',
        'tong_thanh_toan',
        'phuong_thuc_thanh_toan',
        'trang_thai',
    ];

    protected $casts = [
        'tong_tien_hang'      => 'decimal:0',
        'phi_van_chuyen'      => 'decimal:0',
        'gia_tri_giam_voucher'=> 'decimal:0',
        'tong_thanh_toan'     => 'decimal:0',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'ma_don_hang', 'ma_don_hang');
    }
}
