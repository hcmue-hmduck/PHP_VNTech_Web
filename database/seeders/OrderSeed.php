<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\FlashSaleItem;
use App\Models\Voucher;
use App\OrderStatus;

class OrderSeed extends Seeder
{
    public function run(): void
    {
        Order::query()->delete();
        OrderItem::query()->delete();
        Review::query()->delete();

        $allUsers = User::all();
        if ($allUsers->isEmpty()) {
            $this->command->warn('No users found. Skipping OrderSeed.');
            return;
        }

        $allVariants = ProductVariant::where('trang_thai', 'active')->with('product')->get();
        if ($allVariants->isEmpty()) {
            $this->command->warn('No active variants found. Skipping OrderSeed.');
            return;
        }

        // Flash sale items
        $flashSaleItems = FlashSaleItem::where('trang_thai', 'active')->get()->keyBy('ma_bien_the');

        // Active vouchers có thể dùng
        $availableVouchers = Voucher::active()->get();

        // 10 status profiles: 5 completed + 5 other (momo chưa hoàn thành => cho_thanh_toan)
        // Index 0-1 sẽ được đánh dấu dùng voucher (2 trong 10)
        // Possible: cho_thanh_toan, cho_xac_nhan, da_xac_nhan, dang_giao_hang, da_nhan_hang, da_huy
        $statusProfiles = [
            ['trang_thai' => OrderStatus::DELIVERED->value, 'pttt' => 'cod',  'use_voucher' => true],
            ['trang_thai' => OrderStatus::DELIVERED->value, 'pttt' => 'momo', 'use_voucher' => true],
            ['trang_thai' => OrderStatus::DELIVERED->value, 'pttt' => 'cod',  'use_voucher' => false],
            ['trang_thai' => OrderStatus::DELIVERED->value, 'pttt' => 'cod',  'use_voucher' => false],
            ['trang_thai' => OrderStatus::DELIVERED->value, 'pttt' => 'momo', 'use_voucher' => false],
            ['trang_thai' => OrderStatus::PENDING_PAYMENT->value, 'pttt' => 'momo', 'use_voucher' => false],
            ['trang_thai' => OrderStatus::WAITING_PICKUP->value, 'pttt' => 'cod',  'use_voucher' => false],
            ['trang_thai' => OrderStatus::WAITING_DELIVERY->value, 'pttt' => 'cod',  'use_voucher' => false],
            ['trang_thai' => OrderStatus::WAITING_PICKUP->value, 'pttt' => 'momo', 'use_voucher' => false],
            ['trang_thai' => OrderStatus::CANCELLED->value, 'pttt' => 'cod',  'use_voucher' => false],
        ];

        // Review samples
        $reviewContents = [
            5 => ['Sản phẩm tuyệt vời, đúng như mô tả!', 'Rất hài lòng, giao hàng nhanh.', 'Chất lượng tốt, giá hợp lý.'],
            4 => ['Sản phẩm tốt, nhưng đóng gói chưa chắc.', 'Khá ổn, sẽ mua lại.', 'Hàng chuẩn, giao hơi chậm một chút.'],
            3 => ['Bình thường, không có gì đặc biệt.', 'Tạm được, giá hơi cao.', 'Đúng mô tả nhưng chất lượng chỉ ở mức trung bình.'],
        ];

        // Theo dõi sales & reviews để update cuối
        $variantSalesMap = []; // ma_bien_the => da_ban count
        $productReviewMap = []; // ma_san_pham => [so_sao array]

        $completedOrderIndices = []; // track order items of completed orders for reviews
        $totalOrdersCount = 30;

        for ($i = 0; $i < $totalOrdersCount; $i++) {
            $user = $allUsers->random();

            // Lấy địa chỉ ngẫu nhiên của user này
            $userAddresses = UserAddress::where('ma_nguoi_dung', $user->ma_nguoi_dung)->get();
            if ($userAddresses->isEmpty()) {
                $addrHoTen  = $user->ho_ten;
                $addrSdt    = $user->so_dien_thoai ?? '0900000000';
                $addrDiaChi = 'Địa chỉ chưa cập nhật';
            } else {
                $addr = $userAddresses->random();
                $addrHoTen  = $addr->ho_ten;
                $addrSdt    = $addr->so_dien_thoai;
                $addrDiaChi = implode(', ', array_filter([
                    $addr->dia_chi_chi_tiet,
                    $addr->phuong_xa,
                    $addr->quan_huyen,
                    $addr->tinh_thanh,
                ]));
            }

            // Lấy một profile ngẫu nhiên từ $statusProfiles
            $profile = $statusProfiles[array_rand($statusProfiles)];
            $pttt       = $profile['pttt'];
            $trangThai  = $profile['trang_thai'];
            $useVoucher = $profile['use_voucher'];

            // Momo + không hoàn thành => cho_thanh_toan
            if ($pttt === 'momo' && $trangThai !== OrderStatus::DELIVERED->value) {
                $trangThai  = OrderStatus::PENDING_PAYMENT->value;
                $useVoucher = false; // Đơn chưa thanh toán không áp voucher
            }

            // Pick 2-3 random variants for this order
            $orderVariants = $allVariants->shuffle()->take(rand(2, 3));

            $tongTienHang   = 0;
            $orderItemsData = [];

            foreach ($orderVariants as $variant) {
                $qty         = rand(1, 3);
                $isFlashSale = isset($flashSaleItems[$variant->ma_bien_the]);
                $gia         = $isFlashSale
                    ? (int) $flashSaleItems[$variant->ma_bien_the]->gia_flash_sale
                    : (int) $variant->gia_ban;
                $thanhTien    = $gia * $qty;
                $tongTienHang += $thanhTien;

                $orderItemsData[] = [
                    'ma_bien_the'    => $variant->ma_bien_the,
                    'ten_bien_the'   => trim(($variant->product->ten_san_pham ?? '') . ' ' . ($variant->ten_bien_the ?? '')),
                    'link_anh'       => $variant->link_anh_bien_the ?? ($variant->product->link_anh_dai_dien ?? null),
                    'so_luong'       => $qty,
                    'gia_ban'        => $gia,
                    'thanh_tien'     => $thanhTien,
                    'ma_flash_sales' => $isFlashSale ? $flashSaleItems[$variant->ma_bien_the]->ma_flash_sales : null,
                    'ma_san_pham'    => $variant->ma_san_pham,
                ];

                // Chỉ cộng sales cho đơn hoàn thành
                if ($trangThai === OrderStatus::DELIVERED->value) {
                    $variantSalesMap[$variant->ma_bien_the] =
                        ($variantSalesMap[$variant->ma_bien_the] ?? 0) + $qty;
                }
            }

            // === Tính voucher ===
            $maVoucher         = null;
            $giaTriGiamVoucher = 0;
            if ($useVoucher && $availableVouchers->isNotEmpty()) {
                $voucher = $availableVouchers->random();

                // Kiểm tra đơn tối thiểu
                if ($tongTienHang >= (int) $voucher->don_hang_toi_thieu) {
                    $maVoucher = $voucher->ma_voucher;

                    if ($voucher->hinh_thuc_giam === 'percent') {
                        $giaTriGiamVoucher = (int) round($tongTienHang * $voucher->gia_tri_giam / 100);
                        if ($voucher->muc_giam_toi_da > 0) {
                            $giaTriGiamVoucher = min($giaTriGiamVoucher, (int) $voucher->muc_giam_toi_da);
                        }
                    } else {
                        // fixed
                        $giaTriGiamVoucher = min((int) $voucher->gia_tri_giam, $tongTienHang);
                    }
                }
            }

            $phiVanChuyen = rand(0, 1) ? 0 : 30000;
            $tongThanhToan = max(0, $tongTienHang - $giaTriGiamVoucher) + $phiVanChuyen;

            // Create order
            $order = Order::create([
                'ma_don_hang'            => 'temp',
                'ma_nguoi_dung'          => $user->ma_nguoi_dung,
                'ho_ten_nguoi_nhan'      => $addrHoTen,
                'so_dien_thoai_nhan'     => $addrSdt,
                'dia_chi_giao_hang'      => $addrDiaChi,
                'ghi_chu'                => null,
                'ma_voucher'             => $maVoucher,
                'tong_tien_hang'         => $tongTienHang,
                'phi_van_chuyen'         => $phiVanChuyen,
                'gia_tri_giam_voucher'   => $giaTriGiamVoucher,
                'tong_thanh_toan'        => $tongThanhToan,
                'phuong_thuc_thanh_toan' => $pttt,
                'trang_thai'             => $trangThai,
            ]);
            $order->update(['ma_don_hang' =>  $order->_id]);

            // Create order items
            foreach ($orderItemsData as $itemData) {
                $oi = OrderItem::create([
                    'ma_chi_tiet_don_hang' => 'temp',
                    'ma_don_hang'          => $order->ma_don_hang,
                    'ma_bien_the'          => $itemData['ma_bien_the'],
                    'ten_bien_the'         => $itemData['ten_bien_the'],
                    'link_anh_dai_dien'    => $itemData['link_anh'],
                    'so_luong'             => $itemData['so_luong'],
                    'gia_ban'              => $itemData['gia_ban'],
                    'thanh_tien'           => $itemData['thanh_tien'],
                    'ma_flash_sales'       => $itemData['ma_flash_sales'],
                ]);
                $oi->update(['ma_chi_tiet_don_hang' =>  $oi->_id]);

                // Track completed orders for review
                if ($trangThai === OrderStatus::DELIVERED->value) {
                    $completedOrderIndices[] = [
                        'order'        => $order,
                        'order_item'   => $oi,
                        'ma_san_pham'  => $itemData['ma_san_pham'],
                        'ma_bien_the'  => $itemData['ma_bien_the'],
                        'ten_bien_the' => $itemData['ten_bien_the'],
                        'ma_nguoi_dung'=> $user->ma_nguoi_dung,
                    ];
                }
            }
        }

        // === Tạo review: lấy tối đa 12 đơn hoàn thành ngẫu nhiên để đánh giá ===
        $reviewedOrders = collect($completedOrderIndices)
            ->unique(fn($x) => $x['order']->ma_don_hang)
            ->shuffle()
            ->take(12);

        foreach ($reviewedOrders as $reviewItem) {
            $soSao    = [3, 4, 4, 5, 5][array_rand([3, 4, 4, 5, 5])];
            $contents = $reviewContents[$soSao] ?? $reviewContents[4];
            $noiDung  = $contents[array_rand($contents)];

            $review = Review::create([
                'ma_danh_gia'          => 'temp',
                'ma_san_pham'          => $reviewItem['ma_san_pham'],
                'ma_bien_the'          => $reviewItem['ma_bien_the'],
                'ma_nguoi_dung'        => $reviewItem['ma_nguoi_dung'],
                'ma_don_hang'          => $reviewItem['order']->ma_don_hang,
                'ma_chi_tiet_don_hang' => $reviewItem['order_item']->ma_chi_tiet_don_hang,
                'ten_bien_the'         => $reviewItem['ten_bien_the'],
                'so_sao'               => $soSao,
                'noi_dung'             => $noiDung,
                'danh_sach_anh'        => [],
                'video'                => [],
                'is_anonymous'         => (bool) rand(0, 1),
                'trang_thai'           => 'active',
            ]);
            $review->update(['ma_danh_gia' =>  $review->_id]);

            $productReviewMap[$reviewItem['ma_san_pham']][] = $soSao;
        }

        // === Update da_ban trên variants (từ đơn hoàn thành) ===
        foreach ($variantSalesMap as $maBienThe => $soLuong) {
            ProductVariant::where('ma_bien_the', $maBienThe)
                ->increment('da_ban', $soLuong);
        }

        // === Update tong_luot_ban trên products ===
        $productSalesMap = [];
        foreach ($variantSalesMap as $maBienThe => $soLuong) {
            $v = ProductVariant::where('ma_bien_the', $maBienThe)->first(['ma_san_pham']);
            if ($v) {
                $productSalesMap[$v->ma_san_pham] = ($productSalesMap[$v->ma_san_pham] ?? 0) + $soLuong;
            }
        }
        foreach ($productSalesMap as $maSP => $total) {
            Product::where('ma_san_pham', $maSP)->increment('tong_luot_ban', $total);
        }

        // === Update review stats trên products — PHẢI khớp chính xác với syncProductReviewStats() ===
        foreach (array_unique(array_keys($productReviewMap)) as $maSP) {
            $reviews = Review::where('ma_san_pham', $maSP)
                ->where('trang_thai', 'active')
                ->get(['so_sao']);

            $reviewCount = $reviews->count();
            // Dùng pluck()->sum() thay vì closure để tránh vấn đề với MongoDB collection
            $totalRating = (int) $reviews->pluck('so_sao')->sum();

            Product::where('ma_san_pham', $maSP)->update([
                'tong_so_sao'       => $totalRating,
                'so_luot_danh_gia'  => $reviewCount,
                'so_sao_trung_binh' => $reviewCount > 0 ? round($totalRating / $reviewCount, 2) : 0,
            ]);
        }
    }
}
