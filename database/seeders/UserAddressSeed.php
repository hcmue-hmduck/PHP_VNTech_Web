<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAddress;

class UserAddressSeed extends Seeder
{
    public function run(): void
    {
        UserAddress::query()->delete();

        // Danh sách Tỉnh/Thành và các Phường/Quận tương ứng (2 cấp)
        $locations = [
            'Thành phố Hà Nội' => [
                'Phường Ba Đình',
                'Phường Ngọc Hà',
                'Phường Giảng Võ',
                'Phường Kim Mã',
                'Phường Cống Vị',
                'Phường Nguyễn Trung Trực',
                'Phường Trúc Bạch',
                'Phường Quán Thánh',
                'Phường Thành Công'
            ],
            'Thành phố Hồ Chí Minh' => [
                'Quận 1',
                'Quận 3',
                'Quận 4',
                'Quận 5',
                'Quận 10',
                'Quận Bình Thạnh',
                'Quận Phú Nhuận',
                'Quận Gò Vấp',
                'Quận Tân Bình'
            ],
            'Thành phố Đà Nẵng' => [
                'Quận Hải Châu',
                'Quận Thanh Khê',
                'Quận Sơn Trà',
                'Quận Ngũ Hành Sơn',
                'Quận Liên Chiểu',
                'Quận Cẩm Lệ'
            ],
            'Thành phố Hải Phòng' => [
                'Quận Hồng Bàng',
                'Quận Ngô Quyền',
                'Quận Lê Chân',
                'Quận Hải An',
                'Quận Kiến An',
                'Quận Đồ Sơn'
            ],
            'Thành phố Cần Thơ' => [
                'Quận Ninh Kiều',
                'Quận Bình Thủy',
                'Quận Cái Răng',
                'Quận Ô Môn',
                'Quận Thốt Nốt',
                'Huyện Phong Điền'
            ]
        ];

        // Danh sách tên người nhận phong phú và có ghi chú kiểu Shopee
        $shopeeNames = [
            'Anh Tuấn (Ship giờ hành chính)',
            'Chị Lan (Gọi trước khi giao)',
            'Minh Béo (Gửi bảo vệ)',
            'Vy Vy (Không gọi buổi trưa)',
            'Hoàng (Giao sau 5h chiều)',
            'Thảo Ngân (Chung cư A1 - phòng 1205)',
            'Đức Huy (Giao phòng 502)',
            'Hương Giang (Cửa hàng hoa)',
            'Bảo Lâm (Nhận hộ)',
            'Mẹ Cún (Giao cuối tuần)',
            'Khánh Linh (Phòng Bảo Vệ)',
            'Duy Anh (Nhà riêng)',
            'Lê Thu Trang (Gọi trước 15p)',
            'Nguyễn Quốc Anh',
            'Phạm Hà My',
            'Đỗ Minh Quân',
            'Trần Hoàng Yến',
            'Vũ Đức Thịnh (Gọi điện trước)',
            'Hoàng Thu Thảo',
            'Nguyễn Duy Khánh (Nhà trong ngõ)',
            'Bảo Nam (Nhận tại quầy lễ tân)',
            'Quỳnh Chi (Nhà riêng - gọi trước)',
            'Tùng Lâm (Giao giờ hành chính)',
            'Huyền My (Gửi hàng xóm)'
        ];

        // Các địa chỉ chi tiết ngẫu nhiên
        $detailAddresses = [
            'Số 12 ngõ 45 đường Lê Lợi',
            'Chung cư Sunrise City, block B, phòng 1504',
            'Số 789 Nguyễn Văn Linh',
            'Số 56/8 đường Cách Mạng Tháng 8',
            'Số 234 Điện Biên Phủ',
            'Số 88 Trần Hưng Đạo',
            'Tầng 10, Tòa nhà Landmark 81',
            'Số 15 ngách 2/4 ngõ Thổ Quan',
            'Số 420 Nguyễn Trãi',
            'Số 35 Đường 3/2',
            'Số 112 Hùng Vương',
            'Căn hộ 4B chung cư Hòa Bình',
            'Số 99 Lê Hồng Phong',
            'Số 12 ngõ 24 Hoàng Quốc Việt'
        ];

        // Lấy tất cả tài khoản (admin và user thường) để tạo địa chỉ
        $users = User::all();

        foreach ($users as $user) {
            $count = rand(1, 3);
            
            for ($i = 0; $i < $count; $i++) {
                $tinhThanh = array_rand($locations);
                $phuongXa = $locations[$tinhThanh][array_rand($locations[$tinhThanh])];

                $user_add = UserAddress::create([
                    'ma_nguoi_dung'    => $user->ma_nguoi_dung,
                    'ho_ten'           => $shopeeNames[array_rand($shopeeNames)],
                    'so_dien_thoai'    => '0' . rand(32, 99) . rand(1000000, 9999999),
                    'dia_chi_chi_tiet' => $detailAddresses[array_rand($detailAddresses)],
                    'tinh_thanh'       => $tinhThanh,
                    'quan_huyen'       => '', // Trống theo yêu cầu 2 cấp
                    'phuong_xa'        => $phuongXa,
                    'is_default'       => ($i === 0), // Địa chỉ đầu tiên sẽ là mặc định
                ]);

                $user_add->ma_dia_chi = $user_add->_id;
                $user_add->save();
            }
        }
    }
}
