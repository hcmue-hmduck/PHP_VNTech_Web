<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Category;

class Products_VariantsSeed extends Seeder
{
    public function run(): void
    {
        // 1. Dọn dẹp dữ liệu cũ để chạy lẻ độc lập an toàn
        Product::query()->delete();
        ProductVariant::query()->delete();

        // 2. Tra cứu danh sách Hãng và Danh mục để liên kết ID
        $brands = [];
        foreach (Brand::all() as $brand) {
            $brands[$brand->ten_thuong_hieu] =  $brand->_id;
        }

        $categories = [];
        foreach (Category::all() as $category) {
            $categories[$category->ten_danh_muc] =  $category->_id;
        }

        // 3. Danh sách ~100 sản phẩm thực tế 100% kèm biến thể tương ứng
        $productsData = [
            /*
            |--------------------------------------------------------------------------
            | 1. ĐIỆN THOẠI (12 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'iPhone 15 Pro Max',
                'category' => 'Điện thoại',
                'brand' => 'Apple',
                'desc' => 'Flagship cao cấp nhất của Apple với khung viền Titan siêu nhẹ, chip A17 Pro mạnh mẽ và hệ thống camera zoom quang học 5x đột phá.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.7 inch, Super Retina XDR OLED, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Apple A17 Pro (3nm)'],
                    ['ten' => 'RAM', 'gia_tri' => '8GB'],
                    ['ten' => 'Camera sau', 'gia_tri' => '48MP + 12MP + 12MP'],
                    ['ten' => 'Pin', 'gia_tri' => '4441 mAh, hỗ trợ sạc nhanh 20W']
                ],
                'variants' => [
                    [
                        'name' => 'iPhone 15 Pro Max 256GB',
                        'price' => 28990000,
                        'price_retail' => 34990000,
                        'stock' => 45,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Tự Nhiên']]
                    ],
                    [
                        'name' => 'iPhone 15 Pro Max 512GB',
                        'price' => 32990000,
                        'price_retail' => 40990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Đen']]
                    ]
                ]
            ],
            [
                'name' => 'iPhone 15 Pro',
                'category' => 'Điện thoại',
                'brand' => 'Apple',
                'desc' => 'Sở hữu chip A17 Pro hiệu năng vượt trội, nút Tác vụ tiện lợi và kích thước 6.1 inch gọn gàng, vừa vặn trong lòng bàn tay.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.1 inch, Super Retina XDR OLED, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Apple A17 Pro (3nm)'],
                    ['ten' => 'RAM', 'gia_tri' => '8GB'],
                    ['ten' => 'Camera sau', 'gia_tri' => '48MP + 12MP + 12MP']
                ],
                'variants' => [
                    [
                        'name' => 'iPhone 15 Pro 128GB',
                        'price' => 24490000,
                        'price_retail' => 28990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '128GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Xanh']]
                    ],
                    [
                        'name' => 'iPhone 15 Pro 256GB',
                        'price' => 27490000,
                        'price_retail' => 31990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'iPhone 15',
                'category' => 'Điện thoại',
                'brand' => 'Apple',
                'desc' => 'Trang bị mặt lưng kính pha màu trẻ trung, Dynamic Island thông minh và camera 48MP siêu sắc nét.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.1 inch, Super Retina XDR OLED'],
                    ['ten' => 'CPU', 'gia_tri' => 'Apple A16 Bionic'],
                    ['ten' => 'RAM', 'gia_tri' => '6GB']
                ],
                'variants' => [
                    [
                        'name' => 'iPhone 15 128GB',
                        'price' => 19790000,
                        'price_retail' => 22990000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '128GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Hồng']]
                    ],
                    [
                        'name' => 'iPhone 15 256GB',
                        'price' => 22790000,
                        'price_retail' => 25990000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category' => 'Điện thoại',
                'brand' => 'Samsung',
                'desc' => 'Đỉnh cao công nghệ AI Phone từ Samsung với khung viền Titan cao cấp, bút S-Pen đa năng và cụm camera zoom 100x đỉnh cao.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.8 inch, Dynamic AMOLED 2X, QHD+, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 3 for Galaxy'],
                    ['ten' => 'RAM', 'gia_tri' => '12GB'],
                    ['ten' => 'Camera sau', 'gia_tri' => '200MP + 50MP + 12MP + 10MP']
                ],
                'variants' => [
                    [
                        'name' => 'Galaxy S24 Ultra 256GB',
                        'price' => 26990000,
                        'price_retail' => 33990000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Xám Titan']]
                    ],
                    [
                        'name' => 'Galaxy S24 Ultra 512GB',
                        'price' => 29990000,
                        'price_retail' => 37490000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen Titan']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Plus',
                'category' => 'Điện thoại',
                'brand' => 'Samsung',
                'desc' => 'Trải nghiệm màn hình QHD+ sắc nét, dung lượng pin cực khủng 4900mAh cùng các tính năng Galaxy AI thông minh vượt trội.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.7 inch, Dynamic AMOLED 2X, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Exynos 2400'],
                    ['ten' => 'RAM', 'gia_tri' => '12GB']
                ],
                'variants' => [
                    [
                        'name' => 'Galaxy S24 Plus 256GB',
                        'price' => 21490000,
                        'price_retail' => 26990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Tím Cobalt']]
                    ],
                    [
                        'name' => 'Galaxy S24 Plus 512GB',
                        'price' => 24490000,
                        'price_retail' => 30490000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Vàng Amber']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung Galaxy A55 5G',
                'category' => 'Điện thoại',
                'brand' => 'Samsung',
                'desc' => 'Smartphone cận cao cấp sở hữu mặt lưng kính sang trọng, viền kim loại cứng cáp và camera chống rung quang học OIS.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.6 inch, Super AMOLED, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Exynos 1480 (4nm)'],
                    ['ten' => 'RAM', 'gia_tri' => '8GB']
                ],
                'variants' => [
                    [
                        'name' => 'Galaxy A55 5G 128GB',
                        'price' => 8990000,
                        'price_retail' => 9990000,
                        'stock' => 60,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '128GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Xanh Iceblue']]
                    ],
                    [
                        'name' => 'Galaxy A55 5G 256GB',
                        'price' => 10490000,
                        'price_retail' => 11990000,
                        'stock' => 45,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Xanh Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Xiaomi 14 Ultra',
                'category' => 'Điện thoại',
                'brand' => 'Xiaomi',
                'desc' => 'Đỉnh cao nhiếp ảnh di động đồng chế tác với Leica, cảm biến 1 inch khẩu độ kép thế hệ mới cho chất lượng ảnh tuyệt mỹ.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.73 inch, AMOLED C8 WQHD+, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 3'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB'],
                    ['ten' => 'Camera sau', 'gia_tri' => '50MP (Leica Lythia-900) + 3 camera 50MP']
                ],
                'variants' => [
                    [
                        'name' => 'Xiaomi 14 Ultra 512GB',
                        'price' => 29990000,
                        'price_retail' => 32990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen da']]
                    ]
                ]
            ],
            [
                'name' => 'Xiaomi 14',
                'category' => 'Điện thoại',
                'brand' => 'Xiaomi',
                'desc' => 'Chiếc điện thoại cao cấp có kích thước nhỏ gọn hiếm hoi trên thị trường, cấu hình khủng cùng hệ thống thấu kính Leica Summilux.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.36 inch, LTPO OLED, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 3'],
                    ['ten' => 'RAM', 'gia_tri' => '12GB']
                ],
                'variants' => [
                    [
                        'name' => 'Xiaomi 14 256GB',
                        'price' => 19990000,
                        'price_retail' => 22990000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Xanh Lá']]
                    ],
                    [
                        'name' => 'Xiaomi 14 512GB',
                        'price' => 21990000,
                        'price_retail' => 24490000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Xiaomi Redmi Note 13 Pro 5G',
                'category' => 'Điện thoại',
                'brand' => 'Xiaomi',
                'desc' => 'Ông vua phân khúc tầm trung với camera độ phân giải siêu cao 200MP chống rung OIS và màn hình 1.5K sắc nét.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.67 inch, AMOLED 1.5K, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 7s Gen 2'],
                    ['ten' => 'RAM', 'gia_tri' => '8GB']
                ],
                'variants' => [
                    [
                        'name' => 'Redmi Note 13 Pro 128GB',
                        'price' => 6490000,
                        'price_retail' => 7290000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '128GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Xanh Đại Dương']]
                    ],
                    [
                        'name' => 'Redmi Note 13 Pro 256GB',
                        'price' => 7290000,
                        'price_retail' => 7990000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen Bán Dạ']]
                    ]
                ]
            ],
            [
                'name' => 'OPPO Find N3',
                'category' => 'Điện thoại',
                'brand' => 'OPPO',
                'desc' => 'Điện thoại gập ngang siêu mỏng nhẹ bậc nhất thế giới, tối ưu hóa giao diện đa nhiệm không giới hạn cùng camera Hasselblad.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '7.82 inch (trong), 6.31 inch (ngoài), LTPO OLED'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 2'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB']
                ],
                'variants' => [
                    [
                        'name' => 'OPPO Find N3 512GB',
                        'price' => 44990000,
                        'price_retail' => 49990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Vàng hoàng kim']]
                    ]
                ]
            ],
            [
                'name' => 'OPPO Reno11 Pro 5G',
                'category' => 'Điện thoại',
                'brand' => 'OPPO',
                'desc' => 'Chuyên gia chân dung thế hệ mới với camera telephoto 32MP chụp xóa phông tuyệt đỉnh cùng thiết kế mặt lưng ánh sao.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.7 inch, AMOLED cong, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Dimensity 8200'],
                    ['ten' => 'RAM', 'gia_tri' => '12GB']
                ],
                'variants' => [
                    [
                        'name' => 'OPPO Reno11 Pro 512GB',
                        'price' => 14990000,
                        'price_retail' => 16990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Trắng Ngọc Trai']]
                    ]
                ]
            ],
            [
                'name' => 'Sony Xperia 1 VI',
                'category' => 'Điện thoại',
                'brand' => 'Sony',
                'desc' => 'Đỉnh cao công nghệ hiển thị và âm thanh chuẩn rạp phim của Sony, camera zoom quang học biến thiên độc nhất vô nhị.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.5 inch, OLED Bravia, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 3'],
                    ['ten' => 'RAM', 'gia_tri' => '12GB']
                ],
                'variants' => [
                    [
                        'name' => 'Sony Xperia 1 VI 256GB',
                        'price' => 32990000,
                        'price_retail' => 34990000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen truyền thống']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 2. LAPTOP (15 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'ASUS ROG Strix G16 2024',
                'category' => 'Laptop',
                'brand' => 'ASUS',
                'desc' => 'Laptop gaming tối thượng với màn hình ROG Nebula 16 inch siêu mượt, chip Intel thế hệ 14 mới nhất và card RTX 40-series.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16 inch, WQXGA IPS, 240Hz'],
                    ['ten' => 'SSD', 'gia_tri' => '1TB PCIe 4.0 NVMe'],
                    ['ten' => 'HĐH', 'gia_tri' => 'Windows 11 Home']
                ],
                'variants' => [
                    [
                        'name' => 'ROG Strix G16 Core i7 RTX 4060',
                        'price' => 33490000,
                        'price_retail' => 35990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-13650HX'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ],
                    [
                        'name' => 'ROG Strix G16 Core i9 RTX 4070',
                        'price' => 45990000,
                        'price_retail' => 48990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i9-14900HX'], ['ten' => 'RAM', 'gia_tri' => '32GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4070 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'ASUS Zenbook 14 OLED UX3405',
                'category' => 'Laptop',
                'brand' => 'ASUS',
                'desc' => 'Laptop doanh nhân siêu mỏng nhẹ, pin cực bền, màn hình Lumina OLED 3K hiển thị sắc nét đỉnh cao cùng chip Intel Core Ultra AI.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '14 inch, 3K OLED, 120Hz, HDR500'],
                    ['ten' => 'Trọng lượng', 'gia_tri' => '1.2 kg'],
                    ['ten' => 'VGA', 'gia_tri' => 'Intel Arc Graphics']
                ],
                'variants' => [
                    [
                        'name' => 'Zenbook 14 Ultra 5 16GB/512GB',
                        'price' => 24990000,
                        'price_retail' => 26990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core Ultra 5 125H'], ['ten' => 'RAM', 'gia_tri' => '16GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '512GB M.2 PCIe']]
                    ],
                    [
                        'name' => 'Zenbook 14 Ultra 7 32GB/1TB',
                        'price' => 30990000,
                        'price_retail' => 32990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core Ultra 7 155H'], ['ten' => 'RAM', 'gia_tri' => '32GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '1TB M.2 PCIe']]
                    ]
                ]
            ],
            [
                'name' => 'MSI Katana 15 B13V',
                'category' => 'Laptop',
                'brand' => 'MSI',
                'desc' => 'Vũ khí chiến game đắc lực cho game thủ với thiết kế thanh lịch lấy cảm hứng từ thanh kiếm Katana, trang bị tản nhiệt Cooler Boost 5 siêu mát.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '15.6 inch, FHD IPS, 144Hz'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB DDR5 (2x8GB)'],
                    ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']
                ],
                'variants' => [
                    [
                        'name' => 'MSI Katana 15 i7/512GB SSD',
                        'price' => 24990000,
                        'price_retail' => 27990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-13620H'], ['ten' => 'SSD', 'gia_tri' => '512GB M.2 PCIe 4.0']]
                    ],
                    [
                        'name' => 'MSI Katana 15 i7/1TB SSD',
                        'price' => 26490000,
                        'price_retail' => 29490000,
                        'stock' => 18,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-13620H'], ['ten' => 'SSD', 'gia_tri' => '1TB M.2 PCIe 4.0']]
                    ]
                ]
            ],
            [
                'name' => 'MSI Raider GE78 HX 14V',
                'category' => 'Laptop',
                'brand' => 'MSI',
                'desc' => 'Dòng siêu laptop gaming hàng đầu của MSI sở hữu dải đèn Matrix Lightbar cực độc và cấu hình phần cứng hủy diệt.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '17 inch, QHD+ IPS, 240Hz, 100% DCI-P3'],
                    ['ten' => 'CPU', 'gia_tri' => 'Intel Core i9-14900HX'],
                    ['ten' => 'SSD', 'gia_tri' => '2TB PCIe Gen4x4 NVMe']
                ],
                'variants' => [
                    [
                        'name' => 'MSI Raider RTX 4080 32GB RAM',
                        'price' => 84990000,
                        'price_retail' => 89990000,
                        'stock' => 5,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '32GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4080 12GB']]
                    ],
                    [
                        'name' => 'MSI Raider RTX 4090 64GB RAM',
                        'price' => 109990000,
                        'price_retail' => 114990000,
                        'stock' => 3,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '64GB DDR5 (2x32GB)'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4090 16GB']]
                    ]
                ]
            ],
            [
                'name' => 'Dell XPS 13 9340 2024',
                'category' => 'Laptop',
                'brand' => 'Dell',
                'desc' => 'Biểu tượng laptop siêu sang trọng với thiết kế nhôm nguyên khối, bàn phím vô cực liền mạch và thanh cảm ứng điện dung chức năng.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '13.4 inch, FHD+ IPS, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Intel Core Ultra 7 155H'],
                    ['ten' => 'VGA', 'gia_tri' => 'Intel Arc Graphics']
                ],
                'variants' => [
                    [
                        'name' => 'Dell XPS 13 i7/16GB/512GB',
                        'price' => 39990000,
                        'price_retail' => 42990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '512GB PCIe Gen4 NVMe']]
                    ],
                    [
                        'name' => 'Dell XPS 13 i7/32GB/1TB',
                        'price' => 46990000,
                        'price_retail' => 49990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '32GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '1TB PCIe Gen4 NVMe']]
                    ]
                ]
            ],
            [
                'name' => 'Dell Inspiron 16 5640',
                'category' => 'Laptop',
                'brand' => 'Dell',
                'desc' => 'Laptop đa năng dành cho học tập, văn phòng với màn hình lớn 16 inch tỷ lệ 16:10 và bàn phím đầy đủ số tiện lợi.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16 inch, FHD+ IPS, Anti-Glare'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'],
                    ['ten' => 'VGA', 'gia_tri' => 'Intel Graphics']
                ],
                'variants' => [
                    [
                        'name' => 'Dell Inspiron 16 Core i5/512GB',
                        'price' => 18490000,
                        'price_retail' => 19990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i5-120U'], ['ten' => 'SSD', 'gia_tri' => '512GB M.2 NVMe']]
                    ],
                    [
                        'name' => 'Dell Inspiron 16 Core i7/1TB',
                        'price' => 22990000,
                        'price_retail' => 24990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-150U'], ['ten' => 'SSD', 'gia_tri' => '1TB M.2 NVMe']]
                    ]
                ]
            ],
            [
                'name' => 'Lenovo Legion Pro 5 16IRX9',
                'category' => 'Laptop',
                'brand' => 'Lenovo',
                'desc' => 'Ông vua laptop gaming tầm trung cận cao cấp, được săn đón nhiều nhất nhờ tản nhiệt tốt, bàn phím gõ êm và build vô cùng đầm tay.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16 inch, WQXGA IPS, 165Hz'],
                    ['ten' => 'SSD', 'gia_tri' => '1TB M.2 PCIe 4.0']
                ],
                'variants' => [
                    [
                        'name' => 'Legion Pro 5 i7 RTX 4060',
                        'price' => 37990000,
                        'price_retail' => 39990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-14650HX'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ],
                    [
                        'name' => 'Legion Pro 5 i9 RTX 4070',
                        'price' => 46990000,
                        'price_retail' => 49990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i9-14900HX'], ['ten' => 'RAM', 'gia_tri' => '32GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4070 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon Gen 12',
                'category' => 'Laptop',
                'brand' => 'Lenovo',
                'desc' => 'Được chế tác từ sợi carbon siêu nhẹ và bền bỉ chuẩn quân đội, bàn phím cơ ThinkPad huyền thoại cùng các công nghệ bảo mật độc quyền tối tân.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '14 inch, 2.8K OLED, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Intel Core Ultra 7 155U'],
                    ['ten' => 'VGA', 'gia_tri' => 'Intel Arc Graphics']
                ],
                'variants' => [
                    [
                        'name' => 'ThinkPad X1 Ultra 7/16GB/512GB',
                        'price' => 49990000,
                        'price_retail' => 52990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '512GB PCIe SSD']]
                    ],
                    [
                        'name' => 'ThinkPad X1 Ultra 7/32GB/1TB',
                        'price' => 56990000,
                        'price_retail' => 59990000,
                        'stock' => 5,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '32GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '1TB PCIe SSD']]
                    ]
                ]
            ],
            [
                'name' => 'Apple MacBook Pro 14 M3',
                'category' => 'Laptop',
                'brand' => 'Apple',
                'desc' => 'Thiết kế sang trọng, tối ưu phần cứng cực mượt, thời lượng pin sử dụng liên tục lên đến 22 giờ cùng sức mạnh của chip Apple Silicon M3.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '14.2 inch, Liquid Retina XDR, ProMotion 120Hz'],
                    ['ten' => 'SSD', 'gia_tri' => '512GB SSD'],
                    ['ten' => 'Trọng lượng', 'gia_tri' => '1.55 kg']
                ],
                'variants' => [
                    [
                        'name' => 'MacBook Pro 14 M3 8GB RAM',
                        'price' => 39990000,
                        'price_retail' => 41990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'M3 (8-core CPU, 10-core GPU)'], ['ten' => 'RAM', 'gia_tri' => '8GB Unified']]
                    ],
                    [
                        'name' => 'MacBook Pro 14 M3 Pro 18GB RAM',
                        'price' => 49990000,
                        'price_retail' => 52990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'M3 Pro (11-core CPU, 14-core GPU)'], ['ten' => 'RAM', 'gia_tri' => '18GB Unified']]
                    ]
                ]
            ],
            [
                'name' => 'Apple MacBook Air 13 M3',
                'category' => 'Laptop',
                'brand' => 'Apple',
                'desc' => 'Laptop siêu mỏng nhẹ phổ biến nhất thế giới nay nâng tầm với chip M3, hỗ trợ xuất tới 2 màn hình ngoài khi gập máy.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '13.6 inch, Liquid Retina OLED'],
                    ['ten' => 'CPU', 'gia_tri' => 'M3 (8-core CPU, 8-core GPU)'],
                    ['ten' => 'Trọng lượng', 'gia_tri' => '1.24 kg']
                ],
                'variants' => [
                    [
                        'name' => 'MacBook Air 13 M3 8GB/256GB',
                        'price' => 27490000,
                        'price_retail' => 29490000,
                        'stock' => 35,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '8GB Unified'], ['ten' => 'SSD', 'gia_tri' => '256GB SSD']]
                    ],
                    [
                        'name' => 'MacBook Air 13 M3 16GB/512GB',
                        'price' => 34490000,
                        'price_retail' => 36490000,
                        'stock' => 25,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB Unified'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ]
                ]
            ],
            [
                'name' => 'HP Victus 16 2024',
                'category' => 'Laptop',
                'brand' => 'HP',
                'desc' => 'Dòng laptop gaming tối giản, thiết kế đẹp nhẹ nhàng phù hợp đi học đi làm nhưng chứa sức mạnh đồ hoạ mạnh mẽ bên trong.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16.1 inch, FHD IPS, 144Hz'],
                    ['ten' => 'SSD', 'gia_tri' => '512GB PCIe NVMe M.2']
                ],
                'variants' => [
                    [
                        'name' => 'HP Victus 16 Ryzen 5 RTX 4050',
                        'price' => 19990000,
                        'price_retail' => 21990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'AMD Ryzen 5 7640HS'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4050 6GB']]
                    ],
                    [
                        'name' => 'HP Victus 16 Core i7 RTX 4060',
                        'price' => 25990000,
                        'price_retail' => 27990000,
                        'stock' => 18,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-14700HX'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'HP Spectre x360 14 2024',
                'category' => 'Laptop',
                'brand' => 'HP',
                'desc' => 'Laptop xoay gập 360 độ cao cấp nhất của HP, chất liệu nhôm nguyên khối cắt kim cương sắc sảo và màn hình OLED cảm ứng rực rỡ.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '14 inch, 2.8K OLED cảm ứng, xoay 360 độ'],
                    ['ten' => 'CPU', 'gia_tri' => 'Intel Core Ultra 7 155H'],
                    ['ten' => 'VGA', 'gia_tri' => 'Intel Arc Graphics']
                ],
                'variants' => [
                    [
                        'name' => 'HP Spectre 16GB/1TB SSD',
                        'price' => 42990000,
                        'price_retail' => 45990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '1TB M.2 PCIe']]
                    ],
                    [
                        'name' => 'HP Spectre 32GB/2TB SSD',
                        'price' => 49990000,
                        'price_retail' => 52990000,
                        'stock' => 5,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '32GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '2TB M.2 PCIe']]
                    ]
                ]
            ],
            [
                'name' => 'Acer Nitro 16 Phoenix',
                'category' => 'Laptop',
                'brand' => 'Acer',
                'desc' => 'Trùm phân khúc tản nhiệt kim loại lỏng, màn hình tỷ lệ vàng 16:10 gaming, hoạt động bền bỉ, chiến game trơn tru không tụt FPS.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16 inch, WUXGA IPS, 165Hz, 100% sRGB'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB DDR5 (2x8GB)'],
                    ['ten' => 'SSD', 'gia_tri' => '512GB PCIe Gen4']
                ],
                'variants' => [
                    [
                        'name' => 'Nitro 16 R7 RTX 4050',
                        'price' => 23990000,
                        'price_retail' => 25990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'AMD Ryzen 7 7735HS'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4050 6GB']]
                    ],
                    [
                        'name' => 'Nitro 16 R7 RTX 4060',
                        'price' => 26990000,
                        'price_retail' => 28990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'AMD Ryzen 7 7840HS'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'Acer Predator Helios 16 2024',
                'category' => 'Laptop',
                'brand' => 'Acer',
                'desc' => 'Laptop gaming phân khúc cao cấp Predator sở hữu tản nhiệt quạt kim loại AeroBlade 3D thế hệ 5 và bàn phím RGB Per-key rực rỡ.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16 inch, WQXGA IPS, 240Hz, 500 nits'],
                    ['ten' => 'CPU', 'gia_tri' => 'Intel Core i9-14900HX'],
                    ['ten' => 'SSD', 'gia_tri' => '1TB PCIe Gen4 NVMe']
                ],
                'variants' => [
                    [
                        'name' => 'Predator Helios RTX 4060 16GB',
                        'price' => 48990000,
                        'price_retail' => 51990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ],
                    [
                        'name' => 'Predator Helios RTX 4070 32GB',
                        'price' => 56990000,
                        'price_retail' => 59990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '32GB DDR5 (2x16GB)'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4070 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'Gigabyte G5 Gaming Laptop',
                'category' => 'Laptop',
                'brand' => 'Gigabyte',
                'desc' => 'Sự kết hợp hoàn hảo giữa tính di động cao và cấu hình vừa tầm, giá bán siêu tốt dễ tiếp cận cho sinh viên công nghệ.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '15.6 inch, FHD IPS, 144Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Intel Core i5-12500H'],
                    ['ten' => 'SSD', 'gia_tri' => '512GB M.2 PCIe']
                ],
                'variants' => [
                    [
                        'name' => 'Gigabyte G5 RTX 3050 8GB RAM',
                        'price' => 15990000,
                        'price_retail' => 17990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '8GB DDR4'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 3050 4GB']]
                    ],
                    [
                        'name' => 'Gigabyte G5 RTX 4050 16GB RAM',
                        'price' => 18990000,
                        'price_retail' => 20990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB DDR4'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4050 6GB']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 3. MÁY TÍNH ĐỂ BÀN (8 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Apple iMac 24 inch M3',
                'category' => 'Máy tính để bàn',
                'brand' => 'Apple',
                'desc' => 'Máy tính All-in-One siêu mỏng phong cách All-in-One, màn hình Retina 4.5K hiển thị 1 tỷ màu, phụ kiện Magic Mouse/Keyboard đồng điệu.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '24 inch Retina 4.5K (4480x2520), 500 nits'],
                    ['ten' => 'CPU', 'gia_tri' => 'Apple M3 (8-Core CPU)'],
                    ['ten' => 'RAM', 'gia_tri' => '8GB Unified'],
                    ['ten' => 'HĐH', 'gia_tri' => 'macOS Sonoma']
                ],
                'variants' => [
                    [
                        'name' => 'iMac 24 M3 8-Core GPU/256GB',
                        'price' => 36990000,
                        'price_retail' => 39990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'VGA', 'gia_tri' => '8-Core GPU'], ['ten' => 'SSD', 'gia_tri' => '256GB SSD']]
                    ],
                    [
                        'name' => 'iMac 24 M3 10-Core GPU/512GB',
                        'price' => 41990000,
                        'price_retail' => 45990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'VGA', 'gia_tri' => '10-Core GPU'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ]
                ]
            ],
            [
                'name' => 'Apple Mac Mini M3 2024',
                'category' => 'Máy tính để bàn',
                'brand' => 'Apple',
                'desc' => 'Chiếc hộp máy tính mini nhỏ gọn nhưng sở hữu sức mạnh đồ hoạ của chip M3, chạy êm ái cực kỳ tiết kiệm điện năng.',
                'specs' => [
                    ['ten' => 'CPU', 'gia_tri' => 'Apple M3 (8-core CPU, 10-core GPU)'],
                    ['ten' => 'Kích thước', 'gia_tri' => '19.7 x 19.7 x 3.58 cm'],
                    ['ten' => 'HĐH', 'gia_tri' => 'macOS']
                ],
                'variants' => [
                    [
                        'name' => 'Mac Mini M3 8GB RAM/256GB SSD',
                        'price' => 15990000,
                        'price_retail' => 17990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '8GB Unified'], ['ten' => 'SSD', 'gia_tri' => '256GB SSD']]
                    ],
                    [
                        'name' => 'Mac Mini M3 16GB RAM/512GB SSD',
                        'price' => 21990000,
                        'price_retail' => 23990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB Unified'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ]
                ]
            ],
            [
                'name' => 'PC Gaming ASUS ROG Strix GT15',
                'category' => 'Máy tính để bàn',
                'brand' => 'ASUS',
                'desc' => 'PC Gaming nguyên bộ của ASUS ROG, thiết kế case hầm hố có quai xách chắc chắn, đèn LED Aura Sync đồng bộ ấn tượng.',
                'specs' => [
                    ['ten' => 'RAM', 'gia_tri' => '16GB DDR4 3200MHz'],
                    ['ten' => 'SSD', 'gia_tri' => '512GB M.2 NVMe PCIe'],
                    ['ten' => 'Mainboard', 'gia_tri' => 'Intel B760 Chipset']
                ],
                'variants' => [
                    [
                        'name' => 'ROG GT15 i5/RTX 3060',
                        'price' => 22990000,
                        'price_retail' => 24990000,
                        'stock' => 12,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i5-13400F'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 3060 12GB']]
                    ],
                    [
                        'name' => 'ROG GT15 i7/RTX 4060',
                        'price' => 28990000,
                        'price_retail' => 30990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i7-13700F'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'PC Dell Inspiron 3020 Tower',
                'category' => 'Máy tính để bàn',
                'brand' => 'Dell',
                'desc' => 'PC đồng bộ văn phòng Dell bền bỉ, chạy êm ái ổn định cao, đầy đủ các cổng kết nối và có sẵn Wifi tích hợp thuận tiện.',
                'specs' => [
                    ['ten' => 'RAM', 'gia_tri' => '8GB DDR4 3200MHz'],
                    ['ten' => 'HĐH', 'gia_tri' => 'Windows 11 Home + Office Home & Student']
                ],
                'variants' => [
                    [
                        'name' => 'Dell Inspiron 3020 i3/256GB SSD',
                        'price' => 9990000,
                        'price_retail' => 10990000,
                        'stock' => 40,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i3-13100'], ['ten' => 'SSD', 'gia_tri' => '256GB PCIe SSD']]
                    ],
                    [
                        'name' => 'Dell Inspiron 3020 i5/512GB SSD',
                        'price' => 13990000,
                        'price_retail' => 14990000,
                        'stock' => 35,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i5-13400'], ['ten' => 'SSD', 'gia_tri' => '512GB PCIe SSD']]
                    ]
                ]
            ],
            [
                'name' => 'PC HP Pavilion TP01 Desktop',
                'category' => 'Máy tính để bàn',
                'brand' => 'HP',
                'desc' => 'Thiết kế sang trọng với mặt trước bằng nhôm xước bạc, tối ưu công việc văn phòng hiệu suất tốt cùng độ bền thương hiệu HP.',
                'specs' => [
                    ['ten' => 'SSD', 'gia_tri' => '512GB M.2 NVMe PCIe'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Wifi 5 + Bluetooth 5.0']
                ],
                'variants' => [
                    [
                        'name' => 'HP Pavilion TP01 i5/8GB RAM',
                        'price' => 12990000,
                        'price_retail' => 13990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i5-13400'], ['ten' => 'RAM', 'gia_tri' => '8GB DDR4']]
                    ],
                    [
                        'name' => 'HP Pavilion TP01 i7/16GB RAM',
                        'price' => 17990000,
                        'price_retail' => 18990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i7-13700'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR4']]
                    ]
                ]
            ],
            [
                'name' => 'PC Lenovo IdeaCentre 3 07IRH8',
                'category' => 'Máy tính để bàn',
                'brand' => 'Lenovo',
                'desc' => 'Thùng máy văn phòng phom nhỏ (Small Form Factor) tiết kiệm tối đa không gian bàn làm việc của bạn.',
                'specs' => [
                    ['ten' => 'SSD', 'gia_tri' => '256GB M.2 Gen4 SSD'],
                    ['ten' => 'Kích thước', 'gia_tri' => 'Case nhỏ gọn 7.4L']
                ],
                'variants' => [
                    [
                        'name' => 'Lenovo IdeaCentre i3/8GB RAM',
                        'price' => 8490000,
                        'price_retail' => 8990000,
                        'stock' => 45,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i3-13100'], ['ten' => 'RAM', 'gia_tri' => '8GB DDR4']]
                    ]
                ]
            ],
            [
                'name' => 'PC Gaming MSI MAG Infinite S3',
                'category' => 'Máy tính để bàn',
                'brand' => 'MSI',
                'desc' => 'Hệ thống PC chơi game mạnh mẽ tích hợp các giải pháp làm mát Silent Storm Cooling tiên tiến của MSI.',
                'specs' => [
                    ['ten' => 'RAM', 'gia_tri' => '16GB DDR5 5600MHz'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Wifi 6E + Bluetooth 5.3']
                ],
                'variants' => [
                    [
                        'name' => 'MSI Infinite S3 i5/RTX 4060',
                        'price' => 21990000,
                        'price_retail' => 23990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i5-13400F'], ['ten' => 'SSD', 'gia_tri' => '512GB PCIe SSD'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ],
                    [
                        'name' => 'MSI Infinite S3 i7/RTX 4060Ti',
                        'price' => 27990000,
                        'price_retail' => 29990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-13700F'], ['ten' => 'SSD', 'gia_tri' => '1TB PCIe SSD'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 Ti 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'PC Acer Aspire XC-1780',
                'category' => 'Máy tính để bàn',
                'brand' => 'Acer',
                'desc' => 'Máy tính đồng bộ phổ thông cho văn phòng, hộ gia đình đáp ứng hoàn hảo nhu cầu xem phim, kế toán cơ bản.',
                'specs' => [
                    ['ten' => 'RAM', 'gia_tri' => '8GB DDR4'],
                    ['ten' => 'HĐH', 'gia_tri' => 'Windows 11 Home']
                ],
                'variants' => [
                    [
                        'name' => 'Acer Aspire i3/256GB SSD',
                        'price' => 8990000,
                        'price_retail' => 9490000,
                        'stock' => 30,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i3-13100'], ['ten' => 'SSD', 'gia_tri' => '256GB SSD']]
                    ],
                    [
                        'name' => 'Acer Aspire i5/512GB SSD',
                        'price' => 11990000,
                        'price_retail' => 12490000,
                        'stock' => 25,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i5-13400'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 6. CPU (12 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Intel Core i9-14900K',
                'category' => 'CPU',
                'brand' => 'Intel',
                'desc' => 'Bộ vi xử lý máy tính để bàn thế hệ 14 mạnh mẽ nhất của Intel với 24 nhân 32 luồng, xung nhịp turbo đạt tới 6.0 GHz.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '24 nhân / 32 luồng'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '3.2 GHz (Cơ bản) / 6.0 GHz (Turbo)'],
                    ['ten' => 'Socket', 'gia_tri' => 'LGA 1700'],
                    ['ten' => 'TDP', 'gia_tri' => '125W (Max 253W)']
                ],
                'variants' => [
                    [
                        'name' => 'Core i9-14900K Box Chính Hãng',
                        'price' => 15490000,
                        'price_retail' => 16990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box có quạt']]
                    ],
                    [
                        'name' => 'Core i9-14900K Tray',
                        'price' => 14990000,
                        'price_retail' => 15990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Tray không box']]
                    ]
                ]
            ],
            [
                'name' => 'Intel Core i7-14700K',
                'category' => 'CPU',
                'brand' => 'Intel',
                'desc' => 'Bộ vi xử lý cận cao cấp cực kỳ đáng mua, nâng cấp thêm nhân E-core giúp tăng tốc render đồ hoạ và đa nhiệm mượt mà.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '20 nhân / 28 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'LGA 1700'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '3.4 GHz / 5.6 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Core i7-14700K Box',
                        'price' => 10490000,
                        'price_retail' => 11490000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ],
                    [
                        'name' => 'Core i7-14700K Tray',
                        'price' => 9990000,
                        'price_retail' => 10490000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Tray']]
                    ]
                ]
            ],
            [
                'name' => 'Intel Core i5-14600K',
                'category' => 'CPU',
                'brand' => 'Intel',
                'desc' => 'Sự lựa chọn CPU chiến game hoàn hảo cho các cấu hình tầm trung cao cấp với hiệu năng đơn nhân đỉnh cao.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '14 nhân / 20 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'LGA 1700'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '3.5 GHz / 5.3 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Core i5-14600K Box',
                        'price' => 7890000,
                        'price_retail' => 8490000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ],
                    [
                        'name' => 'Core i5-14600K Tray',
                        'price' => 7390000,
                        'price_retail' => 7890000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Tray']]
                    ]
                ]
            ],
            [
                'name' => 'Intel Core i5-12400F',
                'category' => 'CPU',
                'brand' => 'Intel',
                'desc' => 'Bộ vi xử lý huyền thoại quốc dân chơi game siêu rẻ, không tích hợp card đồ hoạ để có mức giá tối ưu nhất.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '6 nhân / 12 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'LGA 1700'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '2.5 GHz / 4.4 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Core i5-12400F Box',
                        'price' => 2990000,
                        'price_retail' => 3490000,
                        'stock' => 60,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ],
                    [
                        'name' => 'Core i5-12400F Tray',
                        'price' => 2690000,
                        'price_retail' => 2990000,
                        'stock' => 80,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Tray']]
                    ]
                ]
            ],
            [
                'name' => 'Intel Core i3-12100F',
                'category' => 'CPU',
                'brand' => 'Intel',
                'desc' => 'Sự lựa chọn tốt nhất cho các cấu hình PC gaming giá rẻ dưới 10 triệu đồng.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '4 nhân / 8 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'LGA 1700'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '3.3 GHz / 4.3 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Core i3-12100F Box',
                        'price' => 1890000,
                        'price_retail' => 2190000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 9 7950X',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'CPU AMD Socket AM5 cao cấp nhất dành cho máy trạm làm việc chuyên nghiệp, dựng phim 3D và chạy máy ảo.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '16 nhân / 32 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'AM5'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '4.5 GHz / 5.7 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Ryzen 9 7950X Box',
                        'price' => 14490000,
                        'price_retail' => 15490000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 9 7900X',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'CPU mạnh mẽ sử dụng kiến trúc Zen 4 mới nhất cùng khả năng tiết kiệm điện ấn tượng.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '12 nhân / 24 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'AM5'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '4.7 GHz / 5.6 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Ryzen 9 7900X Box',
                        'price' => 10190000,
                        'price_retail' => 10990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 7 7800X3D',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'CPU chơi game mạnh nhất thế giới nhờ công nghệ bộ nhớ đệm xếp chồng 3D V-Cache độc quyền cực khủng.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '8 nhân / 16 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'AM5'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '4.2 GHz / 5.0 GHz'],
                    ['ten' => 'Bộ nhớ đệm L3', 'gia_tri' => '96MB']
                ],
                'variants' => [
                    [
                        'name' => 'Ryzen 7 7800X3D Box',
                        'price' => 10790000,
                        'price_retail' => 11490000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 7 7700X',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'Lựa chọn cân bằng cho game thủ và người sáng tạo nội dung phân khúc cận cao cấp.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '8 nhân / 16 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'AM5'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '4.5 GHz / 5.4 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Ryzen 7 7700X Box',
                        'price' => 7690000,
                        'price_retail' => 8290000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 5 7600X',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'CPU tầm trung thế hệ mới của AMD mang lại sức mạnh chơi game xuất sắc vượt tầm tiền.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '6 nhân / 12 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'AM5'],
                    ['ten' => 'Xung nhịp', 'gia_tri' => '4.7 GHz / 5.3 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Ryzen 5 7600X Box',
                        'price' => 5490000,
                        'price_retail' => 5990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 5 5600G',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'CPU tích hợp nhân đồ họa Radeon cực mạnh, chiến mượt các game Esport như LOL, Valorant mà không cần mua VGA rời.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '6 nhân / 12 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'AM4'],
                    ['ten' => 'Đồ họa tích hợp', 'gia_tri' => 'Radeon Graphics (7 Cores)']
                ],
                'variants' => [
                    [
                        'name' => 'Ryzen 5 5600G Box',
                        'price' => 3190000,
                        'price_retail' => 3590000,
                        'stock' => 45,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 5 3600',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'CPU huyền thoại kiến trúc Zen 2, phù hợp nâng cấp cho các dàn PC cũ giá siêu rẻ.',
                'specs' => [
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '6 nhân / 12 luồng'],
                    ['ten' => 'Socket', 'gia_tri' => 'AM4']
                ],
                'variants' => [
                    [
                        'name' => 'Ryzen 5 3600 Box',
                        'price' => 1890000,
                        'price_retail' => 2190000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Loại hàng', 'gia_tri' => 'Box']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 7. CARD ĐỒ HỌA (12 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'NVIDIA GeForce RTX 4090 Founders Edition',
                'category' => 'Card đồ họa',
                'brand' => 'NVIDIA',
                'desc' => 'GPU chơi game mạnh mẽ đỉnh cao thế giới, mang đến bước nhảy vọt khổng lồ về hiệu năng nhờ công nghệ DLSS 3 AI.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '24GB GDDR6X'],
                    ['ten' => 'Băng thông', 'gia_tri' => '384-bit'],
                    ['ten' => 'Cổng kết nối', 'gia_tri' => '1x HDMI 2.1, 3x DisplayPort 1.4a']
                ],
                'variants' => [
                    [
                        'name' => 'RTX 4090 Founders Edition 24GB',
                        'price' => 54990000,
                        'price_retail' => 59990000,
                        'stock' => 5,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'FE (Founders Edition)']]
                    ]
                ]
            ],
            [
                'name' => 'ASUS ROG Strix GeForce RTX 4080 Super',
                'category' => 'Card đồ họa',
                'brand' => 'ASUS',
                'desc' => 'Thiết kế hầm hố nhất phân khúc với tản nhiệt quạt khổng lồ và hiệu ứng LED ARGB nổi bật đẳng cấp ROG Strix.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '16GB GDDR6X'],
                    ['ten' => 'Băng thông', 'gia_tri' => '256-bit'],
                    ['ten' => 'Nguồn khuyến cáo', 'gia_tri' => '850W']
                ],
                'variants' => [
                    [
                        'name' => 'ROG Strix RTX 4080 Super Standard',
                        'price' => 32990000,
                        'price_retail' => 35990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Tần số xung', 'gia_tri' => '2580 MHz']]
                    ],
                    [
                        'name' => 'ROG Strix RTX 4080 Super OC Edition',
                        'price' => 34990000,
                        'price_retail' => 37990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'Tần số xung', 'gia_tri' => '2670 MHz (OC Mode)']]
                    ]
                ]
            ],
            [
                'name' => 'MSI GeForce RTX 4070 Ti Super Gaming X Slim',
                'category' => 'Card đồ họa',
                'brand' => 'MSI',
                'desc' => 'Thiết kế mỏng nhẹ hơn (Slim) giúp tương thích tốt với nhiều dòng case nhỏ nhưng vẫn mang lại hiệu suất tản nhiệt đỉnh cao.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '16GB GDDR6X'],
                    ['ten' => 'Băng thông', 'gia_tri' => '256-bit']
                ],
                'variants' => [
                    [
                        'name' => 'RTX 4070 Ti Super Slim Black',
                        'price' => 26490000,
                        'price_retail' => 28490000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'RTX 4070 Ti Super Slim White',
                        'price' => 26990000,
                        'price_retail' => 28990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Gigabyte GeForce RTX 4070 Super Windforce OC',
                'category' => 'Card đồ họa',
                'brand' => 'Gigabyte',
                'desc' => 'Trang bị 3 quạt làm mát Windforce quay ngược chiều tối ưu khí động học, tăng diện tích tản nhiệt cho GPU mát mẻ.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '12GB GDDR6X'],
                    ['ten' => 'Băng thông', 'gia_tri' => '192-bit']
                ],
                'variants' => [
                    [
                        'name' => 'RTX 4070 Super Windforce 12G',
                        'price' => 18490000,
                        'price_retail' => 19990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Standard']]
                    ]
                ]
            ],
            [
                'name' => 'ASUS Dual GeForce RTX 4060 Ti',
                'category' => 'Card đồ họa',
                'brand' => 'ASUS',
                'desc' => 'Thiết kế 2 quạt gọn gàng lý tưởng cho cấu hình ITX hoặc thùng máy nhỏ, vẫn mang đầy đủ sức mạnh xử lý hình ảnh thế hệ mới.',
                'specs' => [
                    ['ten' => 'Loại bộ nhớ', 'gia_tri' => 'GDDR6'],
                    ['ten' => 'Băng thông', 'gia_tri' => '128-bit']
                ],
                'variants' => [
                    [
                        'name' => 'ASUS Dual RTX 4060 Ti 8GB',
                        'price' => 11490000,
                        'price_retail' => 12490000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Dung lượng VRAM', 'gia_tri' => '8GB']]
                    ],
                    [
                        'name' => 'ASUS Dual RTX 4060 Ti 16GB',
                        'price' => 13490000,
                        'price_retail' => 14490000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng VRAM', 'gia_tri' => '16GB']]
                    ]
                ]
            ],
            [
                'name' => 'MSI GeForce RTX 4060 Ventus 2X OC',
                'category' => 'Card đồ họa',
                'brand' => 'MSI',
                'desc' => 'Card đồ họa quốc dân chiến game Full HD mượt mà, siêu tiết kiệm điện chỉ ăn tối đa khoảng 115W.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '8GB GDDR6'],
                    ['ten' => 'Băng thông', 'gia_tri' => '128-bit']
                ],
                'variants' => [
                    [
                        'name' => 'MSI RTX 4060 Ventus 2X 8G OC',
                        'price' => 8490000,
                        'price_retail' => 9490000,
                        'stock' => 45,
                        'specs' => [['ten' => 'Xung nhịp', 'gia_tri' => '2505 MHz (Boost Clock)']]
                    ]
                ]
            ],
            [
                'name' => 'Gigabyte GeForce GTX 1650 D6 OC',
                'category' => 'Card đồ họa',
                'brand' => 'Gigabyte',
                'desc' => 'Card đồ họa phân cấp giá rẻ sử dụng bộ nhớ GDDR6 nâng cấp, giúp học tập đồ họa nhẹ và chơi game Esport ổn định.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '4GB GDDR6'],
                    ['ten' => 'Băng thông', 'gia_tri' => '128-bit']
                ],
                'variants' => [
                    [
                        'name' => 'Gigabyte GTX 1650 D6 OC 4G',
                        'price' => 3490000,
                        'price_retail' => 3890000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Cổng kết nối', 'gia_tri' => '1x DisplayPort, 1x HDMI, 1x DVI-D']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Radeon RX 7900 XTX Reference Design',
                'category' => 'Card đồ họa',
                'brand' => 'AMD',
                'desc' => 'Card màn hình đầu bảng sử dụng thiết kế nguyên bản của AMD, bộ nhớ VRAM khổng lồ 24GB chiến game 4K cực tốt.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '24GB GDDR6'],
                    ['ten' => 'Băng thông', 'gia_tri' => '384-bit']
                ],
                'variants' => [
                    [
                        'name' => 'Radeon RX 7900 XTX 24G',
                        'price' => 28990000,
                        'price_retail' => 30990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Reference (Bản gốc)']]
                    ]
                ]
            ],
            [
                'name' => 'ASUS TUF Gaming Radeon RX 7800 XT OC',
                'category' => 'Card đồ họa',
                'brand' => 'ASUS',
                'desc' => 'Dòng TUF Gaming siêu bền bỉ với khung kim loại, quạt vòng bi kép và linh kiện chuẩn quân đội.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '16GB GDDR6'],
                    ['ten' => 'Băng thông', 'gia_tri' => '256-bit']
                ],
                'variants' => [
                    [
                        'name' => 'ASUS TUF RX 7800 XT OC 16G',
                        'price' => 15990000,
                        'price_retail' => 16990000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Xung nhịp', 'gia_tri' => '2565 MHz (OC Mode)']]
                    ]
                ]
            ],
            [
                'name' => 'MSI Radeon RX 6600 Mech 2X',
                'category' => 'Card đồ họa',
                'brand' => 'MSI',
                'desc' => 'Sự lựa chọn card chơi game 1080p hiệu năng tốt nhất trong tầm giá rẻ của phe Đỏ.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '8GB GDDR6'],
                    ['ten' => 'Băng thông', 'gia_tri' => '128-bit']
                ],
                'variants' => [
                    [
                        'name' => 'MSI RX 6600 Mech 2X 8G',
                        'price' => 5990000,
                        'price_retail' => 6490000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Nguồn khuyên dùng', 'gia_tri' => '500W']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair 12VHPWR GPU Power Bridge',
                'category' => 'Card đồ họa',
                'brand' => 'Corsair',
                'desc' => 'Đầu chuyển nguồn bẻ góc 180 độ cho các dòng card RTX 40-series, giúp đi dây nguồn gọn gàng đẹp mắt tránh gập cáp.',
                'specs' => [
                    ['ten' => 'Chuẩn đầu kết nối', 'gia_tri' => '12VHPWR (16-pin)'],
                    ['ten' => 'Góc bẻ', 'gia_tri' => '180 độ']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair GPU Power Bridge 180 Degree',
                        'price' => 790000,
                        'price_retail' => 890000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Gigabyte AORUS GeForce RTX 4080 Master',
                'category' => 'Card đồ họa',
                'brand' => 'Gigabyte',
                'desc' => 'Dòng Master cao cấp tích hợp màn hình LCD Edge View hiển thị nhiệt độ, thông số GPU hoặc hình ảnh GIF cá nhân hoá.',
                'specs' => [
                    ['ten' => 'Bộ nhớ', 'gia_tri' => '16GB GDDR6X'],
                    ['ten' => 'Màn hình phụ', 'gia_tri' => 'LCD Edge View']
                ],
                'variants' => [
                    [
                        'name' => 'AORUS RTX 4080 Master 16G',
                        'price' => 35990000,
                        'price_retail' => 38990000,
                        'stock' => 6,
                        'specs' => [['ten' => 'Kích thước', 'gia_tri' => '35.7 x 16.3 x 7.5 cm']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 8. RAM (10 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Kingston Fury Beast DDR5',
                'category' => 'RAM',
                'brand' => 'Kingston',
                'desc' => 'Ram DDR5 chất lượng cao của Kingston, tương thích cực tốt với các bo mạch chủ Intel và AMD thế hệ mới.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR5'],
                    ['ten' => 'Hiệu điện thế', 'gia_tri' => '1.25V']
                ],
                'variants' => [
                    [
                        'name' => 'Kingston Fury Beast DDR5 16GB 5600MHz',
                        'price' => 1590000,
                        'price_retail' => 1790000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '16GB (1x16GB)'], ['ten' => 'Bus RAM', 'gia_tri' => '5600MHz']]
                    ],
                    [
                        'name' => 'Kingston Fury Beast DDR5 32GB (2x16GB) 5600MHz',
                        'price' => 3090000,
                        'price_retail' => 3390000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '32GB (2x16GB)'], ['ten' => 'Bus RAM', 'gia_tri' => '5600MHz']]
                    ]
                ]
            ],
            [
                'name' => 'Kingston Fury Renegade RGB DDR5',
                'category' => 'RAM',
                'brand' => 'Kingston',
                'desc' => 'Dòng RAM chuyên cho ép xung (overclock) với tản nhiệt nhôm cao cấp kết hợp dải đèn LED RGB chuyển động mượt mà.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR5'],
                    ['ten' => 'Độ trễ CAS', 'gia_tri' => 'CL32']
                ],
                'variants' => [
                    [
                        'name' => 'Fury Renegade RGB 32GB (2x16GB) 6000MHz',
                        'price' => 3690000,
                        'price_retail' => 3990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '32GB (2x16GB)'], ['ten' => 'Bus RAM', 'gia_tri' => '6000MHz']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair Vengeance RGB DDR5',
                'category' => 'RAM',
                'brand' => 'Corsair',
                'desc' => 'Dòng RAM DDR5 nổi tiếng của Corsair, phần mềm iCUE quản lý LED RGB vô cùng thông minh và đa dạng hiệu ứng độc quyền.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR5'],
                    ['ten' => 'Hỗ trợ cấu hình', 'gia_tri' => 'Intel XMP 3.0']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair Vengeance RGB DDR5 32GB 5600MHz',
                        'price' => 2990000,
                        'price_retail' => 3290000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '32GB (2x16GB)'], ['ten' => 'Bus RAM', 'gia_tri' => '5600MHz']]
                    ],
                    [
                        'name' => 'Corsair Vengeance RGB DDR5 64GB 5600MHz',
                        'price' => 5690000,
                        'price_retail' => 6090000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '64GB (2x32GB)'], ['ten' => 'Bus RAM', 'gia_tri' => '5600MHz']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair Dominator Titanium DDR5',
                'category' => 'RAM',
                'brand' => 'Corsair',
                'desc' => 'Dòng RAM DDR5 cao cấp nhất của Corsair, thiết kế nhôm rèn tinh xảo, hiệu năng ép xung cực khủng.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR5'],
                    ['ten' => 'Bus RAM', 'gia_tri' => '6000MHz']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair Dominator Titanium 32GB (2x16GB)',
                        'price' => 4990000,
                        'price_retail' => 5390000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '32GB (2x16GB)']]
                    ]
                ]
            ],
            [
                'name' => 'G.Skill Trident Z5 Neo RGB',
                'category' => 'RAM',
                'brand' => 'ASUS',
                'desc' => 'Tối ưu hoá tối đa cho nền tảng vi xử lý AMD Ryzen thế hệ mới, hỗ trợ công nghệ AMD EXPO ép xung dễ dàng.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR5'],
                    ['ten' => 'Bus RAM', 'gia_tri' => '6000MHz']
                ],
                'variants' => [
                    [
                        'name' => 'Trident Z5 Neo RGB 32GB (2x16GB)',
                        'price' => 3290000,
                        'price_retail' => 3590000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '32GB (2x16GB)']]
                    ]
                ]
            ],
            [
                'name' => 'Kingston Fury Beast DDR4',
                'category' => 'RAM',
                'brand' => 'Kingston',
                'desc' => 'Bộ nhớ RAM DDR4 quốc dân bền bỉ, chiều cao thanh ram thấp (Low Profile) tránh cấn quạt tản nhiệt CPU.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR4'],
                    ['ten' => 'Bus RAM', 'gia_tri' => '3200MHz']
                ],
                'variants' => [
                    [
                        'name' => 'Kingston Fury Beast DDR4 8GB',
                        'price' => 590000,
                        'price_retail' => 690000,
                        'stock' => 100,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '8GB (1x8GB)']]
                    ],
                    [
                        'name' => 'Kingston Fury Beast DDR4 16GB (2x8GB)',
                        'price' => 1150000,
                        'price_retail' => 1290000,
                        'stock' => 80,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '16GB (2x8GB)']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair Vengeance LPX DDR4',
                'category' => 'RAM',
                'brand' => 'Corsair',
                'desc' => 'Tản nhiệt nhôm tinh khiết giúp truyền nhiệt nhanh hơn cho hiệu năng ổn định tối đa.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR4'],
                    ['ten' => 'Bus RAM', 'gia_tri' => '3200MHz']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair Vengeance LPX DDR4 8GB',
                        'price' => 550000,
                        'price_retail' => 650000,
                        'stock' => 90,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '8GB (1x8GB)']]
                    ],
                    [
                        'name' => 'Corsair Vengeance LPX DDR4 16GB',
                        'price' => 990000,
                        'price_retail' => 1190000,
                        'stock' => 70,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '16GB (1x16GB)']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung DDR5 SO-DIMM Laptop RAM',
                'category' => 'RAM',
                'brand' => 'Samsung',
                'desc' => 'RAM nâng cấp cho các dòng Laptop Gaming và Laptop văn phòng đời mới sử dụng chuẩn DDR5.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR5 SO-DIMM (Laptop)'],
                    ['ten' => 'Bus RAM', 'gia_tri' => '4800MHz']
                ],
                'variants' => [
                    [
                        'name' => 'Samsung DDR5 SO-DIMM 16GB',
                        'price' => 1290000,
                        'price_retail' => 1490000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '16GB']]
                    ],
                    [
                        'name' => 'Samsung DDR5 SO-DIMM 32GB',
                        'price' => 2490000,
                        'price_retail' => 2790000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '32GB']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung DDR4 SO-DIMM Laptop RAM',
                'category' => 'RAM',
                'brand' => 'Samsung',
                'desc' => 'Ram nâng cấp chuẩn DDR4 cho Laptop, hoạt động ổn định và tiết kiệm điện năng.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR4 SO-DIMM (Laptop)'],
                    ['ten' => 'Bus RAM', 'gia_tri' => '3200MHz']
                ],
                'variants' => [
                    [
                        'name' => 'Samsung DDR4 SO-DIMM 8GB',
                        'price' => 550000,
                        'price_retail' => 650000,
                        'stock' => 60,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '8GB']]
                    ],
                    [
                        'name' => 'Samsung DDR4 SO-DIMM 16GB',
                        'price' => 990000,
                        'price_retail' => 1190000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '16GB']]
                    ]
                ]
            ],
            [
                'name' => 'Gigabyte Aorus RGB Memory DDR4',
                'category' => 'RAM',
                'brand' => 'Gigabyte',
                'desc' => 'Thanh RAM cao cấp tích hợp LED RGB rực rỡ và kèm sẵn 2 thanh Demo giúp cắm kín khe RAM bo mạch chủ lung linh hơn.',
                'specs' => [
                    ['ten' => 'Chuẩn RAM', 'gia_tri' => 'DDR4 Kit (2x8GB)'],
                    ['ten' => 'Bus RAM', 'gia_tri' => '3733MHz']
                ],
                'variants' => [
                    [
                        'name' => 'Aorus RGB Memory DDR4 16GB',
                        'price' => 2190000,
                        'price_retail' => 2390000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '16GB (2x8GB)']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 9. Ổ CỨNG SSD (10 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Samsung 990 Pro PCIe 4.0 NVMe M.2 SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Samsung',
                'desc' => 'Ổ cứng SSD PCIe 4.0 nhanh nhất thế giới của Samsung, tốc độ đọc ghi tiệm cận giới hạn vật lý cổng giao tiếp.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '7450 MB/s'],
                    ['ten' => 'Tốc độ ghi tối đa', 'gia_tri' => '6900 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'Samsung 990 Pro 1TB',
                        'price' => 2890000,
                        'price_retail' => 3190000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ],
                    [
                        'name' => 'Samsung 990 Pro 2TB',
                        'price' => 4990000,
                        'price_retail' => 5490000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '2TB']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung 980 Pro PCIe 4.0 NVMe M.2 SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Samsung',
                'desc' => 'Lựa chọn ổ cứng SSD PCIe 4.0 cao cấp vô cùng phổ biến cho PC và nâng cấp bộ nhớ PS5.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '7000 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'Samsung 980 Pro 1TB',
                        'price' => 2190000,
                        'price_retail' => 2490000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ],
                    [
                        'name' => 'Samsung 980 Pro 2TB',
                        'price' => 3990000,
                        'price_retail' => 4390000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '2TB']]
                    ]
                ]
            ],
            [
                'name' => 'Kingston NV2 PCIe 4.0 NVMe M.2 SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Kingston',
                'desc' => 'Ổ cứng SSD NVMe phân cấp phổ thông giá rẻ nhất thị trường, lựa chọn hàng đầu để nâng cấp lưu trữ cho học sinh sinh viên.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '3500 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'Kingston NV2 NVMe 500GB',
                        'price' => 1090000,
                        'price_retail' => 1290000,
                        'stock' => 80,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '500GB']]
                    ],
                    [
                        'name' => 'Kingston NV2 NVMe 1TB',
                        'price' => 1690000,
                        'price_retail' => 1890000,
                        'stock' => 70,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ],
                    [
                        'name' => 'Kingston NV2 NVMe 2TB',
                        'price' => 3190000,
                        'price_retail' => 3490000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '2TB']]
                    ]
                ]
            ],
            [
                'name' => 'Kingston KC3000 PCIe 4.0 NVMe M.2 SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Kingston',
                'desc' => 'Ổ cứng SSD phân khúc cao cấp sử dụng bộ điều khiển Phison E18 và 3D TLC NAND cho hiệu năng vượt trội.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '7000 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'Kingston KC3000 1TB',
                        'price' => 2290000,
                        'price_retail' => 2590000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ],
                    [
                        'name' => 'Kingston KC3000 2TB',
                        'price' => 4190000,
                        'price_retail' => 4590000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '2TB']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair MP600 PRO LPX PCIe Gen4 M.2 SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Corsair',
                'desc' => 'Được trang bị sẵn lá tản nhiệt nhôm mỏng, tương thích hoàn hảo và tối ưu hoá cho máy chơi game PS5.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280 (Có sẵn tản nhiệt)'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '7100 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair MP600 PRO LPX 1TB',
                        'price' => 2490000,
                        'price_retail' => 2790000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ],
                    [
                        'name' => 'Corsair MP600 PRO LPX 2TB',
                        'price' => 4490000,
                        'price_retail' => 4890000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '2TB']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung 870 EVO SATA III 2.5 inch SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Samsung',
                'desc' => 'Ổ cứng chuẩn SATA III 2.5 inch truyền thống giúp nâng cấp bộ nhớ cho các máy tính đời cũ.',
                'specs' => [
                    ['ten' => 'Chuẩn cổng', 'gia_tri' => 'SATA III 2.5 inch'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '560 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'Samsung 870 EVO 500GB',
                        'price' => 1490000,
                        'price_retail' => 1690000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '500GB']]
                    ],
                    [
                        'name' => 'Samsung 870 EVO 1TB',
                        'price' => 2490000,
                        'price_retail' => 2790000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ]
                ]
            ],
            [
                'name' => 'Gigabyte AORUS Gen4 SSD M.2',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Gigabyte',
                'desc' => 'Trang bị tản nhiệt đồng nguyên khối cực dày bao phủ, giúp giải phóng nhiệt lượng tối đa và giữ tốc độ đọc cực cao.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280 (Tản nhiệt đồng)'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '5000 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'AORUS Gen4 SSD 1TB',
                        'price' => 2290000,
                        'price_retail' => 2490000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ]
                ]
            ],
            [
                'name' => 'MSI Spatium M480 PCIe 4.0 NVMe M.2 SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'MSI',
                'desc' => 'Bộ lưu trữ hàng đầu của MSI đem tới tốc độ truyền dữ liệu kinh ngạc cho máy tính của bạn.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '7000 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'MSI Spatium M480 1TB',
                        'price' => 2390000,
                        'price_retail' => 2690000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ]
                ]
            ],
            [
                'name' => 'Acer FA100 M.2 NVMe SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Acer',
                'desc' => 'Ổ cứng SSD chuẩn PCIe Gen3 hiệu năng ổn định, hoạt động êm mát thích hợp cho cả laptop.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280'],
                    ['ten' => 'Chuẩn cổng', 'gia_tri' => 'PCIe Gen3 x4']
                ],
                'variants' => [
                    [
                        'name' => 'Acer FA100 512GB',
                        'price' => 990000,
                        'price_retail' => 1190000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB']]
                    ]
                ]
            ],
            [
                'name' => 'Western Digital Black SN850X PCIe 4.0 SSD',
                'category' => 'Ổ cứng SSD',
                'brand' => 'Sony',
                'desc' => 'Ổ cứng SSD chơi game cực đỉnh của Western Digital, tối ưu thời gian tải game bản đồ rộng cho PS5 và PC.',
                'specs' => [
                    ['ten' => 'Kích thước', 'gia_tri' => 'M.2 2280'],
                    ['ten' => 'Tốc độ đọc tối đa', 'gia_tri' => '7300 MB/s']
                ],
                'variants' => [
                    [
                        'name' => 'WD Black SN850X 1TB',
                        'price' => 2590000,
                        'price_retail' => 2890000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '1TB']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 10. BÀN PHÍM (8 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Logitech G Pro X TKL Mechanical Keyboard',
                'category' => 'Bàn phím',
                'brand' => 'Logitech',
                'desc' => 'Bàn phím cơ thiết kế rút gọn TKL chuyên nghiệp dành cho vận động viên thể thao điện tử chuyên nghiệp.',
                'specs' => [
                    ['ten' => 'Layout', 'gia_tri' => 'Tenkeyless (TKL)'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Dây cáp tháo rời Micro USB'],
                    ['ten' => 'Đèn LED', 'gia_tri' => 'Lightsync RGB']
                ],
                'variants' => [
                    [
                        'name' => 'Logitech G Pro X Clicky Switch',
                        'price' => 2990000,
                        'price_retail' => 3290000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Loại Switch', 'gia_tri' => 'GX Blue Clicky']]
                    ],
                    [
                        'name' => 'Logitech G Pro X Linear Switch',
                        'price' => 2990000,
                        'price_retail' => 3290000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Loại Switch', 'gia_tri' => 'GX Red Linear']]
                    ]
                ]
            ],
            [
                'name' => 'Logitech MX Keys S Wireless Keyboard',
                'category' => 'Bàn phím',
                'brand' => 'Logitech',
                'desc' => 'Bàn phím không dây cao cấp tối ưu hóa cho lập trình viên và nhà thiết kế sáng tạo nội dung.',
                'specs' => [
                    ['ten' => 'Phím cơ học', 'gia_tri' => 'Không (Phím cao su cắt kéo MX cao cấp)'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Bluetooth Low Energy / Logi Bolt']
                ],
                'variants' => [
                    [
                        'name' => 'Logitech MX Keys S Graphite',
                        'price' => 2490000,
                        'price_retail' => 2690000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Xám Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Razer BlackWidow V4 Pro',
                'category' => 'Bàn phím',
                'brand' => 'Razer',
                'desc' => 'Bàn phím cơ đỉnh cao của Razer với núm xoay đa chức năng, 8 phím macro chuyên dụng và đèn LED gầm rực rỡ.',
                'specs' => [
                    ['ten' => 'Layout', 'gia_tri' => 'Full-size'],
                    ['ten' => 'Đèn LED', 'gia_tri' => 'Razer Chroma RGB']
                ],
                'variants' => [
                    [
                        'name' => 'BlackWidow V4 Pro Clicky Green',
                        'price' => 5690000,
                        'price_retail' => 5990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Loại Switch', 'gia_tri' => 'Razer Green Clicky']]
                    ],
                    [
                        'name' => 'BlackWidow V4 Pro Linear Yellow',
                        'price' => 5690000,
                        'price_retail' => 5990000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Loại Switch', 'gia_tri' => 'Razer Yellow Silent Linear']]
                    ]
                ]
            ],
            [
                'name' => 'Razer Huntsman V3 Pro TKL',
                'category' => 'Bàn phím',
                'brand' => 'Razer',
                'desc' => 'Trang bị switch quang học analog thế hệ 2 mới nhất của Razer với tính năng Rapid Trigger phản hồi siêu tốc độ.',
                'specs' => [
                    ['ten' => 'Layout', 'gia_tri' => 'Tenkeyless (TKL)'],
                    ['ten' => 'Loại Switch', 'gia_tri' => 'Razer Analog Optical Gen-2']
                ],
                'variants' => [
                    [
                        'name' => 'Huntsman V3 Pro TKL Black',
                        'price' => 5490000,
                        'price_retail' => 5790000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair K70 RGB PRO Mechanical Keyboard',
                'category' => 'Bàn phím',
                'brand' => 'Corsair',
                'desc' => 'Khung nhôm phay bền bỉ huyền thoại của dòng K70, tần số phản hồi cực cao AXON 8000Hz và nút chuyển đổi Tournament tiện lợi.',
                'specs' => [
                    ['ten' => 'Layout', 'gia_tri' => 'Full-size'],
                    ['ten' => 'Khung viền', 'gia_tri' => 'Nhôm phay hàng không'],
                    ['ten' => 'Đèn LED', 'gia_tri' => 'RGB từng phím']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair K70 PRO Cherry MX Red',
                        'price' => 3490000,
                        'price_retail' => 3790000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Loại Switch', 'gia_tri' => 'Cherry MX Red']]
                    ],
                    [
                        'name' => 'Corsair K70 PRO Cherry MX Speed',
                        'price' => 3590000,
                        'price_retail' => 3890000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Loại Switch', 'gia_tri' => 'Cherry MX Speed']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair K100 RGB Optical-Mechanical Keyboard',
                'category' => 'Bàn phím',
                'brand' => 'Corsair',
                'desc' => 'Flagship bàn phím chơi game cao cấp nhất của Corsair sở hữu bánh xe điều khiển iCUE Control Wheel đa năng.',
                'specs' => [
                    ['ten' => 'Layout', 'gia_tri' => 'Full-size có phím Macro'],
                    ['ten' => 'Switch', 'gia_tri' => 'Corsair OPX Optical-Mechanical']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair K100 RGB OPX Switch',
                        'price' => 5490000,
                        'price_retail' => 5890000,
                        'stock' => 8,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'SteelSeries Apex Pro TKL 2023 Wireless',
                'category' => 'Bàn phím',
                'brand' => 'SteelSeries',
                'desc' => 'Bàn phím chơi game nhanh nhất thế giới nhờ phím bấm có thể tùy chỉnh điểm nhận lực OmniPoint 2.0.',
                'specs' => [
                    ['ten' => 'Layout', 'gia_tri' => 'Tenkeyless (TKL)'],
                    ['ten' => 'Switch', 'gia_tri' => 'OmniPoint 2.0 Adjustable HyperMagnetic']
                ],
                'variants' => [
                    [
                        'name' => 'Apex Pro TKL 2023 Wireless',
                        'price' => 4990000,
                        'price_retail' => 5290000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Kết nối', 'gia_tri' => 'Wireless 2.4GHz / Bluetooth / Type-C']]
                    ]
                ]
            ],
            [
                'name' => 'ASUS ROG Strix Scope II 96 Wireless',
                'category' => 'Bàn phím',
                'brand' => 'ASUS',
                'desc' => 'Layout 96% cực kỳ gọn nhưng giữ nguyên cụm phím số, lót tiêu âm silicon nhiều lớp cho âm gõ êm ái.',
                'specs' => [
                    ['ten' => 'Layout', 'gia_tri' => '96%'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'ROG Omni Receiver Wireless / Bluetooth']
                ],
                'variants' => [
                    [
                        'name' => 'ROG Strix Scope II 96 NX Snow',
                        'price' => 3690000,
                        'price_retail' => 3990000,
                        'stock' => 18,
                        'specs' => [['ten' => 'Switch', 'gia_tri' => 'ROG NX Snow (Linear)']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 11. CHUỘT (8 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Logitech G Pro X Superlight 2 Wireless',
                'category' => 'Chuột',
                'brand' => 'Logitech',
                'desc' => 'Chuột chơi game không dây siêu nhẹ thế hệ 2 từ Logitech, nặng chỉ 60g, trang bị cảm biến Hero 2 độ chính xác tuyệt đối.',
                'specs' => [
                    ['ten' => 'Trọng lượng', 'gia_tri' => '60g'],
                    ['ten' => 'Cảm biến', 'gia_tri' => 'HERO 2 (32.000 DPI)'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Lightspeed Wireless']
                ],
                'variants' => [
                    [
                        'name' => 'G Pro X Superlight 2 Black',
                        'price' => 3290000,
                        'price_retail' => 3590000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'G Pro X Superlight 2 White',
                        'price' => 3290000,
                        'price_retail' => 3590000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Logitech G502 X Plus Lightspeed',
                'category' => 'Chuột',
                'brand' => 'Logitech',
                'desc' => 'Huyền thoại chuột gaming nhiều nút bấm nay lột xác với switch lai quang-cơ Lightforce phản hồi nhanh, độ bền cao.',
                'specs' => [
                    ['ten' => 'Cảm biến', 'gia_tri' => 'HERO 25K'],
                    ['ten' => 'Số nút bấm', 'gia_tri' => '13 nút lập trình được'],
                    ['ten' => 'Đèn LED', 'gia_tri' => 'Active RGB']
                ],
                'variants' => [
                    [
                        'name' => 'G502 X Plus Black',
                        'price' => 3390000,
                        'price_retail' => 3690000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'G502 X Plus White',
                        'price' => 3490000,
                        'price_retail' => 3790000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Razer DeathAdder V3 Pro Wireless',
                'category' => 'Chuột',
                'brand' => 'Razer',
                'desc' => 'Dáng chuột công thái học cho người thuận tay phải huyền thoại, nay tinh chỉnh siêu nhẹ 63g chuyên dùng cho game FPS.',
                'specs' => [
                    ['ten' => 'Trọng lượng', 'gia_tri' => '63g'],
                    ['ten' => 'Cảm biến', 'gia_tri' => 'Razer Focus Pro 30K Optical'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'HyperSpeed Wireless']
                ],
                'variants' => [
                    [
                        'name' => 'DeathAdder V3 Pro Black',
                        'price' => 3290000,
                        'price_retail' => 3590000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'DeathAdder V3 Pro White',
                        'price' => 3290000,
                        'price_retail' => 3590000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Razer Cobra Pro Wireless',
                'category' => 'Chuột',
                'brand' => 'Razer',
                'desc' => 'Chuột đối xứng nhỏ gọn tích hợp 11 vùng đèn LED Chroma RGB chạy quanh thân chuột vô cùng bắt mắt.',
                'specs' => [
                    ['ten' => 'Trọng lượng', 'gia_tri' => '77g'],
                    ['ten' => 'Đèn LED', 'gia_tri' => '11-Zone Chroma RGB']
                ],
                'variants' => [
                    [
                        'name' => 'Razer Cobra Pro Wireless',
                        'price' => 2990000,
                        'price_retail' => 3190000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Kết nối', 'gia_tri' => 'Wireless 2.4GHz / Bluetooth']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair Darkstar Wireless MMO Gaming Mouse',
                'category' => 'Chuột',
                'brand' => 'Corsair',
                'desc' => 'Chuột gaming không dây thiết kế độc đáo với cụm phím phụ tiện ích chuyên cho game thủ thể loại MMO và Moba.',
                'specs' => [
                    ['ten' => 'Số phím phụ', 'gia_tri' => '15 phím lập trình được'],
                    ['ten' => 'Cảm biến', 'gia_tri' => 'Corsair MARKSMAN 26,000 DPI']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair Darkstar Wireless',
                        'price' => 3490000,
                        'price_retail' => 3790000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'SteelSeries Aerox 3 Wireless 2022',
                'category' => 'Chuột',
                'brand' => 'SteelSeries',
                'desc' => 'Thiết kế đục lỗ tổ ong giúp siêu nhẹ 68g, tích hợp chuẩn kháng nước bụi bẩn AquaBarrier IP54 độc quyền cực kỳ bền.',
                'specs' => [
                    ['ten' => 'Trọng lượng', 'gia_tri' => '68g'],
                    ['ten' => 'Chuẩn bảo vệ', 'gia_tri' => 'IP54 AquaBarrier']
                ],
                'variants' => [
                    [
                        'name' => 'SteelSeries Aerox 3 Onyx',
                        'price' => 2190000,
                        'price_retail' => 2390000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen nhám']]
                    ]
                ]
            ],
            [
                'name' => 'SteelSeries Rival 3 Wired Mouse',
                'category' => 'Chuột',
                'brand' => 'SteelSeries',
                'desc' => 'Dòng chuột gaming có dây giá rẻ, phom cầm đối xứng thoải mái và đèn LED RGB chạy viền tinh tế.',
                'specs' => [
                    ['ten' => 'Kết nối', 'gia_tri' => 'Có dây USB'],
                    ['ten' => 'Cảm biến', 'gia_tri' => 'TrueMove Core (8500 DPI)']
                ],
                'variants' => [
                    [
                        'name' => 'SteelSeries Rival 3 Wired',
                        'price' => 690000,
                        'price_retail' => 790000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'ASUS ROG Harpe Ace Aim Lab Edition',
                'category' => 'Chuột',
                'brand' => 'ASUS',
                'desc' => 'Chuột siêu nhẹ 54g đồng phát triển với Aim Lab, tích hợp phần mềm phân tích thói quen vẩy chuột để tối ưu thông số.',
                'specs' => [
                    ['ten' => 'Trọng lượng', 'gia_tri' => '54g'],
                    ['ten' => 'Cảm biến', 'gia_tri' => 'ROG AimPoint (36.000 DPI)']
                ],
                'variants' => [
                    [
                        'name' => 'ROG Harpe Ace Aim Lab',
                        'price' => 2990000,
                        'price_retail' => 3290000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 12. TAI NGHE (8 sản phẩm)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Sony WH-1000XM5 Wireless Headphones',
                'category' => 'Tai nghe',
                'brand' => 'Sony',
                'desc' => 'Ông hoàng tai nghe chống ồn chủ động (ANC) thế giới với chip xử lý âm thanh độc quyền và chất âm Sony xuất sắc.',
                'specs' => [
                    ['ten' => 'Thời lượng pin', 'gia_tri' => 'Lên tới 30 giờ (bật ANC) / 40 giờ (tắt ANC)'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Bluetooth 5.2, jack 3.5mm'],
                    ['ten' => 'Công nghệ chống ồn', 'gia_tri' => 'Dual Processor V1 + HD Noise Cancelling QN1']
                ],
                'variants' => [
                    [
                        'name' => 'Sony WH-1000XM5 Black',
                        'price' => 7490000,
                        'price_retail' => 8490000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'Sony WH-1000XM5 Silver',
                        'price' => 7490000,
                        'price_retail' => 8490000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Bạc']]
                    ]
                ]
            ],
            [
                'name' => 'Sony WF-1000XM5 True Wireless',
                'category' => 'Tai nghe',
                'brand' => 'Sony',
                'desc' => 'Tai nghe nhét tai In-ear không dây chống ồn đỉnh cao, kích thước màng loa Dynamic Driver X thế hệ mới cho âm bass sâu lắng.',
                'specs' => [
                    ['ten' => 'Kiểu kết nối', 'gia_tri' => 'True Wireless'],
                    ['ten' => 'Chống nước', 'gia_tri' => 'IPX4']
                ],
                'variants' => [
                    [
                        'name' => 'Sony WF-1000XM5 Black',
                        'price' => 5490000,
                        'price_retail' => 5990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Logitech G Pro X 2 Lightspeed Wireless',
                'category' => 'Tai nghe',
                'brand' => 'Logitech',
                'desc' => 'Tai nghe gaming chụp tai chuyên nghiệp sử dụng màng loa Graphene cho độ chi tiết âm thanh tối đa, nghe rõ tiếng bước chân định hướng.',
                'specs' => [
                    ['ten' => 'Màng loa', 'gia_tri' => 'PRO-G GRAPHENE 50mm'],
                    ['ten' => 'Microphone', 'gia_tri' => '6mm Cardioid tháo rời (Blue VO!CE)'],
                    ['ten' => 'Thời lượng pin', 'gia_tri' => 'Lên đến 50 giờ']
                ],
                'variants' => [
                    [
                        'name' => 'Logitech G Pro X 2 Black',
                        'price' => 5290000,
                        'price_retail' => 5590000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'Logitech G Pro X 2 White',
                        'price' => 5290000,
                        'price_retail' => 5590000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Razer BlackShark V2 Pro 2023 Edition',
                'category' => 'Tai nghe',
                'brand' => 'Razer',
                'desc' => 'Nâng cấp micro siêu rộng HyperClear Super Wideband cho chất lượng giọng nói đàm thoại rõ như pha lê, cực thích hợp cho eSports.',
                'specs' => [
                    ['ten' => 'Màng loa', 'gia_tri' => 'Razer TriForce Titanium 50mm'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'HyperSpeed Wireless (2.4GHz) / Bluetooth 5.2']
                ],
                'variants' => [
                    [
                        'name' => 'BlackShark V2 Pro 2023 Black',
                        'price' => 4290000,
                        'price_retail' => 4590000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'BlackShark V2 Pro 2023 White',
                        'price' => 4290000,
                        'price_retail' => 4590000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Corsair HS80 RGB WIRELESS Gaming Headset',
                'category' => 'Tai nghe',
                'brand' => 'Corsair',
                'desc' => 'Trải nghiệm âm thanh vòm Dolby Atmos đa chiều sống động cùng thiết kế đệm tai vải nỉ êm ái thoáng mát.',
                'specs' => [
                    ['ten' => 'Chuẩn âm thanh', 'gia_tri' => 'Dolby Atmos Spatial Audio'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Slipstream Wireless siêu tốc / Dây USB']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair HS80 Wireless Carbon',
                        'price' => 3190000,
                        'price_retail' => 3490000,
                        'stock' => 22,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Xám Carbon']]
                    ],
                    [
                        'name' => 'Corsair HS80 Wireless White',
                        'price' => 3290000,
                        'price_retail' => 3590000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'SteelSeries Arctis Nova 7 Wireless',
                'category' => 'Tai nghe',
                'brand' => 'SteelSeries',
                'desc' => 'Tích hợp kết nối không dây đồng thời 2 thiết bị (2.4GHz + Bluetooth), dễ dàng vừa chơi game trên PC vừa nghe gọi điện thoại.',
                'specs' => [
                    ['ten' => 'Kết nối', 'gia_tri' => 'Dual Wireless (2.4G + BT)'],
                    ['ten' => 'Thời lượng pin', 'gia_tri' => '38 giờ']
                ],
                'variants' => [
                    [
                        'name' => 'SteelSeries Arctis Nova 7',
                        'price' => 4390000,
                        'price_retail' => 4690000,
                        'stock' => 18,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'SteelSeries Arctis Nova Pro Wireless',
                'category' => 'Tai nghe',
                'brand' => 'SteelSeries',
                'desc' => 'Tai nghe chơi game cao cấp nhất tích hợp hệ thống sạc pin kép liên tục Hot-Swap và bộ xử lý âm thanh ngoài GameDAC Gen 2.',
                'specs' => [
                    ['ten' => 'Âm thanh DAC', 'gia_tri' => 'ESS Sabre Quad-DAC Gen 2'],
                    ['ten' => 'Chống ồn', 'gia_tri' => 'ANC (Active Noise Cancellation)']
                ],
                'variants' => [
                    [
                        'name' => 'Arctis Nova Pro Wireless',
                        'price' => 8490000,
                        'price_retail' => 8990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Apple AirPods Max',
                'category' => 'Tai nghe',
                'brand' => 'Apple',
                'desc' => 'Thiết kế chùm đầu nhôm anodized sang trọng, chống ồn đỉnh cao ANC và âm thanh vòm Spatial Audio tuyệt hảo.',
                'specs' => [
                    ['ten' => 'Bộ vi xử lý', 'gia_tri' => 'Apple H1 chip (mỗi bên tai)'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Bluetooth 5.0']
                ],
                'variants' => [
                    [
                        'name' => 'AirPods Max Space Gray',
                        'price' => 12490000,
                        'price_retail' => 13990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Xám Không Gian']]
                    ]
                ]
            ]
        ];

        // 4. Lặp tạo các bản ghi Sản phẩm và các Biến thể
        foreach ($productsData as $index => $item) {
            $catId = $categories[$item['category']] ?? null;
            $brandId = $brands[$item['brand']] ?? null;

            if (!$catId || !$brandId) {
                continue; // Bỏ qua nếu danh mục hoặc hãng không tìm thấy trong DB
            }

            // Tính giá thấp nhất ban đầu từ các biến thể
            $minPrice = collect($item['variants'])->min('price');

            // Tạo sản phẩm chính
            $product = Product::create([
                'ma_san_pham'             => 'temp',
                'ten_san_pham'            => $item['name'],
                'ma_danh_muc'             => $catId,
                'ma_thuong_hieu'          => $brandId,
                'mo_ta_ngan'              => $item['desc'],
                'mo_ta_chi_tiet'          => 'Thông tin chi tiết và chính sách bảo hành chính hãng của ' . $item['name'] . '. Sản phẩm được phân phối chính thức bởi VNTech.',
                'link_anh_dai_dien'       => null, // Mặc định dùng elvis operator ?: fallback trên UI
                'trang_thai'              => 'active',
                'hinh_anh'                => [],   // Mảng ảnh rỗng
                'thong_so_ky_thuat_chung' => $item['specs'],
                'thong_tin_them'          => [],
                'luot_xem'                => rand(50, 1500),
                'gia_thap_nhat'           => $minPrice,
                'so_sao_trung_binh'       => 0,
                'so_luot_danh_gia'        => 0,
                'tong_so_sao'             => 0,
                'tong_luot_ban'           => 0,
            ]);

            // Cập nhật lại mã sản phẩm khớp với ID thật
            $product->update([
                'ma_san_pham' => (string) $product->_id,
            ]);

            // Tạo các biến thể của sản phẩm
            foreach ($item['variants'] as $vItem) {

                // Tự động bỏ tên sản phẩm ở đầu và sinh tên từ các giá trị specs, cách nhau bởi /
                $variantName = '';
                if (!empty($vItem['specs']) && is_array($vItem['specs'])) {
                    $specValues = [];
                    foreach ($vItem['specs'] as $spec) {
                        if (!empty($spec['gia_tri'])) {
                            $specValues[] = $spec['gia_tri'];
                        }
                    }
                    if (!empty($specValues)) {
                        $variantName = implode('/', $specValues);
                    }
                }
                if (empty($variantName)) {
                    $variantName = $vItem['name'];
                }

                $variant = ProductVariant::create([
                    'ma_san_pham'             =>  $product->ma_san_pham,
                    'ma_bien_the'             => 'temp',
                    'ten_bien_the'            => $variantName,
                    'link_anh_bien_the'       => null,
                    'gia_ban'                 => $vItem['price'],
                    'gia_niem_yet'            => $vItem['price_retail'],
                    'so_luong_ton_kho'        => $vItem['stock'],
                    'da_ban'                  => 0,
                    'thong_so_ky_thuat_rieng' => $vItem['specs'],
                    'trang_thai'              => 'active',
                ]);

                // Cập nhật lại mã biến thể khớp với ID thật
                $variant->update([
                    'ma_bien_the' =>  (string) $variant->_id,
                ]);
            }

        }
    }
}
