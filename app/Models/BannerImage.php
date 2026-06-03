<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BannerImage extends Model
{
    protected $fillable = [
        'ma_banner',
        'image_url',
        'tieu_de',
        'mo_ta',
        'lien_ket',
        'thu_tu_hien_thi',
        'trang_thai'
    ];
}
