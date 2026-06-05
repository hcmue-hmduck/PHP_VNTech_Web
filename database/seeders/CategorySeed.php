<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeed extends Seeder
{
    public function run(): void
    {
        Category::query()->delete();
        
        $categories = [];

        $categoryData = [
            ['Điện thoại', null],
            ['Laptop', null],
            ['Máy tính để bàn', null],
            ['Linh kiện máy tính', null],
            ['Gaming Gear', null],

            ['CPU', 'Linh kiện máy tính'],
            ['Card đồ họa', 'Linh kiện máy tính'],
            ['RAM', 'Linh kiện máy tính'],
            ['Ổ cứng SSD', 'Linh kiện máy tính'],

            ['Bàn phím', 'Gaming Gear'],
            ['Chuột', 'Gaming Gear'],
            ['Tai nghe', 'Gaming Gear'],
        ];

        foreach ($categoryData as $item) {
            $parentId = null;

            if ($item[1]) {
                $parentId = $categories[$item[1]]->_id;
            }

            $category = Category::create([
                'ma_danh_muc'     => 'temp',
                'ma_danh_muc_cha' => $parentId,
                'ten_danh_muc'    => $item[0],
                'logo_url'        => null,
                'trang_thai'      => 'active',
            ]);

            $category->update([
                'ma_danh_muc' => $category->_id,
            ]);

            $categories[$item[0]] = $category;
        }
    }
}
