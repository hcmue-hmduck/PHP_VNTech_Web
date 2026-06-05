<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::query()->delete();
        
        $brands = [
            'Apple',        // Điện thoại, Laptop, Máy tính để bàn
            'Samsung',      // Điện thoại, Ổ cứng SSD, RAM
            'ASUS',         // Laptop, Linh kiện, Gaming Gear
            'MSI',          // Laptop, Card đồ họa, Gaming Gear
            'Dell',         // Laptop, Máy tính để bàn
            'Lenovo',       // Laptop, Máy tính để bàn
            'HP',           // Laptop, Máy tính để bàn
            'Acer',         // Laptop, Máy tính để bàn
            'Intel',        // CPU
            'AMD',          // CPU, Card đồ họa
            'NVIDIA',       // Card đồ họa (GPU)
            'Gigabyte',     // Linh kiện máy tính, Laptop
            'Kingston',     // RAM, Ổ cứng SSD
            'Corsair',      // RAM, Linh kiện, Gaming Gear
            'Logitech',     // Gaming Gear (Bàn phím, Chuột, Tai nghe)
            'Razer',        // Gaming Gear, Laptop
            'SteelSeries',  // Gaming Gear (Tai nghe, Bàn phím, Chuột)
            'Sony',         // Tai nghe
            'Xiaomi',       // Điện thoại
            'OPPO',         // Điện thoại
        ];

        foreach ($brands as $name) {
            $brand = Brand::create([
                'ma_thuong_hieu' => 'temp',
                'ten_thuong_hieu'=> $name,
                'mo_ta'          => 'Thương hiệu ' . $name,
                'logo_url'       => null,
                'trang_thai'     => 'active',
            ]);

            $brand->update([
                'ma_thuong_hieu' => $brand->_id,
            ]);
        }
    }
}
