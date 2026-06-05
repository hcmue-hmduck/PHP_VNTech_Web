<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\FlashSaleItem;

class CartSeed extends Seeder
{
    public function run(): void
    {
        Cart::query()->delete();
        CartItem::query()->delete();

        // Lấy tất cả users
        $allUsers = User::all();
        if ($allUsers->isEmpty()) {
            $this->command->warn('No users found. Skipping CartSeed.');
            return;
        }

        // Lấy tất cả active variants
        $allVariants = ProductVariant::where('trang_thai', 'active')->get();
        if ($allVariants->isEmpty()) {
            $this->command->warn('No active variants found. Skipping CartSeed.');
            return;
        }

        // Lấy flash sale items đang active
        $flashSaleItems = FlashSaleItem::where('trang_thai', 'active')->get();
        $flashSaleVariantIds = $flashSaleItems->pluck('ma_bien_the')->toArray();

        // Chọn 5 người ngẫu nhiên: ưu tiên lấy 1 admin trước
        $admin = $allUsers->where('vai_tro', 'admin')->first();
        $nonAdmins = $allUsers->where('vai_tro', '!=', 'admin')->shuffle()->take(4);
        $selectedUsers = collect();
        if ($admin) {
            $selectedUsers->push($admin);
        }
        foreach ($nonAdmins as $u) {
            $selectedUsers->push($u);
        }
        // Nếu không đủ 5 (không có admin), bổ sung thêm
        if ($selectedUsers->count() < 5) {
            $extra = $allUsers->whereNotIn('ma_nguoi_dung', $selectedUsers->pluck('ma_nguoi_dung'))
                ->shuffle()->take(5 - $selectedUsers->count());
            foreach ($extra as $u) {
                $selectedUsers->push($u);
            }
        }
        $selectedUserIds = $selectedUsers->pluck('ma_nguoi_dung')->toArray();

        // Lấy 1 variant thuộc flash sale (nếu có)
        $flashVariant = $allVariants->whereIn('ma_bien_the', $flashSaleVariantIds)->first();

        // Tạo cart cho tất cả users
        foreach ($allUsers as $user) {
            $cart = Cart::create([
                'ma_gio_hang' => 'temp',
                'ma_nguoi_dung' => $user->ma_nguoi_dung,
                'trang_thai' => 'active',
            ]);
            $cart->update(['ma_gio_hang' => (string) $cart->_id]);

            // Chọn ít nhất 3 variants ngẫu nhiên cho cart item bình thường
            $cartVariants = $allVariants->whereNotIn('ma_bien_the', $flashSaleVariantIds)
                ->shuffle()->take(rand(3, 5));

            // Kiểm tra user này có thuộc 5 người được chọn không
            $isSelectedUser = in_array($user->ma_nguoi_dung, $selectedUserIds);

            // Nếu là user được chọn và có flash sale variant → thêm 1 flash sale item
            $addedVariantIds = [];
            if ($isSelectedUser && $flashVariant) {
                CartItem::create([
                    'ma_gio_hang' => $cart->ma_gio_hang,
                    'ma_bien_the' => $flashVariant->ma_bien_the,
                    'so_luong' => rand(1, 2), // giới hạn mua flash sale thường nhỏ
                ]);
                $addedVariantIds[] = $flashVariant->ma_bien_the;
            }

            // Thêm các sản phẩm bình thường
            foreach ($cartVariants as $v) {
                if (in_array($v->ma_bien_the, $addedVariantIds)) continue;
                CartItem::create([
                    'ma_gio_hang' => $cart->ma_gio_hang,
                    'ma_bien_the' => $v->ma_bien_the,
                    'so_luong' => rand(1, 5),
                ]);
                $addedVariantIds[] = $v->ma_bien_the;
            }
        }
    }
}
