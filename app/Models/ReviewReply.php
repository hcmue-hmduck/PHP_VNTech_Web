<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ReviewReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_phan_hoi',
        'ma_danh_gia',
        'ma_admin',
        'noi_dung',
        'lich_su_phan_hoi',
        'is_updated',
        'trang_thai',
    ];

    protected $casts = [
        'lich_su_phan_hoi' => 'array',
        'is_updated' => 'boolean',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'ma_danh_gia', 'ma_danh_gia');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'ma_admin', 'ma_nguoi_dung');
    }
}
