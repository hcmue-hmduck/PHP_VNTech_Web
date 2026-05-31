<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserController extends Controller
{
    public function viewUserInfo() {
        $user = User::where('ma_nguoi_dung', Auth::user()->id)->firstOrFail();
        $user_address = UserAddress::where('ma_nguoi_dung', $user->ma_nguoi_dung)->get();
        return view('homeUI.user', compact('user', 'user_address'));
    }

    public function editUserInfo(Request $request, User $user) {
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác.']);
            }
        }

        $data = $request->validate([
            'ho_ten'        => 'nullable|string',
            'email'         => 'nullable|string',
            'so_dien_thoai' => 'nullable|string',
            'password'      => 'nullable|string',
            'avatar_url'    => 'nullable|image|max:5120',
            'bio'           => 'nullable|string',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $filePath = $user->ho_ten . ' - ' . $user->ma_nguoi_dung;

        if ($request->hasFile('avatar_url')) {
            $file = $request->file('avatar_url');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/avatars/{$filePath}"
                ]);
                $data['avatar_url'] = $upload['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Cập nhật thông tin người dùng thành công');
    }
}
