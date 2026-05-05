<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'ma_nguoi_dung',
    'ho_ten',
    'so_dien_thoai',
    'dia_chi_chi_tiet',
    'tinh_thanh',
    'quan_huyen',
    'phuong_xa',
    'is_default',
])]

class UserAddress extends Model {
    use HasFactory;

    protected $table = 'user_address';

    protected $casts = [
        'is_default' => 'boolean',
    ];
}

?>
