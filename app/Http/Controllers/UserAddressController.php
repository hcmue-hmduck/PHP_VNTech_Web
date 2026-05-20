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
        ]);

        $data['ma_nguoi_dung'] = Auth::id();
        $data['is_default'] = true;

        UserAddress::where('ma_nguoi_dung', Auth::id())->update(['is_default' => false]);

        UserAddress::create($data);

        return redirect()->back()->with('success', 'Lưu địa chỉ thành công!');
    }
}
