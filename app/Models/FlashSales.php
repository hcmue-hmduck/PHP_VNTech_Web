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

    protected $appends = [
        'trang_thai_hien_thi',
    ];

    public function getTrangThaiHienThiAttribute(): string
    {
        $status = strtolower($this->trang_thai ?? 'active');
        if ($status === 'deleted') {
            return 'deleted';
        }
        if ($status === 'finished') {
            return 'ended';
        }
        
        $now = now();
        $start = $this->bat_dau;
        $end = $this->ket_thuc;
        
        if ($start && $now->lt($start)) {
            return 'scheduled';
        }
        if ($end && $now->gt($end)) {
            return 'ended';
        }
        
        return 'live';
    }
    
    public function flash_sale_items()
    {
        return $this->hasMany(FlashSaleItem::class, 'ma_flash_sales', 'ma_flash_sales');
    }
}
