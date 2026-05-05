<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'ma_nguoi_dung',
    'trang_thai',
])]

class Cart extends Model {
    use HasFactory;
}

?>