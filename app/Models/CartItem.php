<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class CartItem extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_gio_hang',
        'ma_bien_the',
        'so_luong',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'ma_bien_the', 'ma_sku');
    }
}