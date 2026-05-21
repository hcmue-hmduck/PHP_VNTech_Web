<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use App\Models\FlashSaleItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo Brands
        $brands = [
            ['ma_thuong_hieu' => 'APPLE', 'ten_thuong_hieu' => 'Apple', 'slug' => 'apple', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg', 'trang_thai' => 'active'],
            ['ma_thuong_hieu' => 'DELL', 'ten_thuong_hieu' => 'Dell', 'slug' => 'dell', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/48/Dell_Logo.svg', 'trang_thai' => 'active'],
            ['ma_thuong_hieu' => 'ASUS', 'ten_thuong_hieu' => 'ASUS', 'slug' => 'asus', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/d/de/Asus_Logo.svg', 'trang_thai' => 'active'],
            ['ma_thuong_hieu' => 'HP', 'ten_thuong_hieu' => 'HP', 'slug' => 'hp', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/HP_logo_2012.svg', 'trang_thai' => 'active'],
            ['ma_thuong_hieu' => 'MSI', 'ten_thuong_hieu' => 'MSI', 'slug' => 'msi', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/a4/MSI_Logo.svg', 'trang_thai' => 'active'],
            ['ma_thuong_hieu' => 'LENOVO', 'ten_thuong_hieu' => 'Lenovo', 'slug' => 'lenovo', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/b/b8/Lenovo_logo_2015.svg', 'trang_thai' => 'active'],
            ['ma_thuong_hieu' => 'ACER', 'ten_thuong_hieu' => 'Acer', 'slug' => 'acer', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/8/8b/Acer_2011.svg', 'trang_thai' => 'active'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        // 2. Seed categories (inlined from CategorySeeder)
        $categories = [
            ['ten_danh_muc' => 'CPU', 'ma_danh_muc_cha' => null, 'trang_thai' => 'active'],
            ['ten_danh_muc' => 'VGA', 'ma_danh_muc_cha' => null, 'trang_thai' => 'active'],
            ['ten_danh_muc' => 'Mainboard', 'ma_danh_muc_cha' => null, 'trang_thai' => 'active'],
            ['ten_danh_muc' => 'RAM', 'ma_danh_muc_cha' => null, 'trang_thai' => 'active'],
            ['ten_danh_muc' => 'Laptop', 'ma_danh_muc_cha' => null, 'trang_thai' => 'active'],
            ['ten_danh_muc' => 'Peripherals', 'ma_danh_muc_cha' => null, 'trang_thai' => 'active'],
        ];

        foreach ($categories as $data) {
            $exists = Category::where('ten_danh_muc', $data['ten_danh_muc'])->first();
            if ($exists) continue;

            $cat = Category::create([
                'ten_danh_muc' => $data['ten_danh_muc'],
                'ma_danh_muc_cha' => $data['ma_danh_muc_cha'] ?? null,
                'logo_url' => null,
                'trang_thai' => $data['trang_thai'] ?? 'active',
            ]);

            if (empty($cat->ma_danh_muc)) {
                $cat->ma_danh_muc = $cat->_id ?? $cat->id ?? null;
                $cat->save();
            }
        }

        // 2. Tạo Users (Password: 123456)
        $password = Hash::make('123456');
        
        User::create([
            'ho_ten' => 'Admin VNTech',
            'email' => 'admin@vntech.com',
            'password' => $password,
            'vai_tro' => 'admin',
            'so_dien_thoai' => '0987654321',
            'trang_thai' => 'active'
        ]);

        User::create([
            'ho_ten' => 'Nguyễn Văn Khách',
            'email' => 'user1@gmail.com',
            'password' => $password,
            'vai_tro' => 'user',
            'so_dien_thoai' => '0123456789',
            'trang_thai' => 'active'
        ]);

        User::create([
            'ho_ten' => 'Trần Thị Người Mua',
            'email' => 'user2@gmail.com',
            'password' => $password,
            'vai_tro' => 'user',
            'so_dien_thoai' => '0999888777',
            'trang_thai' => 'active'
        ]);

        // 3. Tạo Products & Variants (20 Laptop)
        $laptops = [
            ['brand' => 'APPLE', 'name' => 'MacBook Air M2 2022', 'price' => 2400],
            ['brand' => 'APPLE', 'name' => 'MacBook Pro M3 Pro 14 inch', 'price' => 4500],
            ['brand' => 'DELL', 'name' => 'Dell XPS 13 9315', 'price' => 2800],
            ['brand' => 'DELL', 'name' => 'Dell Inspiron 16 5620', 'price' => 1850],
            ['brand' => 'ASUS', 'name' => 'ASUS ROG Zephyrus G14', 'price' => 3500],
            ['brand' => 'ASUS', 'name' => 'ASUS Vivobook 15 OLED', 'price' => 1400],
            ['brand' => 'HP', 'name' => 'HP Spectre x360 14', 'price' => 3200],
            ['brand' => 'HP', 'name' => 'HP Pavilion 15 eg2000', 'price' => 1600],
            ['brand' => 'MSI', 'name' => 'MSI Katana 15 B13V', 'price' => 2200],
            ['brand' => 'MSI', 'name' => 'MSI Modern 14 C13M', 'price' => 1100],
            ['brand' => 'LENOVO', 'name' => 'Lenovo Legion 5 Slim', 'price' => 2900],
            ['brand' => 'LENOVO', 'name' => 'Lenovo Yoga 7i Gen 8', 'price' => 2100],
            ['brand' => 'ACER', 'name' => 'Acer Nitro V ANV15', 'price' => 1900],
            ['brand' => 'ACER', 'name' => 'Acer Swift Go 14', 'price' => 1700],
            ['brand' => 'APPLE', 'name' => 'MacBook Pro 16 M3 Max', 'price' => 8500],
            ['brand' => 'DELL', 'name' => 'Dell Alienware m16 R2', 'price' => 4800],
            ['brand' => 'ASUS', 'name' => 'ASUS Zenbook 14 OLED', 'price' => 2300],
            ['brand' => 'HP', 'name' => 'HP Omen 16 2023', 'price' => 3100],
            ['brand' => 'MSI', 'name' => 'MSI Raider GE78 HX', 'price' => 7500],
            ['brand' => 'LENOVO', 'name' => 'Lenovo ThinkPad X1 Carbon Gen 11', 'price' => 3800],
        ];

        foreach ($laptops as $index => $lap) {
            $cpu = 'Intel Core i5';
            $screen = '15.6" FHD (1920x1080) IPS';
            $gpu = 'Intel Iris Xe Graphics';
            $weight = '1.7 kg';

            if ($lap['brand'] === 'APPLE') {
                $cpu = str_contains($lap['name'], 'M2') ? 'Apple M2' : (str_contains($lap['name'], 'M3 Max') ? 'Apple M3 Max' : 'Apple M3 Pro');
                $screen = str_contains($lap['name'], '16') ? '16.2" Liquid Retina XDR' : '14.2" Liquid Retina XDR';
                if (str_contains($lap['name'], 'Air')) {
                    $screen = '13.6" Liquid Retina';
                }
                $gpu = 'Apple Integrated GPU';
                $weight = '1.24 kg';
            } elseif (str_contains($lap['name'], 'Zephyrus') || str_contains($lap['name'], 'Nitro') || str_contains($lap['name'], 'Katana') || str_contains($lap['name'], 'Legion') || str_contains($lap['name'], 'Omen') || str_contains($lap['name'], 'Raider') || str_contains($lap['name'], 'Alienware')) {
                $cpu = 'Intel Core i7-13700H';
                $screen = '16" QHD+ (2560x1600) 165Hz';
                $gpu = 'NVIDIA GeForce RTX 4060';
                $weight = '2.3 kg';
            }

            $product = Product::create([
                'ten_san_pham' => $lap['name'],
                'ma_danh_muc' => 'LAPTOP',
                'ma_thuong_hieu' => $lap['brand'],
                'mo_ta_ngan' => 'Laptop mạnh mẽ dành cho công việc và giải trí.',
                'mo_ta_chi_tiet' => 'Đây là mô tả chi tiết cho sản phẩm ' . $lap['name'] . '. Hàng chính hãng VNTech.',
                'link_anh_dai_dien' => 'https://picsum.photos/seed/' . ($index + 1) . '/400/300',
                'trang_thai' => 'active',
                'gia_thap_nhat' => $lap['price'],
                'luot_xem' => rand(100, 1000),
                'thong_so_ky_thuat_chung' => [
                    ['ten' => 'Màn hình', 'gia_tri' => $screen],
                    ['ten' => 'CPU', 'gia_tri' => $cpu],
                    ['ten' => 'Card đồ họa', 'gia_tri' => $gpu],
                    ['ten' => 'Trọng lượng', 'gia_tri' => $weight],
                ],
                'thong_tin_them' => [
                    ['ten' => 'Bảo hành', 'gia_tri' => '12 tháng'],
                    ['ten' => 'Tình trạng', 'gia_tri' => 'Mới 100%'],
                    ['ten' => 'Phụ kiện', 'gia_tri' => 'Sạc, Sách hướng dẫn'],
                ]
            ]);
            $product->ma_san_pham = $product->_id;
            $product->save();

            // Tạo 2 Variants cho mỗi Product
            $configs = [
                ['ram' => '8GB', 'ssd' => '256GB', 'price_plus' => 0],
                ['ram' => '16GB', 'ssd' => '512GB', 'price_plus' => 500],
            ];

            foreach ($configs as $cfg) {
                $variant = ProductVariant::create([
                    'ma_san_pham' => $product->ma_san_pham,
                    'ten_bien_the' => $lap['name'] . ' ' . $cfg['ram'] . ' ' . $cfg['ssd'],
                    'gia_ban' => $lap['price'] + $cfg['price_plus'],
                    'gia_niem_yet' => $lap['price'] + $cfg['price_plus'] + 500,
                    'so_luong_ton_kho' => rand(5, 50),
                    'trang_thai' => 'active',
                    'thong_so_ky_thuat_rieng' => [
                        ['ten' => 'RAM', 'gia_tri' => $cfg['ram']],
                        ['ten' => 'Ổ cứng', 'gia_tri' => $cfg['ssd']],
                        ['ten' => 'Màu sắc', 'gia_tri' => 'Silver'],
                    ],
                    'link_anh_bien_the' => 'https://picsum.photos/seed/' . $product->ma_san_pham . $cfg['ram'] . '/400/300'
                ]);
                $variant->ma_bien_the = $variant->_id;
                $variant->save();
            }
        }

        // 4. Tạo Vouchers mẫu
        Voucher::create([
            'ma_voucher' => 'VNTECHNEW',
            'mo_ta' => 'Giảm 100k cho khách hàng mới',
            'loai_voucher' => 'bill',
            'hinh_thuc_giam' => 'fixed',
            'gia_tri_giam' => 100000,
            'don_hang_toi_thieu' => 500000,
            'tong_luot_dung' => 100,
            'da_dung' => 0,
            'bat_dau' => now(),
            'ket_thuc' => now()->addDays(30),
            'trang_thai' => 'active'
        ]);

        Voucher::create([
            'ma_voucher' => 'FREESHIP',
            'mo_ta' => 'Miễn phí vận chuyển cho đơn hàng từ 2tr',
            'loai_voucher' => 'shipping',
            'hinh_thuc_giam' => 'fixed',
            'gia_tri_giam' => 30000,
            'don_hang_toi_thieu' => 2000000,
            'tong_luot_dung' => 500,
            'da_dung' => 0,
            'bat_dau' => now(),
            'ket_thuc' => now()->addDays(60),
            'trang_thai' => 'active'
        ]);

        // 5. Tạo 1 Flash Sale mẫu cho sản phẩm đầu tiên
        $firstVariant = ProductVariant::first();
        if ($firstVariant) {
            FlashSaleItem::create([
                'ma_bien_the' => $firstVariant->ma_bien_the,
                'gia_flash_sale' => 15000000,
                'so_luong_gioi_han' => 5,
                'so_luong_da_ban' => 0,
                'bat_dau' => now(),
                'ket_thuc' => now()->addHours(5),
                'trang_thai' => 'active'
            ]);
        }

        // 6. Tạo Cart và CartItem cho User
        $userKhach = User::where('email', 'user1@gmail.com')->first();
        if ($userKhach && $firstVariant) {
            $cart = Cart::create([
                'ma_nguoi_dung' => $userKhach->_id,
                'trang_thai' => 'active'
            ]);

            CartItem::create([
                'ma_gio_hang' => $cart->_id,
                'ma_bien_the' => $firstVariant->ma_bien_the,
                'so_luong' => 1,
            ]);

            $anotherVariant = ProductVariant::skip(4)->first() ?: $firstVariant;
            CartItem::create([
                'ma_gio_hang' => $cart->_id,
                'ma_bien_the' => $anotherVariant->ma_bien_the,
                'so_luong' => 2,
            ]);
        }
    }
}
