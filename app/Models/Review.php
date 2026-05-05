<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Product;

#[Fillable([
    'ma_san_pham',
    'ma_nguoi_dung',
    'ma_don_hang',
    'so_sao',
    'noi_dung',
    'hinh_anh',
    'trang_thai',
])]

class Review extends Model {
    use HasFactory;

    protected $casts = [
        'so_sao'   => 'integer',
        'hinh_anh' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ma_san_pham', 'ma_san_pham');
    }
}

?>
