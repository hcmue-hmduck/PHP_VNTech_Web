<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_danh_muc',
        'ma_danh_muc_cha',
        'ten_danh_muc',
        'logo_url',
        'trang_thai',
    ];
}
