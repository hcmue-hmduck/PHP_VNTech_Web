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

        // 3. Danh sách đúng 25 sản phẩm thực tế kèm biến thể tương ứng
        $productsData = [
            /*
            |--------------------------------------------------------------------------
            | 1. ĐIỆN THOẠI (5 sản phẩm, tất cả đều có biến thể)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'iPhone 17 Pro Max',
                'category' => 'Điện thoại',
                'brand' => 'Apple',
                'desc' => 'Flagship tối tân nhất của Apple thế hệ mới với thiết kế siêu mỏng nhẹ, trang bị camera tele 48MP zoom quang học nâng cấp và chip xử lý AI đỉnh cao.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.9 inch, Super Retina XDR OLED, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Apple A19 Pro (2nm)'],
                    ['ten' => 'RAM', 'gia_tri' => '12GB'],
                    ['ten' => 'Camera sau', 'gia_tri' => '48MP + 48MP + 48MP'],
                    ['ten' => 'Pin', 'gia_tri' => '4685 mAh, hỗ trợ sạc nhanh 35W']
                ],
                'variants' => [
                    [
                        'name' => 'iPhone 17 Pro Max 256GB',
                        'price' => 34990000,
                        'price_retail' => 37990000,
                        'stock' => 45,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Sa Mạc']]
                    ],
                    [
                        'name' => 'iPhone 17 Pro Max 512GB',
                        'price' => 38990000,
                        'price_retail' => 42990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Tự Nhiên']]
                    ]
                ]
            ],
            [
                'name' => 'iPhone 16',
                'category' => 'Điện thoại',
                'brand' => 'Apple',
                'desc' => 'Trang bị nút điều khiển Camera Control hoàn toàn mới, mặt lưng kính pha màu bền bỉ và hiệu năng đỉnh cao của chip A18.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.1 inch, Super Retina XDR OLED'],
                    ['ten' => 'CPU', 'gia_tri' => 'Apple A18 Bionic'],
                    ['ten' => 'RAM', 'gia_tri' => '8GB']
                ],
                'variants' => [
                    [
                        'name' => 'iPhone 16 128GB',
                        'price' => 22990000,
                        'price_retail' => 24990000,
                        'stock' => 50,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '128GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Xanh Lưu Ly']]
                    ],
                    [
                        'name' => 'iPhone 16 256GB',
                        'price' => 25990000,
                        'price_retail' => 27990000,
                        'stock' => 35,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung Galaxy S25 Ultra',
                'category' => 'Điện thoại',
                'brand' => 'Samsung',
                'desc' => 'Đỉnh cao công nghệ AI Phone thế hệ mới từ Samsung với khung viền Titan mỏng nhẹ hơn, bút S-Pen đa năng và cụm camera zoom 100x đỉnh cao.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.86 inch, Dynamic AMOLED 2X, QHD+, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 4 for Galaxy'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB'],
                    ['ten' => 'Camera sau', 'gia_tri' => '200MP + 50MP + 50MP + 10MP']
                ],
                'variants' => [
                    [
                        'name' => 'Galaxy S25 Ultra 256GB',
                        'price' => 31990000,
                        'price_retail' => 34990000,
                        'stock' => 40,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Bạc']]
                    ],
                    [
                        'name' => 'Galaxy S25 Ultra 512GB',
                        'price' => 35990000,
                        'price_retail' => 38990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Titan Đen']]
                    ]
                ]
            ],
            [
                'name' => 'Xiaomi 15 Ultra',
                'category' => 'Điện thoại',
                'brand' => 'Xiaomi',
                'desc' => 'Đỉnh cao nhiếp ảnh di động đồng chế tác với Leica, cảm biến chính thế hệ mới cho chất lượng ảnh tuyệt mỹ.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.73 inch, AMOLED C9 WQHD+, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 4'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB'],
                    ['ten' => 'Camera sau', 'gia_tri' => '50MP (Leica Lythia) + 3 camera 50MP']
                ],
                'variants' => [
                    [
                        'name' => 'Xiaomi 15 Ultra 256GB',
                        'price' => 27990000,
                        'price_retail' => 29990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'Xiaomi 15 Ultra 512GB',
                        'price' => 30990000,
                        'price_retail' => 32990000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'OPPO Find X8 Ultra',
                'category' => 'Điện thoại',
                'brand' => 'OPPO',
                'desc' => 'Điện thoại cao cấp siêu mỏng nhẹ bậc nhất thế giới, tối ưu hóa giao diện đa nhiệm không giới hạn cùng cụm camera Hasselblad.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '6.82 inch, LTPO OLED, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Snapdragon 8 Gen 4'],
                    ['ten' => 'RAM', 'gia_tri' => '16GB']
                ],
                'variants' => [
                    [
                        'name' => 'OPPO Find X8 Ultra 256GB',
                        'price' => 29990000,
                        'price_retail' => 31990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '256GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Xanh Dương']]
                    ],
                    [
                        'name' => 'OPPO Find X8 Ultra 512GB',
                        'price' => 32990000,
                        'price_retail' => 34990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'Dung lượng', 'gia_tri' => '512GB'], ['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 2. LAPTOP (5 sản phẩm, tất cả đều có biến thể)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'ASUS ROG Strix G16 2025',
                'category' => 'Laptop',
                'brand' => 'ASUS',
                'desc' => 'Laptop gaming tối thượng với màn hình ROG Nebula 16 inch siêu mượt, chip Intel thế hệ mới nhất và card RTX 50-series.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16 inch, WQXGA IPS, 240Hz'],
                    ['ten' => 'SSD', 'gia_tri' => '1TB PCIe 4.0 NVMe'],
                    ['ten' => 'HĐH', 'gia_tri' => 'Windows 11 Home']
                ],
                'variants' => [
                    [
                        'name' => 'ROG Strix G16 Core i7 RTX 5060',
                        'price' => 38990000,
                        'price_retail' => 41990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i7-14700HX'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 5060 8GB']]
                    ],
                    [
                        'name' => 'ROG Strix G16 Core i9 RTX 5070',
                        'price' => 49990000,
                        'price_retail' => 52990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Core i9-14900HX'], ['ten' => 'RAM', 'gia_tri' => '32GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 5070 8GB']]
                    ]
                ]
            ],
            [
                'name' => 'Apple MacBook Pro 14 M4',
                'category' => 'Laptop',
                'brand' => 'Apple',
                'desc' => 'Thiết kế sang trọng, tối ưu phần cứng cực mượt, thời lượng pin sử dụng liên tục lên đến 24 giờ cùng sức mạnh của chip Apple Silicon M4.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '14.2 inch, Liquid Retina XDR, ProMotion 120Hz'],
                    ['ten' => 'SSD', 'gia_tri' => '512GB SSD'],
                    ['ten' => 'Trọng lượng', 'gia_tri' => '1.55 kg']
                ],
                'variants' => [
                    [
                        'name' => 'MacBook Pro 14 M4 16GB RAM',
                        'price' => 44990000,
                        'price_retail' => 47990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'M4 (10-core CPU)'], ['ten' => 'RAM', 'gia_tri' => '16GB Unified']]
                    ],
                    [
                        'name' => 'MacBook Pro 14 M4 Pro 24GB RAM',
                        'price' => 54990000,
                        'price_retail' => 57990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'M4 Pro (12-core CPU)'], ['ten' => 'RAM', 'gia_tri' => '24GB Unified']]
                    ]
                ]
            ],
            [
                'name' => 'Dell XPS 13 9350 2025',
                'category' => 'Laptop',
                'brand' => 'Dell',
                'desc' => 'Biểu tượng laptop siêu sang trọng với thiết kế nhôm nguyên khối, bàn phím vô cực liền mạch và thanh cảm ứng điện dung chức năng mới.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '13.4 inch, FHD+ IPS, 120Hz'],
                    ['ten' => 'CPU', 'gia_tri' => 'Intel Core Ultra 7 258V'],
                    ['ten' => 'VGA', 'gia_tri' => 'Intel Arc Graphics']
                ],
                'variants' => [
                    [
                        'name' => 'Dell XPS 13 i7/16GB/512GB',
                        'price' => 42990000,
                        'price_retail' => 45990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '512GB PCIe Gen4 NVMe']]
                    ],
                    [
                        'name' => 'Dell XPS 13 i9/32GB/1TB',
                        'price' => 49990000,
                        'price_retail' => 52990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '32GB LPDDR5X'], ['ten' => 'SSD', 'gia_tri' => '1TB PCIe Gen4 NVMe']]
                    ]
                ]
            ],
            [
                'name' => 'MSI Katana 16 AI',
                'category' => 'Laptop',
                'brand' => 'MSI',
                'desc' => 'Trang bị tản nhiệt Cooler Boost 5 siêu mát kết hợp cùng chip xử lý Ryzen AI hiệu năng phần cứng vô cùng vượt trội.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16.0 inch, FHD IPS, 144Hz'],
                    ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']
                ],
                'variants' => [
                    [
                        'name' => 'MSI Katana 16 Ryzen 7/16GB/512GB',
                        'price' => 28990000,
                        'price_retail' => 31990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Ryzen 7 8840HS'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ],
                    [
                        'name' => 'MSI Katana 16 Ryzen 9/32GB/1TB',
                        'price' => 36990000,
                        'price_retail' => 39990000,
                        'stock' => 18,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Ryzen 9 8945HS'], ['ten' => 'RAM', 'gia_tri' => '32GB DDR5'], ['ten' => 'SSD', 'gia_tri' => '1TB SSD']]
                    ]
                ]
            ],
            [
                'name' => 'HP Victus 16 2025',
                'category' => 'Laptop',
                'brand' => 'HP',
                'desc' => 'Dòng laptop gaming tối giản, thiết kế đẹp nhẹ nhàng phù hợp đi học đi làm nhưng chứa sức mạnh đồ hoạ mạnh mẽ bên trong.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '16.1 inch, FHD IPS, 144Hz'],
                    ['ten' => 'SSD', 'gia_tri' => '512GB PCIe NVMe M.2']
                ],
                'variants' => [
                    [
                        'name' => 'HP Victus 16 Core i5 RTX 4050',
                        'price' => 21990000,
                        'price_retail' => 23990000,
                        'stock' => 30,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i5-14450HX'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4050 6GB']]
                    ],
                    [
                        'name' => 'HP Victus 16 Core i7 RTX 4060',
                        'price' => 27990000,
                        'price_retail' => 29990000,
                        'stock' => 18,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i7-14650HX'], ['ten' => 'RAM', 'gia_tri' => '16GB DDR5'], ['ten' => 'VGA', 'gia_tri' => 'GeForce RTX 4060 8GB']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 3. MÁY TÍNH ĐỂ BÀN (3 sản phẩm, tất cả đều có biến thể)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Apple iMac 24 inch M4',
                'category' => 'Máy tính để bàn',
                'brand' => 'Apple',
                'desc' => 'Máy tính All-in-One siêu mỏng phong cách All-in-One, màn hình Retina 4.5K hiển thị 1 tỷ màu, chip M4 xử lý AI vượt trội.',
                'specs' => [
                    ['ten' => 'Màn hình', 'gia_tri' => '24 inch Retina 4.5K (4480x2520), 500 nits'],
                    ['ten' => 'CPU', 'gia_tri' => 'Apple M4 (10-Core CPU)'],
                    ['ten' => 'HĐH', 'gia_tri' => 'macOS Sequoia']
                ],
                'variants' => [
                    [
                        'name' => 'iMac 24 M4 16GB/256GB SSD',
                        'price' => 39990000,
                        'price_retail' => 42990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB Unified'], ['ten' => 'SSD', 'gia_tri' => '256GB SSD']]
                    ],
                    [
                        'name' => 'iMac 24 M4 16GB/512GB SSD',
                        'price' => 44990000,
                        'price_retail' => 47990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB Unified'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ]
                ]
            ],
            [
                'name' => 'Apple Mac Mini M4 2025',
                'category' => 'Máy tính để bàn',
                'brand' => 'Apple',
                'desc' => 'Chiếc hộp máy tính mini nhỏ gọn được thiết kế lại hoàn toàn siêu nhỏ, sở hữu sức mạnh đồ hoạ vượt bậc của chip M4.',
                'specs' => [
                    ['ten' => 'CPU', 'gia_tri' => 'Apple M4 (10-core CPU, 10-core GPU)'],
                    ['ten' => 'Kích thước', 'gia_tri' => '12.7 x 12.7 x 5.0 cm'],
                    ['ten' => 'HĐH', 'gia_tri' => 'macOS']
                ],
                'variants' => [
                    [
                        'name' => 'Mac Mini M4 16GB RAM/256GB SSD',
                        'price' => 18990000,
                        'price_retail' => 20990000,
                        'stock' => 25,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '16GB Unified'], ['ten' => 'SSD', 'gia_tri' => '256GB SSD']]
                    ],
                    [
                        'name' => 'Mac Mini M4 24GB RAM/512GB SSD',
                        'price' => 24990000,
                        'price_retail' => 26990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'RAM', 'gia_tri' => '24GB Unified'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ]
                ]
            ],
            [
                'name' => 'Lenovo ThinkCentre Neo 50t Gen 5',
                'category' => 'Máy tính để bàn',
                'brand' => 'Lenovo',
                'desc' => 'Máy tính đồng bộ Lenovo mạnh mẽ, bền bỉ và dễ nâng cấp dành cho doanh nghiệp và văn phòng hiện đại thế hệ Gen 5.',
                'specs' => [
                    ['ten' => 'RAM', 'gia_tri' => 'DDR5 4800MHz'],
                    ['ten' => 'HĐH', 'gia_tri' => 'Windows 11 Home']
                ],
                'variants' => [
                    [
                        'name' => 'ThinkCentre Neo 50t i5/8G/512G',
                        'price' => 13490000,
                        'price_retail' => 14990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i5-14400'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ],
                    [
                        'name' => 'ThinkCentre Neo 50t i7/16G/512G',
                        'price' => 17490000,
                        'price_retail' => 18990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'CPU', 'gia_tri' => 'Intel Core i7-14700'], ['ten' => 'SSD', 'gia_tri' => '512GB SSD']]
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 4. LINH KIỆN MÁY TÍNH (6 sản phẩm, 4 có biến thể, 2 không có biến thể)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Intel Core i9-14900K',
                'category' => 'CPU',
                'brand' => 'Intel',
                'desc' => 'Bộ vi xử lý Intel Core thế hệ 14 Raptor Lake Refresh tối tân với 24 nhân 32 luồng, xung nhịp turbo tối đa lên đến 6.0 GHz.',
                'specs' => [
                    ['ten' => 'Socket', 'gia_tri' => 'LGA 1700'],
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '24 nhân / 32 luồng'],
                    ['ten' => 'Xung nhịp tối đa', 'gia_tri' => '6.0 GHz']
                ],
                'variants' => [
                    [
                        'name' => 'Intel Core i9-14900K Box',
                        'price' => 14490000,
                        'price_retail' => 15990000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Box chính hãng']]
                    ],
                    [
                        'name' => 'Intel Core i9-14900K Tray',
                        'price' => 13990000,
                        'price_retail' => 14990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Tray không quạt']]
                    ]
                ]
            ],
            [
                'name' => 'AMD Ryzen 7 9800X3D',
                'category' => 'CPU',
                'brand' => 'AMD',
                'desc' => 'Bộ vi xử lý chơi game kiến trúc Zen 5 tối tân nhất thế giới với công nghệ bộ nhớ đệm 3D V-Cache đột phá.',
                'specs' => [
                    ['ten' => 'Socket', 'gia_tri' => 'AM5'],
                    ['ten' => 'Số nhân/luồng', 'gia_tri' => '8 nhân / 16 luồng'],
                    ['ten' => 'Bộ nhớ đệm L3', 'gia_tri' => '96MB (3D V-Cache)']
                ],
                'variants' => [
                    [
                        'name' => 'AMD Ryzen 7 9800X3D Box',
                        'price' => 11990000,
                        'price_retail' => 12990000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Box chính hãng']]
                    ],
                    [
                        'name' => 'AMD Ryzen 7 9800X3D Tray',
                        'price' => 11490000,
                        'price_retail' => 11990000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Tray không quạt']]
                    ]
                ]
            ],
            [
                'name' => 'MSI GeForce RTX 5070 Ti Gaming X',
                'category' => 'Card đồ họa',
                'brand' => 'MSI',
                'desc' => 'Dòng card đồ họa kiến trúc Blackwell 50-series thế hệ mới nhất của MSI, hiệu năng chơi game 4K cực đỉnh và dò tia siêu tốc.',
                'specs' => [
                    ['ten' => 'Băng thông', 'gia_tri' => '256-bit'],
                    ['ten' => 'Cổng kết nối', 'gia_tri' => '1x HDMI, 3x DisplayPort']
                ],
                'variants' => [
                    [
                        'name' => 'MSI RTX 5070 Ti 12GB',
                        'price' => 26490000,
                        'price_retail' => 28490000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Bộ nhớ', 'gia_tri' => '12GB GDDR7']]
                    ],
                    [
                        'name' => 'MSI RTX 5070 Ti 16GB',
                        'price' => 29990000,
                        'price_retail' => 31990000,
                        'stock' => 8,
                        'specs' => [['ten' => 'Bộ nhớ', 'gia_tri' => '16GB GDDR7']]
                    ]
                ]
            ],
            [
                'name' => 'ASUS ROG Strix GeForce RTX 5060',
                'category' => 'Card đồ họa',
                'brand' => 'ASUS',
                'desc' => 'Thiết kế hầm hố chuẩn ROG Strix thế hệ mới, hệ thống 3 quạt tản nhiệt hiệu suất cao cùng hiệu năng đồ họa ấn tượng từ kiến trúc thế hệ mới.',
                'specs' => [
                    ['ten' => 'Cổng kết nối', 'gia_tri' => '2x HDMI, 3x DisplayPort']
                ],
                'variants' => [
                    [
                        'name' => 'ROG Strix RTX 5060 8GB',
                        'price' => 13490000,
                        'price_retail' => 14490000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Bộ nhớ', 'gia_tri' => '8GB GDDR7']]
                    ],
                    [
                        'name' => 'ROG Strix RTX 5060 12GB',
                        'price' => 15990000,
                        'price_retail' => 16990000,
                        'stock' => 12,
                        'specs' => [['ten' => 'Bộ nhớ', 'gia_tri' => '12GB GDDR7']]
                    ]
                ]
            ],
            [
                'name' => 'Samsung 990 PRO SSD 1TB', // KHÔNG BIẾN THỂ (1)
                'category' => 'Ổ cứng SSD',
                'brand' => 'Samsung',
                'desc' => 'Ổ cứng SSD PCIe 4.0 NVMe cao cấp nhất với tốc độ đọc ghi vượt trội hơn hẳn mọi đối thủ.',
                'specs' => [
                    ['ten' => 'Tốc độ đọc', 'gia_tri' => 'Lên đến 7450 MB/s'],
                    ['ten' => 'Tốc độ ghi', 'gia_tri' => 'Lên đến 6900 MB/s'],
                    ['ten' => 'Chuẩn kết nối', 'gia_tri' => 'M.2 NVMe PCIe Gen4']
                ],
                'variants' => [
                    [
                        'name' => 'Samsung 990 PRO 1TB PCIe 4.0',
                        'price' => 2490000,
                        'price_retail' => 2990000,
                        'stock' => 50,
                        'specs' => null
                    ]
                ]
            ],
            [
                'name' => 'Corsair Vengeance RGB DDR5 32GB', // KHÔNG BIẾN THỂ (2)
                'category' => 'RAM',
                'brand' => 'Corsair',
                'desc' => 'Bộ nhớ RAM DDR5 hiệu năng cao kết hợp cùng dải đèn LED RGB rực rỡ, tối ưu hóa hoàn hảo cho các cấu hình PC thế hệ mới.',
                'specs' => [
                    ['ten' => 'Bus RAM', 'gia_tri' => '5600 MHz'],
                    ['ten' => 'Dung lượng', 'gia_tri' => '32GB (2x16GB)'],
                    ['ten' => 'Loại RAM', 'gia_tri' => 'DDR5']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair Vengeance RGB 32GB 5600MHz',
                        'price' => 3190000,
                        'price_retail' => 3490000,
                        'stock' => 40,
                        'specs' => null
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | 5. GAMING GEAR (6 sản phẩm, 3 có biến thể, 3 không có biến thể)
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Bàn phím cơ ASUS ROG Strix Scope II', // KHÔNG BIẾN THỂ (3)
                'category' => 'Bàn phím',
                'brand' => 'ASUS',
                'desc' => 'Bàn phím cơ chơi game thế hệ mới trang bị switch độc quyền ROG NX được bôi trơn sẵn, cấu trúc tiêu âm bọt khí êm ái.',
                'specs' => [
                    ['ten' => 'Loại Switch', 'gia_tri' => 'ROG NX Snow (Linear)'],
                    ['ten' => 'Đèn LED', 'gia_tri' => 'RGB Per-key'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Cáp USB rời']
                ],
                'variants' => [
                    [
                        'name' => 'ASUS ROG Strix Scope II Standard',
                        'price' => 2690000,
                        'price_retail' => 2990000,
                        'stock' => 25,
                        'specs' => null
                    ]
                ]
            ],
            [
                'name' => 'Chuột không dây Logitech G Pro X Superlight', // KHÔNG BIẾN THỂ (4)
                'category' => 'Chuột',
                'brand' => 'Logitech',
                'desc' => 'Chuột chơi game không dây huyền thoại siêu nhẹ chỉ 63g, độ chính xác tuyệt đối tin dùng bởi hàng loạt tuyển thủ chuyên nghiệp.',
                'specs' => [
                    ['ten' => 'Cảm biến', 'gia_tri' => 'HERO 25K'],
                    ['ten' => 'Trọng lượng', 'gia_tri' => '63g'],
                    ['ten' => 'Thời lượng pin', 'gia_tri' => 'Lên đến 70 giờ']
                ],
                'variants' => [
                    [
                        'name' => 'Logitech G Pro X Superlight Black',
                        'price' => 3190000,
                        'price_retail' => 3490000,
                        'stock' => 30,
                        'specs' => null
                    ]
                ]
            ],
            [
                'name' => 'Tai nghe không dây Sony WH-1000XM5', // KHÔNG BIẾN THỂ (5)
                'category' => 'Tai nghe',
                'brand' => 'Sony',
                'desc' => 'Tai nghe chụp tai chống ồn chủ động cao cấp hàng đầu của Sony, tích hợp bộ xử lý kép V1 và chất âm độ phân giải cao Hi-Res Audio.',
                'specs' => [
                    ['ten' => 'Công nghệ chống ồn', 'gia_tri' => 'ANC kép (Auto NC Optimizer)'],
                    ['ten' => 'Thời lượng pin', 'gia_tri' => 'Lên đến 30 giờ'],
                    ['ten' => 'Kết nối', 'gia_tri' => 'Bluetooth 5.2']
                ],
                'variants' => [
                    [
                        'name' => 'Sony WH-1000XM5 Standard',
                        'price' => 6490000,
                        'price_retail' => 8490000,
                        'stock' => 30,
                        'specs' => null
                    ]
                ]
            ],
            [
                'name' => 'Chuột không dây Razer DeathAdder V3 Pro',
                'category' => 'Chuột',
                'brand' => 'Razer',
                'desc' => 'Chuột gaming công thái học siêu nhẹ 63g, trang bị cảm biến quang học Focus Pro 30K và công nghệ kết nối không dây siêu tốc HyperSpeed.',
                'specs' => [
                    ['ten' => 'Cảm biến', 'gia_tri' => 'Focus Pro 30K'],
                    ['ten' => 'Thời lượng pin', 'gia_tri' => 'Lên đến 90 giờ'],
                    ['ten' => 'Trọng lượng', 'gia_tri' => '63g']
                ],
                'variants' => [
                    [
                        'name' => 'DeathAdder V3 Pro Black',
                        'price' => 3290000,
                        'price_retail' => 3690000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Đen']]
                    ],
                    [
                        'name' => 'DeathAdder V3 Pro White',
                        'price' => 3290000,
                        'price_retail' => 3690000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Màu sắc', 'gia_tri' => 'Trắng']]
                    ]
                ]
            ],
            [
                'name' => 'Tai nghe không dây SteelSeries Arctis Nova 7',
                'category' => 'Tai nghe',
                'brand' => 'SteelSeries',
                'desc' => 'Tai nghe gaming không dây cao cấp sở hữu chất âm Nova Acoustic đỉnh cao, kết nối đồng thời Bluetooth và 2.4GHz tiện lợi.',
                'specs' => [
                    ['ten' => 'Thời lượng pin', 'gia_tri' => 'Lên đến 38 giờ'],
                    ['ten' => 'Kết nối', 'gia_tri' => '2.4GHz Wireless & Bluetooth']
                ],
                'variants' => [
                    [
                        'name' => 'SteelSeries Arctis Nova 7 Wireless',
                        'price' => 4290000,
                        'price_retail' => 4890000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Standard']]
                    ],
                    [
                        'name' => 'SteelSeries Arctis Nova 7 Diablo IV',
                        'price' => 4990000,
                        'price_retail' => 5590000,
                        'stock' => 10,
                        'specs' => [['ten' => 'Phiên bản', 'gia_tri' => 'Diablo IV Edition']]
                    ]
                ]
            ],
            [
                'name' => 'Bàn phím cơ Corsair K70 RGB PRO',
                'category' => 'Bàn phím',
                'brand' => 'Corsair',
                'desc' => 'Bàn phím cơ chơi game cao cấp sở hữu khung viền nhôm bền bỉ, switch cơ Cherry MX và công nghệ siêu xử lý Corsair AXON.',
                'specs' => [
                    ['ten' => 'Keycap', 'gia_tri' => 'Double-shot PBT'],
                    ['ten' => 'Tần số gửi tín hiệu', 'gia_tri' => '8000 Hz']
                ],
                'variants' => [
                    [
                        'name' => 'Corsair K70 RGB PRO Cherry Red',
                        'price' => 3890000,
                        'price_retail' => 4290000,
                        'stock' => 20,
                        'specs' => [['ten' => 'Switch', 'gia_tri' => 'Cherry MX Red']]
                    ],
                    [
                        'name' => 'Corsair K70 RGB PRO Cherry Blue',
                        'price' => 3890000,
                        'price_retail' => 4290000,
                        'stock' => 15,
                        'specs' => [['ten' => 'Switch', 'gia_tri' => 'Cherry MX Blue']]
                    ]
                ]
            ]
        ];

        // 4. Lặp tạo các bản ghi Sản phẩm và các Biến thể
        foreach ($productsData as $index => $item) {
            // Tự động tạo danh mục nếu chưa có
            if (empty($categories[$item['category']])) {
                $newCat = Category::create([
                    'ma_danh_muc'     => 'temp',
                    'ma_danh_muc_cha' => null,
                    'ten_danh_muc'    => $item['category'],
                    'logo_url'        => null,
                    'trang_thai'      => 'active',
                ]);
                $newCat->update(['ma_danh_muc' => $newCat->_id]);
                $categories[$item['category']] = $newCat->_id;
            }
            $catId = $categories[$item['category']];

            // Tự động tạo hãng nếu chưa có
            if (empty($brands[$item['brand']])) {
                $newBrand = Brand::create([
                    'ma_thuong_hieu' => 'temp',
                    'ten_thuong_hieu'=> $item['brand'],
                    'mo_ta'          => 'Thương hiệu ' . $item['brand'],
                    'logo_url'       => null,
                    'trang_thai'     => 'active',
                ]);
                $newBrand->update(['ma_thuong_hieu' => $newBrand->_id]);
                $brands[$item['brand']] = $newBrand->_id;
            }
            $brandId = $brands[$item['brand']];

            // Tính giá thấp nhất ban đầu từ các biến thể
            $minPrice = collect($item['variants'])->min('price');

            // Xác định sản phẩm có nhiều biến thể hay không
            $hasVariants = count($item['variants']) > 1;

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
                'kiem_tra_bien_the'       => $hasVariants,
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
                if ($hasVariants) {
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
                } else {
                    // Nếu là sản phẩm đơn giản (không có biến thể), tên biến thể phải để rỗng ""
                    $variantName = '';
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
                    'thong_so_ky_thuat_rieng' => $hasVariants ? $vItem['specs'] : null,
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
