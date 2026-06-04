<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function viewVoucherAdmin()
    {
        $voucher = Voucher::where('trang_thai', '!=', 'deleted')->latest()->get();
        return view('adminUI.voucherAdmin', compact('voucher'));
    }

    public function viewCreateVoucherAdmin()
    {
        return view('adminUI.formVoucherAdmin');
    }

    public function storeCreateVoucherAdmin(Request $request)
    {
        $data = $request->validate([
            'ten_voucher' => 'required|unique:vouchers,ten_voucher',
            'hinh_thuc_giam' => 'required',
            'gia_tri_giam' => 'required|numeric',
            'tong_luot_dung' => 'required|integer',
            'bat_dau' => 'required|date',
            'ket_thuc' => 'required|date|after_or_equal:bat_dau',
            'mo_ta' => 'nullable|string',
            'muc_giam_toi_da' => 'nullable|numeric',
            'don_hang_toi_thieu' => 'nullable|numeric',
            'trang_thai' => 'required',
        ]);
        $data['da_dung'] = 0;

        $data['muc_giam_toi_da'] = $data['muc_giam_toi_da'] ?? 0;
        $data['don_hang_toi_thieu'] = $data['don_hang_toi_thieu'] ?? 0;

        $voucher = Voucher::create($data);
        $voucher->ma_voucher = $voucher->_id;
        $voucher->save();

        return redirect()->route('admin.voucher.view')->with('success', 'Tạo voucher thành công!');
    }

    public function viewEditVoucherAdmin(Voucher $voucher)
    {
        return view('adminUI.formVoucherAdmin', compact('voucher'));
    }

    public function updateEditVoucherAdmin(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'ten_voucher' => 'required',
            'hinh_thuc_giam' => 'required',
            'gia_tri_giam' => 'required|numeric',
            'tong_luot_dung' => 'required|integer',
            'bat_dau' => 'required|date',
            'ket_thuc' => 'required|date|after_or_equal:bat_dau',
            'mo_ta' => 'nullable|string',
            'muc_giam_toi_da' => 'nullable|numeric',
            'don_hang_toi_thieu' => 'nullable|numeric',
            'trang_thai' => 'required',
        ]);

        $voucher->update($data);

        return redirect()->route('admin.voucher.view')->with('success', 'Cập nhật voucher thành công!');
    }

    public function deleteVoucherAdmin(Voucher $voucher)
    {
        $voucher->trang_thai = 'deleted';
        $voucher->save();
        return redirect()->route('admin.voucher.view')->with('success', 'Xoá voucher thành công!');
    }
}
