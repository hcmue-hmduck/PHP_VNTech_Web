<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'ma_thuong_hieu',
    'ten_thuong_hieu',
    'mo_ta',
    'logo_url',
    'slug',
    'trang_thai',
])]

class Brand extends Model {
    use HasFactory;
}

?>