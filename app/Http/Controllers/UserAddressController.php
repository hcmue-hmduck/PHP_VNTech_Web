<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;

class UserAddressController extends Controller
{
    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string',
            'so_dien_thoai' => 'required|string',
            'dia_chi_chi_tiet' => 'required|string',
            'tinh_thanh' => 'required|string',
            'quan_huyen' => 'nullable|string',
            'phuong_xa' => 'required|string',
            'is_default' => 'nullable|boolean'
        ]);

        $data['ma_nguoi_dung'] = Auth::id();
        
        $isDefault = $request->boolean('is_default');
        
        // Nếu đây là địa chỉ đầu tiên, bắt buộc đặt làm mặc định
        $hasAddress = UserAddress::where('ma_nguoi_dung', Auth::id())->exists();
        if (!$hasAddress) {
            $isDefault = true;
        }

        if ($isDefault) {
            UserAddress::where('ma_nguoi_dung', Auth::id())->update(['is_default' => false]);
        }

        $data['is_default'] = $isDefault;

        $user_address = UserAddress::create($data);
        $user_address->ma_dia_chi = $user_address->_id;
        $user_address->save();
        return redirect()->back()->with('success', 'Lưu địa chỉ thành công!');
    }

    public function updateAddress(Request $request, UserAddress $user_address)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string',
            'so_dien_thoai' => 'required|string',
            'dia_chi_chi_tiet' => 'required|string',
            'tinh_thanh' => 'required|string',
            'quan_huyen' => 'nullable|string',
            'phuong_xa' => 'required|string',
            'is_default' => 'nullable|boolean'
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            UserAddress::where('ma_nguoi_dung', Auth::id())->update(['is_default' => false]);
        }
        else {
            if ($user_address->is_default) {
                $anotherAddress = UserAddress::where('ma_nguoi_dung', Auth::id())
                    ->where('ma_dia_chi', '!=', $user_address->ma_dia_chi)
                    ->latest()
                    ->first();

                if ($anotherAddress) {
                    $anotherAddress->update(['is_default' => true]);
                } else {
                    $isDefault = true;
                }
            }
        }

        $data['is_default'] = $isDefault;
        $user_address->update($data);

        return redirect()->back()->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function destroyAddress(UserAddress $user_address)
    {
        if ($user_address->ma_nguoi_dung != Auth::id()) {
            return redirect()->back()->with('error', 'Không có quyền xoá địa chỉ này!');
        }

        $wasDefault = $user_address->is_default;
        $user_address->delete();

        if ($wasDefault) {
            $otherAddress = UserAddress::where('ma_nguoi_dung', Auth::id())->first();
            if ($otherAddress) {
                $otherAddress->update(['is_default' => true]);
            }
        }

        // Nếu xoá địa chỉ đang chọn trong session, thì clear session
        if (session('selected_address_id') == $user_address->ma_dia_chi) {
            session()->forget('selected_address_id');
        }

        return redirect()->back()->with('success', 'Xoá địa chỉ thành công!');
    }

    public function selectAddressGet(string $ma_dia_chi)
    {
        $exists = UserAddress::where('ma_nguoi_dung', Auth::id())
            ->where('ma_dia_chi', $ma_dia_chi)
            ->exists();

        if ($exists) {
            session(['selected_address_id' => $ma_dia_chi]);
        }

        return redirect()->back();
    }
}

