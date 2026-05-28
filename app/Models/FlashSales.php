<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class FlashSales extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_flash_sales',
        'ten_flash_sales',
        'mo_ta',
        'bat_dau',
        'ket_thuc',
        'trang_thai',
    ];

    protected $casts = [
        'bat_dau' => 'datetime',
        'ket_thuc' => 'datetime',
    ];
    
    public function flash_sale_items()
    {
        return $this->hasMany(FlashSaleItem::class, 'ma_flash_sales', 'ma_flash_sales');
    }
}
