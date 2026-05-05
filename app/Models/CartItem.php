<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\ProductVariant;

#[Fillable([
    'ma_gio_hang',
    'ma_bien_the',
    'so_luong',
])]

class CartItem extends Model {
    use HasFactory;

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'ma_bien_the', 'ma_sku');
    }
}

?>