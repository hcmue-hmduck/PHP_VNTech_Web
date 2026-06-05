<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeed extends Seeder
{
    public function run(): void
    {
        Voucher::query()->delete();

        $vouchers = [
            [
                'ten_voucher'        => 'VNTECHWELCOME',
                'mo_ta'               => 'Giảm 10% cho khách hàng mới đăng ký tài khoản (Tối đa 100.000đ)',
                'hinh_thuc_giam'      => 'percent',
                'gia_tri_giam'        => 10,
                'muc_giam_toi_da'     => 100000,
                'don_hang_toi_thieu'  => 0,
                'tong_luot_dung'      => 500,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(5),
                'ket_thuc'            => Carbon::now()->addDays(90),
                'trang_thai'          => 'active',
            ],
            [
                'ten_voucher'        => 'HE2026',
                'mo_ta'               => 'Mừng hè rực rỡ, giảm 5% cho đơn hàng từ 2.000.000đ (Tối đa 200.000đ)',
                'hinh_thuc_giam'      => 'percent',
                'gia_tri_giam'        => 5,
                'muc_giam_toi_da'     => 200000,
                'don_hang_toi_thieu'  => 2000000,
                'tong_luot_dung'      => 300,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(1),
                'ket_thuc'            => Carbon::now()->addDays(60),
                'trang_thai'          => 'active',
            ],
            [
                'ten_voucher'        => 'LAPTOPMAX',
                'mo_ta'               => 'Giảm ngay 500.000đ khi mua sắm Laptop/PC cho đơn hàng từ 12.000.000đ',
                'hinh_thuc_giam'      => 'money',
                'gia_tri_giam'        => 500000,
                'muc_giam_toi_da'     => 500000,
                'don_hang_toi_thieu'  => 12000000,
                'tong_luot_dung'      => 100,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(2),
                'ket_thuc'            => Carbon::now()->addDays(45),
                'trang_thai'          => 'active',
            ],
            [
                'ten_voucher'        => 'BUILDPCMEGA',
                'mo_ta'               => 'Siêu ưu đãi build cấu hình PC, giảm thẳng 1.500.000đ cho đơn hàng từ 35.000.000đ',
                'hinh_thuc_giam'      => 'money',
                'gia_tri_giam'        => 1500000,
                'muc_giam_toi_da'     => 1500000,
                'don_hang_toi_thieu'  => 35000000,
                'tong_luot_dung'      => 50,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(3),
                'ket_thuc'            => Carbon::now()->addDays(30),
                'trang_thai'          => 'active',
            ],
            [
                'ten_voucher'        => 'GAMINGGEAR',
                'mo_ta'               => 'Ưu đãi Gaming Gear, phụ kiện gaming giảm 12% tối đa 150.000đ cho đơn từ 1.000.000đ',
                'hinh_thuc_giam'      => 'percent',
                'gia_tri_giam'        => 12,
                'muc_giam_toi_da'     => 150000,
                'don_hang_toi_thieu'  => 1000000,
                'tong_luot_dung'      => 200,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(4),
                'ket_thuc'            => Carbon::now()->addDays(45),
                'trang_thai'          => 'active',
            ],
            [
                'ten_voucher'        => 'SHIP30K',
                'mo_ta'               => 'Hỗ trợ phí vận chuyển 30.000đ cho đơn hàng từ 500.000đ',
                'hinh_thuc_giam'      => 'money',
                'gia_tri_giam'        => 30000,
                'muc_giam_toi_da'     => 30000,
                'don_hang_toi_thieu'  => 500000,
                'tong_luot_dung'      => 1000,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(10),
                'ket_thuc'            => Carbon::now()->addDays(180),
                'trang_thai'          => 'active',
            ],
            [
                'ten_voucher'        => 'TRIANVIP',
                'mo_ta'               => 'Tri ân khách hàng thân thiết VNTech, giảm 8% (Tối đa 1.000.000đ) cho đơn từ 10.000.000đ',
                'hinh_thuc_giam'      => 'percent',
                'gia_tri_giam'        => 8,
                'muc_giam_toi_da'     => 1000000,
                'don_hang_toi_thieu'  => 10000000,
                'tong_luot_dung'      => 100,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(1),
                'ket_thuc'            => Carbon::now()->addDays(30),
                'trang_thai'          => 'active',
            ],
            [
                'ten_voucher'        => 'EXPIRED50K',
                'mo_ta'               => 'Mã giảm giá đã hết hạn, giảm 50.000đ cho đơn từ 1.000.000đ (Dùng để kiểm tra bộ lọc)',
                'hinh_thuc_giam'      => 'money',
                'gia_tri_giam'        => 50000,
                'muc_giam_toi_da'     => 50000,
                'don_hang_toi_thieu'  => 1000000,
                'tong_luot_dung'      => 100,
                'da_dung'             => 0,
                'bat_dau'             => Carbon::now()->subDays(30),
                'ket_thuc'            => Carbon::now()->subDays(1),
                'trang_thai'          => 'active',
            ],
        ];

        // 3. Tiến hành tạo các Vouchers vào database và lưu ID vào ma_voucher
        foreach ($vouchers as $vData) {
            $voucher = Voucher::create($vData);
            
            // Cập nhật ma_voucher bằng chuỗi string representation của MongoDB ObjectId (_id)
            $voucher->update([
                'ma_voucher' =>  $voucher->_id,
            ]);
        }
    }
}
