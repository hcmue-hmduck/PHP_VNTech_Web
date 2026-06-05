<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        | CLEAR DATABASE (theo đúng thứ tự phụ thuộc)
        |--------------------------------------------------------------------------
        */
        Review::query()->delete();
        OrderItem::query()->delete();
        Order::query()->delete();
        CartItem::query()->delete();
        Cart::query()->delete();
        FlashSaleItem::query()->delete();
        FlashSales::query()->delete();
        Voucher::query()->delete();
        UserAddress::query()->delete();
        User::query()->delete();
        ProductVariant::query()->delete();
        Product::query()->delete();
        Brand::query()->delete();
        Category::query()->delete();

        /*
        |--------------------------------------------------------------------------
        | SEED (theo đúng thứ tự phụ thuộc)
        |--------------------------------------------------------------------------
        */
        $this->call([
            BrandSeed::class,           // 1. Hãng sản xuất (không phụ thuộc)
            CategorySeed::class,        // 2. Danh mục (không phụ thuộc)
            Products_VariantsSeed::class, // 3. Sản phẩm + Biến thể (cần Brand, Category)
            UserSeed::class,            // 4. Người dùng
            UserAddressSeed::class,     // 5. Địa chỉ người dùng (cần User)
            VoucherSeed::class,         // 6. Voucher (không phụ thuộc)
            FlashSaleSeed::class,       // 7. Flash Sale + Items (cần Product, Variant)
            CartSeed::class,            // 8. Giỏ hàng (cần User, Variant, FlashSale)
            OrderSeed::class,           // 9. Đơn hàng + Reviews (cần User, Variant, Voucher, FlashSale, UserAddress)
        ]);
    }
}
