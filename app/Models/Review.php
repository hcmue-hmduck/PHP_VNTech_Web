<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Review extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_danh_gia',
        'ma_san_pham',
        'ma_bien_the',
        'ma_nguoi_dung',
        'ma_don_hang',
        'ma_chi_tiet_don_hang',
        'ten_bien_the',
        'so_sao',
        'noi_dung',
        'danh_sach_anh',
        'video',
        'lich_su_chinh_sua',
        'is_anonymous',
        'trang_thai',
        'is_updated'
    ];

    protected $casts = [
        'so_sao'   => 'integer',
        'danh_sach_anh' => 'array',
        'video' => 'array',
        'lich_su_chinh_sua' => 'array',
        'is_anonymous' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'ma_bien_the', 'ma_bien_the');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'ma_chi_tiet_don_hang', 'ma_chi_tiet_don_hang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function replies()
    {
        return $this->hasMany(ReviewReply::class, 'ma_danh_gia', 'ma_danh_gia');
    }
}
