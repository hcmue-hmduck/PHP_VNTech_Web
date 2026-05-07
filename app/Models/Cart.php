<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Cart extends Model {
    use HasFactory;

    protected $fillable = [
        'ma_nguoi_dung',
        'trang_thai',
    ];
}