<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_thong_bao',
        'ma_nguoi_dung',
        'tieu_de',
        'noi_dung',
        'loai',
        'duong_dan',
        'da_doc', 
    ];

    protected $casts = [
        'da_doc' => 'boolean',
    ];

    protected $attributes = [
        'da_doc' => false,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
}
