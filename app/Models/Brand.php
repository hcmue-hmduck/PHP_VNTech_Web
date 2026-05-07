<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Brand extends Model {
    use HasFactory;

    protected $primaryKey = 'ma_thuong_hieu';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_thuong_hieu',
        'ten_thuong_hieu',
        'mo_ta',
        'logo_url',
        'slug',
        'trang_thai',
    ];
}