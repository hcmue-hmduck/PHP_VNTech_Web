<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeed extends Seeder
{
    public function run(): void
    {
        User::query()->delete();
        
        $usersData = [
            // --- ADMINS ---
            [
                'ho_ten'        => 'Administrator',
                'email'         => 'admin@gmail.com',
                'so_dien_thoai' => '0900000001',
                'vai_tro'       => 'admin',
                'avatar_url'    => 'avatars/admin.jpg',
                'bio'           => 'Quản trị viên',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'VNTech Admin Assistant',
                'email'         => 'admin2@gmail.com',
                'so_dien_thoai' => '0900000004',
                'vai_tro'       => 'admin',
                'avatar_url'    => 'avatars/admin2.jpg',
                'bio'           => 'Quản trị viên phụ',
                'trang_thai'    => 'active',
            ],
            
            // --- USERS ---
            [
                'ho_ten'        => 'Nguyen Van A',
                'email'         => 'user1@gmail.com',
                'so_dien_thoai' => '0900000002',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user1.jpg',
                'bio'           => 'Khách hàng 1',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Tran Thi B',
                'email'         => 'user2@gmail.com',
                'so_dien_thoai' => '0900000003',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user2.jpg',
                'bio'           => 'Khách hàng 2',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Lê Hoàng Nam',
                'email'         => 'user3@gmail.com',
                'so_dien_thoai' => '0900000005',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user3.jpg',
                'bio'           => 'Khách hàng 3',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Phạm Minh Tuấn',
                'email'         => 'user4@gmail.com',
                'so_dien_thoai' => '0900000006',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user4.jpg',
                'bio'           => 'Khách hàng 4',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Nguyễn Thị Mai',
                'email'         => 'user5@gmail.com',
                'so_dien_thoai' => '0900000007',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user5.jpg',
                'bio'           => 'Khách hàng 5',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Hoàng Xuân Bách',
                'email'         => 'user6@gmail.com',
                'so_dien_thoai' => '0900000008',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user6.jpg',
                'bio'           => 'Khách hàng 6',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Vũ Anh Dũng',
                'email'         => 'user7@gmail.com',
                'so_dien_thoai' => '0900000009',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user7.jpg',
                'bio'           => 'Khách hàng 7',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Phan Thanh Thủy',
                'email'         => 'user8@gmail.com',
                'so_dien_thoai' => '0900000010',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user8.jpg',
                'bio'           => 'Khách hàng 8',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Đỗ Duy Mạnh',
                'email'         => 'user9@gmail.com',
                'so_dien_thoai' => '0900000011',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user9.jpg',
                'bio'           => 'Khách hàng 9',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Bùi Hồng Nhung',
                'email'         => 'user10@gmail.com',
                'so_dien_thoai' => '0900000012',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user10.jpg',
                'bio'           => 'Khách hàng 10',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Trịnh Quốc Bảo',
                'email'         => 'user11@gmail.com',
                'so_dien_thoai' => '0900000013',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user11.jpg',
                'bio'           => 'Khách hàng 11',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Đặng Minh Hằng',
                'email'         => 'user12@gmail.com',
                'so_dien_thoai' => '0900000014',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user12.jpg',
                'bio'           => 'Khách hàng 12',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Đinh Tiến Đạt',
                'email'         => 'user13@gmail.com',
                'so_dien_thoai' => '0900000015',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user13.jpg',
                'bio'           => 'Khách hàng 13',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Lý Gia Hân',
                'email'         => 'user14@gmail.com',
                'so_dien_thoai' => '0900000016',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user14.jpg',
                'bio'           => 'Khách hàng 14',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Trần Thế Vinh',
                'email'         => 'user15@gmail.com',
                'so_dien_thoai' => '0900000017',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user15.jpg',
                'bio'           => 'Khách hàng 15',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Võ Hoàng Yến',
                'email'         => 'user16@gmail.com',
                'so_dien_thoai' => '0900000018',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user16.jpg',
                'bio'           => 'Khách hàng 16',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Ngô Bảo Châu',
                'email'         => 'user17@gmail.com',
                'so_dien_thoai' => '0900000019',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user17.jpg',
                'bio'           => 'Khách hàng 17',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Dương Cẩm Ly',
                'email'         => 'user18@gmail.com',
                'so_dien_thoai' => '0900000020',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user18.jpg',
                'bio'           => 'Khách hàng 18',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Lâm Vĩnh Hải',
                'email'         => 'user19@gmail.com',
                'so_dien_thoai' => '0900000021',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user19.jpg',
                'bio'           => 'Khách hàng 19',
                'trang_thai'    => 'active',
            ],
            [
                'ho_ten'        => 'Quách Ngọc Ngoan',
                'email'         => 'user20@gmail.com',
                'so_dien_thoai' => '0900000022',
                'vai_tro'       => 'user',
                'avatar_url'    => 'avatars/user20.jpg',
                'bio'           => 'Khách hàng 20',
                'trang_thai'    => 'active',
            ],
        ];

        foreach ($usersData as $u) {
            $user = User::create([
                'ho_ten'        => $u['ho_ten'],
                'email'         => $u['email'],
                'so_dien_thoai' => $u['so_dien_thoai'],
                'password'      => Hash::make('123456'),
                'vai_tro'       => $u['vai_tro'],
                'avatar_url'    => $u['avatar_url'],
                'bio'           => $u['bio'],
                'trang_thai'    => $u['trang_thai'],
            ]);
            $user->ma_nguoi_dung = $user->_id;
            $user->save();
        }
    }
}
