<div align="center">

[![Typing SVG](https://readme-typing-svg.demolab.com?font=Space+Grotesk&weight=700&size=50&pause=1000&color=00e55b&center=true&vCenter=true&width=450&lines=PHP+VNTECH;E-COMMERCE+SYSTEM)](https://git.io/typing-svg)

<img src="https://img.shields.io/badge/version-1.0.0-lime?style=for-the-badge" alt="Version" />
<img src="https://img.shields.io/badge/license-MIT-green?style=for-the-badge" alt="License" />
<img src="https://img.shields.io/badge/status-Active-success?style=for-the-badge" alt="Status" />

<br/>

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MongoDB](https://img.shields.io/badge/MongoDB-47A248?style=for-the-badge&logo=mongodb&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=black)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Cloudinary](https://img.shields.io/badge/Cloudinary-3448C5?style=for-the-badge&logo=cloudinary&logoColor=white)

**Hệ thống thương mại điện tử mua bán Laptop & Thiết bị công nghệ hiệu năng cao VNTech, tích hợp giao diện Cyberpunk Glassmorphism hiện đại và cơ sở dữ liệu MongoDB!**

</div>

---

## Cấu trúc dự án

```
PHP_VNTech/
├── app/
│   ├── Http/
│   │   └── Controllers/        # Bộ điều khiển (HomeController, CartController, OrderController,...)
│   └── Models/                 # Thực thể dữ liệu MongoDB (Product, ProductVariant, Cart, Order,...)
├── config/                     # Cấu hình hệ thống (database, cloudinary,...)
├── database/
│   ├── migrations/             # Lược đồ cơ sở dữ liệu MongoDB
│   └── seeders/                # Bộ tạo dữ liệu mẫu thông số kỹ thuật động
├── public/                     # Tài nguyên tĩnh công khai (CSS, JS, Images,...)
├── resources/
│   ├── css/                    # Custom CSS cho giao diện (Neon, Glassmorphism,...)
│   ├── js/                     # Custom JS xử lý logic
│   └── views/                  # Giao diện Blade templates
│       ├── adminUI/            # Giao diện quản trị Admin (Dashboard, CRUD sản phẩm,...)
│       ├── auth/               # Đăng nhập, đăng ký, Google OAuth
│       └── homeUI/             # Trang chủ, chi tiết sản phẩm, giỏ hàng, thanh toán
├── routes/
│   └── web.php                 # Hệ thống định tuyến chính của ứng dụng
└── composer.json               # Quản lý các thư viện PHP phụ thuộc
```

---

## Tính năng chính

### Giao diện Khách hàng (Client UI)
- **Trang chủ Cyberpunk**: Banner chuyển động, hiệu ứng neon rực rỡ, hiển thị danh mục sản phẩm cùng các bộ lọc thương hiệu trực quan.
- **Trang Chi tiết Sản phẩm**: 
  - Trình duyệt ảnh thu nhỏ thông minh dạng dòng (thumbnail slider).
  - Chọn cấu hình biến thể (RAM/SSD) phản hồi tức thì về giá bán, giá niêm yết và phần trăm giảm giá.
  - Bảng **Thông số kỹ thuật** tải động theo biến thể được chọn thời gian thực bằng Alpine.js.
- **Hệ thống Giỏ hàng**: AJAX cập nhật số lượng, xóa sản phẩm, chọn các sản phẩm riêng biệt để chuyển đến trang thanh toán.
- **Thanh toán nhanh (Mua ngay)**: Chuyển hướng trực tiếp cấu hình đang xem đến trang điền thông tin hóa đơn.
- **Đăng nhập & Đăng ký**: Hỗ trợ xác thực bảo mật và liên kết đăng nhập nhanh thông qua **Google Socialite**.

### Giao diện Quản trị (Admin Panel)
- **Bảng điều khiển (Dashboard)**: Biểu đồ thống kê trực quan doanh thu, số lượng đơn hàng và các mặt hàng bán chạy.
- **Quản lý Sản phẩm**:
  - Hủy bỏ các cấu trúc Slug và SKU truyền thống, chuyển sang định danh tối ưu `ma_san_pham`.
  - Tạo mới và cập nhật thông tin sản phẩm, mô tả ngắn/chi tiết cùng bộ thông số chung bằng giao diện bảng động (cho phép thêm/xóa dòng).
- **Quản lý Biến thể**: Cho phép tạo và cập nhật các biến thể của sản phẩm (RAM, SSD, màu sắc...) kèm theo bộ thông số kỹ thuật riêng và tải lên hình ảnh đại diện qua Cloudinary.
- **Quản lý Đơn hàng**: Theo dõi danh sách đơn đặt hàng, chi tiết hóa đơn và cập nhật trạng thái đơn hàng (Chờ xử lý, Đang giao, Đã hoàn thành, Đã hủy).

---

## Công nghệ sử dụng

### Backend Stack
- **PHP 8.2 & Laravel 12**: Framework PHP hiện đại, mạnh mẽ với hiệu năng cải tiến vượt trội.
- **MongoDB (mongodb/laravel-mongodb)**: Cơ sở dữ liệu NoSQL lưu trữ dữ liệu dạng tài liệu linh hoạt (JSON-like).
- **Cloudinary Integration**: Xử lý và lưu trữ hình ảnh đám mây tối ưu tốc độ tải trang.
- **Laravel Socialite**: Quản lý đăng nhập OAuth2 với bên thứ ba (Google).

### Frontend Stack
- **Blade Layouts**: Công cụ tạo mẫu giao diện mạnh mẽ có sẵn của Laravel.
- **Alpine.js**: Thư viện JavaScript nhỏ gọn hỗ trợ quản lý trạng thái động (State management) trực tiếp trên giao diện Client.
- **Tailwind CSS**: Framework CSS tiện ích thiết kế giao diện Glassmorphism độc bản, bóng bẩy.
- **Lucide Icons**: Bộ biểu tượng dạng vector hiện đại và sắc nét.

---

## Cài đặt hệ thống

### Yêu cầu hệ thống
- **PHP**: `>= 8.2`
- **Composer**: Quản lý thư viện PHP.
- **Node.js & NPM**: Biên dịch CSS/JS.
- **MongoDB**: Đã được cài đặt và kích hoạt kết nối.

### Các bước thiết lập

1. **Clone dự án & Cài đặt thư viện**:
   ```bash
   git clone https://github.com/hcmue-hmduck/PHP_VNTech_Web.git
   cd PHP_VNTech
   composer install
   npm install
   ```

2. **Cấu hình môi trường (`.env`)**:
   Sao chép file cấu hình mẫu và điền thông tin kết nối cơ sở dữ liệu MongoDB và Cloudinary:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Cấu hình MongoDB mẫu trong `.env`:*
   ```env
   DB_CONNECTION=mongodb
   DB_HOST=127.0.0.1
   DB_PORT=27017
   DB_DATABASE=php_vntech
   ```

3. **Cập nhật database & Nạp dữ liệu mẫu (Seeder)**:
   Lệnh này sẽ làm sạch các bảng và nạp lại toàn bộ danh mục sản phẩm kèm thông số kỹ thuật mẫu chất lượng cao:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Khởi động Server phát triển**:
   ```bash
   php artisan serve
   ```
   Ứng dụng sẽ hoạt động tại địa chỉ: `http://127.0.0.1:8000`.

---

<div align="center">

Dự án **PHP_VNTech** được tối ưu hóa toàn diện về cơ sở dữ liệu NoSQL và thiết kế UI/UX hiện đại! 💻✨

</div>
