<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use App\Models\FlashSales;
use App\Models\FlashSaleItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CLEAR DATABASE
        |--------------------------------------------------------------------------
        */

        User::query()->delete();
        Brand::query()->delete();
        Category::query()->delete();
        Product::query()->delete();
        ProductVariant::query()->delete();
        Voucher::query()->delete();
        FlashSales::query()->delete();
        FlashSaleItem::query()->delete();
        Cart::query()->delete();
        CartItem::query()->delete();
        UserAddress::query()->delete();
        Order::query()->delete();
        OrderItem::query()->delete();
        Review::query()->delete();


        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $admin = User::create([
            'ho_ten'        => 'Administrator',
            'email'         => 'admin@gmail.com',
            'so_dien_thoai' => '0900000001',
            'password'      => Hash::make('123456'),
            'vai_tro'       => 'admin',
            'avatar_url'    => 'avatars/admin.jpg',
            'bio'           => 'Quản trị viên',
            'trang_thai'    => 'active',
        ]);

        $user1 = User::create([
            'ho_ten'        => 'Nguyen Van A',
            'email'         => 'user1@gmail.com',
            'so_dien_thoai' => '0900000002',
            'password'      => Hash::make('123456'),
            'vai_tro'       => 'user',
            'avatar_url'    => 'avatars/user1.jpg',
            'bio'           => 'Khách hàng 1',
            'trang_thai'    => 'active',
        ]);

        $user2 = User::create([
            'ho_ten'        => 'Tran Thi B',
            'email'         => 'user2@gmail.com',
            'so_dien_thoai' => '0900000003',
            'password'      => Hash::make('123456'),
            'vai_tro'       => 'user',
            'avatar_url'    => 'avatars/user2.jpg',
            'bio'           => 'Khách hàng 2',
            'trang_thai'    => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | USER ADDRESS
        |--------------------------------------------------------------------------
        */

        UserAddress::create([
            'ma_nguoi_dung'    => (string) $user1->_id,
            'ho_ten'           => 'Nguyen Van A',
            'so_dien_thoai'    => '0900000002',
            'dia_chi_chi_tiet' => '123 Nguyễn Trãi',
            'tinh_thanh'       => 'TP.HCM',
            'quan_huyen'       => 'Quận 1',
            'phuong_xa'        => 'Bến Nghé',
            'is_default'       => true,
        ]);

        UserAddress::create([
            'ma_nguoi_dung'    => (string) $user2->_id,
            'ho_ten'           => 'Tran Thi B',
            'so_dien_thoai'    => '0900000003',
            'dia_chi_chi_tiet' => '456 Lê Lợi',
            'tinh_thanh'       => 'TP.HCM',
            'quan_huyen'       => 'Quận 3',
            'phuong_xa'        => 'Phường 7',
            'is_default'       => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | BRANDS
        |--------------------------------------------------------------------------
        */

        $brandData = [
            ['Apple', 'apple.png'],
            ['Samsung', 'samsung.png'],
            ['ASUS', 'asus.png'],
            ['MSI', 'msi.png'],
            ['Dell', 'dell.png'],
            ['Lenovo', 'lenovo.png'],
            ['Intel', 'intel.png'],
            ['AMD', 'amd.png'],
            ['NVIDIA', 'nvidia.png'],
            ['Logitech', 'logitech.png'],
            ['Razer', 'razer.png'],
            ['Kingston', 'kingston.png'],
        ];

        $brands = [];

        foreach ($brandData as $item) {

            $brand = Brand::create([
                'ma_thuong_hieu' => 'temp',
                'ten_thuong_hieu'=> $item[0],
                'mo_ta'          => 'Thương hiệu ' . $item[0],
                'logo_url'       => 'brands/' . $item[1],
                'trang_thai'     => 'active',
            ]);

            $brand->update([
                'ma_thuong_hieu' => (string) $brand->_id,
            ]);

            $brands[$item[0]] = $brand;
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

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
                $parentId = (string) $categories[$item[1]]->_id;
            }

            $category = Category::create([
                'ma_danh_muc'     => 'temp',
                'ma_danh_muc_cha' => $parentId,
                'ten_danh_muc'    => $item[0],
                'logo_url'        => 'categories/default.png',
                'trang_thai'      => 'active',
            ]);

            $category->update([
                'ma_danh_muc' => (string) $category->_id,
            ]);

            $categories[$item[0]] = $category;
        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        $productsData = [

            ['iPhone 15 Pro Max', 'Điện thoại', 'Apple', 28990000],
            ['iPhone 14 Plus', 'Điện thoại', 'Apple', 19990000],
            ['Galaxy S24 Ultra', 'Điện thoại', 'Samsung', 26990000],
            ['Galaxy A55', 'Điện thoại', 'Samsung', 9990000],

            ['ASUS ROG Strix G16', 'Laptop', 'ASUS', 32990000],
            ['ASUS Vivobook OLED', 'Laptop', 'ASUS', 18990000],
            ['MSI Katana 15', 'Laptop', 'MSI', 24990000],
            ['Dell XPS 13', 'Laptop', 'Dell', 35990000],
            ['Lenovo Legion 5', 'Laptop', 'Lenovo', 28990000],

            ['Intel Core i5 14600K', 'CPU', 'Intel', 7490000],
            ['Intel Core i7 14700K', 'CPU', 'Intel', 10990000],

            ['Ryzen 5 7600X', 'CPU', 'AMD', 6490000],
            ['Ryzen 7 7800X3D', 'CPU', 'AMD', 10990000],

            ['RTX 4060', 'Card đồ họa', 'NVIDIA', 9990000],
            ['RTX 4070 Super', 'Card đồ họa', 'NVIDIA', 18990000],

            ['Kingston Fury 16GB', 'RAM', 'Kingston', 1490000],
            ['Kingston NV2 1TB', 'Ổ cứng SSD', 'Kingston', 1690000],

            ['Logitech G Pro X', 'Bàn phím', 'Logitech', 2990000],
            ['Razer DeathAdder V3', 'Chuột', 'Razer', 1890000],
            ['Razer BlackShark V2', 'Tai nghe', 'Razer', 2490000],
        ];

        $allVariants = [];

        foreach ($productsData as $index => $item) {

            $product = Product::create([

                'ma_san_pham'     => 'temp',
                'ten_san_pham'    => $item[0],
                'ma_danh_muc'     => (string) $categories[$item[1]]->_id,
                'ma_thuong_hieu'  => (string) $brands[$item[2]]->_id,

                'mo_ta_ngan'      => 'Mô tả ngắn ' . $item[0],

                'mo_ta_chi_tiet'  => 'Thông tin chi tiết của sản phẩm ' . $item[0],

                'link_anh_dai_dien' => 'products/product-' . ($index + 1) . '.jpg',

                'trang_thai' => 'active',

                'hinh_anh' => [
                    'products/product-' . ($index + 1) . '-1.jpg',
                    'products/product-' . ($index + 1) . '-2.jpg',
                ],

                'thong_so_ky_thuat_chung' => [
                    [
                        'ten' => 'Bảo hành',
                        'gia_tri' => '12 tháng',
                    ],
                    [
                        'ten' => 'Tình trạng',
                        'gia_tri' => 'Mới 100%',
                    ]
                ],

                'thong_tin_them' => [
                    [
                        'ten' => 'Xuất xứ',
                        'gia_tri' => 'Chính hãng',
                    ]
                ],

                'luot_xem' => rand(50, 1000),

                'gia_thap_nhat' => $item[3],
            ]);

            $product->update([
                'ma_san_pham' => (string) $product->_id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | VARIANT 1
            |--------------------------------------------------------------------------
            */

            $variant1 = ProductVariant::create([

                'ma_san_pham' => (string) $product->_id,

                'ma_bien_the' => 'temp',

                'ten_bien_the' => $product->ten_san_pham . ' Standard',

                'link_anh_bien_the' => 'variants/' . ($index + 1) . '-standard.jpg',

                'gia_ban' => $item[3],

                'gia_niem_yet' => $item[3] + 1000000,

                'so_luong_ton_kho' => 10,

                'trang_thai' => 'active',

                'thong_so_ky_thuat_rieng' => [
                    [
                        'ten' => 'Phiên bản',
                        'gia_tri' => 'Standard',
                    ],
                    [
                        'ten' => 'Màu sắc',
                        'gia_tri' => 'Black',
                    ],
                    [
                        'ten' => 'Dung lượng',
                        'gia_tri' => '256GB',
                    ],
                ],
            ]);

            $variant1->update([
                'ma_bien_the' => (string) $variant1->_id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | VARIANT 2
            |--------------------------------------------------------------------------
            */

            $variant2 = ProductVariant::create([

                'ma_san_pham' => (string) $product->_id,

                'ma_bien_the' => 'temp',

                'ten_bien_the' => $product->ten_san_pham . ' Premium',

                'link_anh_bien_the' => 'variants/' . ($index + 1) . '-premium.jpg',

                'gia_ban' => $item[3] + 2000000,

                'gia_niem_yet' => $item[3] + 3000000,

                'so_luong_ton_kho' => 15,

                'trang_thai' => 'active',

                'thong_so_ky_thuat_rieng' => [
                    [
                        'ten' => 'Phiên bản',
                        'gia_tri' => 'Premium',
                    ],
                    [
                        'ten' => 'Màu sắc',
                        'gia_tri' => 'Silver',
                    ],
                    [
                        'ten' => 'Dung lượng',
                        'gia_tri' => '512GB',
                    ],
                ],
            ]);

            $variant2->update([
                'ma_bien_the' => (string) $variant2->_id,
            ]);

            $allVariants[] = $variant1;
            $allVariants[] = $variant2;
        }


        /*
        |--------------------------------------------------------------------------
        | VOUCHER
        |--------------------------------------------------------------------------
        */

        $voucher = Voucher::create([
            'ma_voucher'          => 'temp',
            'mo_ta'               => 'Giảm 10%',
            'loai_voucher'        => 'public',
            'hinh_thuc_giam'      => 'percent',
            'gia_tri_giam'        => 10,
            'muc_giam_toi_da'     => 1000000,
            'don_hang_toi_thieu'  => 5000000,
            'tong_luot_dung'      => 100,
            'da_dung'             => 5,
            'bat_dau'             => Carbon::now()->subDay(),
            'ket_thuc'            => Carbon::now()->addDays(30),
            'trang_thai'          => 'active',
        ]);

        $voucher->update([
            'ma_voucher' => (string) $voucher->_id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | FLASH SALE
        |--------------------------------------------------------------------------
        */

        $flashSale = FlashSales::create([
            'ma_flash_sales' => 'temp',
            'ten_flash_sales'=> 'Flash Sale Công Nghệ',
            'mo_ta'          => 'Flash sale test',
            'bat_dau'        => Carbon::now()->subHours(1),
            'ket_thuc'       => Carbon::now()->addDays(2),
            'trang_thai'     => 'active',
        ]);

        $flashSale->update([
            'ma_flash_sales' => (string) $flashSale->_id,
        ]);


        FlashSaleItem::create([
            'ma_flash_sales'      => (string) $flashSale->_id,
            'ma_bien_the'         => (string) $allVariants[0]->_id,
            'gia_flash_sale'      => 9999999,
            'so_luong_gioi_han'   => 50,
            'so_luong_da_ban'     => 5,
            'gioi_han_moi_nguoi'  => 2,
            'trang_thai'          => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CART
        |--------------------------------------------------------------------------
        */

        $cart = Cart::create([
            'ma_nguoi_dung' => (string) $user1->_id,
            'trang_thai'    => 'active',
        ]);


        CartItem::create([
            'ma_gio_hang' => (string) $cart->_id,
            'ma_bien_the' => (string) $allVariants[1]->_id,
            'so_luong'    => 2,
        ]);


        /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

        $order = Order::create([
            'ma_don_hang'            => 'temp',
            'ma_nguoi_dung'          => (string) $user1->_id,
            'ho_ten_nguoi_nhan'      => 'Nguyen Van A',
            'so_dien_thoai_nhan'     => '0900000002',
            'dia_chi_giao_hang'      => '123 Nguyễn Trãi',
            'ghi_chu'                => 'Giao giờ hành chính',
            'ma_voucher'             => (string) $voucher->_id,
            'tong_tien_hang'         => 30000000,
            'phi_van_chuyen'         => 30000,
            'gia_tri_giam_voucher'   => 1000000,
            'tong_thanh_toan'        => 29030000,
            'phuong_thuc_thanh_toan' => 'cod',
            'trang_thai'             => 'completed',
        ]);

        $order->update([
            'ma_don_hang' => (string) $order->_id,
        ]);


        $orderItem = OrderItem::create([
            'ma_chi_tiet_don_hang'    => 'temp',
            'ma_don_hang'             => (string) $order->_id,
            'ma_bien_the'             => (string) $allVariants[0]->_id,
            'ten_bien_the'            => $allVariants[0]->ten_bien_the,
            'link_anh_dai_dien'       => $allVariants[0]->link_anh_bien_the,
            'so_luong'                => 1,
            'gia_ban'                 => $allVariants[0]->gia_ban,
            'ma_bien_the_flash_sale'  => null,
            'so_tien_giam_flash_sale' => 0,
            'thanh_tien'              => $allVariants[0]->gia_ban,
        ]);

        $orderItem->update([
            'ma_chi_tiet_don_hang' => (string) $orderItem->_id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | REVIEW
        |--------------------------------------------------------------------------
        */

        Review::create([
            'ma_san_pham'  => (string) Product::first()->_id,
            'ma_nguoi_dung'=> (string) $user1->_id,
            'ma_don_hang'  => (string) $order->_id,
            'so_sao'       => 5,
            'noi_dung'     => 'Sản phẩm rất tốt',
            'hinh_anh'     => [
                'reviews/review1.jpg'
            ],
            'trang_thai'   => 'approved',
        ]);
    }
}