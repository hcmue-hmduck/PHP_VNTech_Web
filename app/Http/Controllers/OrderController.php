<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;

class OrderController extends Controller
{
    public function viewAdminOrder() {
        $orders = Order::latest()->paginate(10);
        return view('adminUI.ordersAdmin', compact('orders'));
    }

    public function viewAdminOrderDetail(Request $request) {
        $order = Order::where('ma_don_hang', $request->ma_don_hang)->first();
        $orderItems = OrderItem::where('ma_don_hang', $request->ma_don_hang)->with('variant.product')->get();
        return view('adminUI.orderDetailsAdmin', compact('order', 'orderItems'));
    }

    public function updateAdminOrderStatus(Request $request) {
        $order = Order::where('ma_don_hang', $request->ma_don_hang)->first();
        if ($order) {
            $order->trang_thai = $request->trang_thai;
            $order->save();
            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy đơn hàng!');
    }
    public function storeCreateOrder(Request $request) {
        $data = $request->validate([
            'ma_nguoi_dung' => 'required|string',
            'ho_ten_nguoi_nhan' => 'required|string',
            'so_dien_thoai_nhan' => 'required|string',
            'dia_chi_giao_hang' => 'required|string',
            'ghi_chu' => 'string',
            'ma_voucher' => 'string',
            'tong_tien_hang' => 'required|numeric',
            'phi_van_chuyen' => 'required|numeric',
            'gia_tri_giam_voucher' => 'numeric',
            'tong_thanh_toan' => 'required|string',
            'phuong_thuc_thanh_toan' => 'required|string',
            'trang_thai' => 'required|string',
            'cart_items' => 'required|json',
        ]);

        // Parse cart_items JSON
        $cartItems = json_decode($data['cart_items'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['cart_items' => 'Dữ liệu giỏ hàng không hợp lệ']);
        }

        // Create Order
        $order = Order::create($data);
        $order->ma_don_hang = $order->_id;
        $order->save();

        // Create OrderItems for each cart item
        foreach ($cartItems as $item) {
            $orderItems = OrderItem::create([
                'ma_don_hang' => $order->ma_don_hang,
                'ma_bien_the' => $item['ma_bien_the'] ?? null,
                'ma_san_pham' => $item['ma_san_pham'] ?? $item['id'] ?? null,
                'ten_san_pham' => $item['ten_san_pham'] ?? $item['name'] ?? null,
                'gia_ban' => $item['gia_ban'] ?? $item['price'] ?? 0,
                'so_luong' => $item['so_luong'] ?? $item['quantity'] ?? 1,
                'link_anh_dai_dien' => $item['link_anh_dai_dien'] ?? $item['image'] ?? null,
            ]);
            $orderItems->ma_chi_tiet_don_hang = $orderItems->_id;
            $orderItems->save();
        }

        return redirect()->route('viewOrderDetail', ['user_id' => $order->ma_nguoi_dung, 'ma_don_hang' => $order->ma_don_hang])->with('success', 'Tạo đơn hàng thành công!');
    }

    public function viewOrderDetail(Request $request) {
        $order = Order::where('ma_don_hang', $request->ma_don_hang)->first();
        $orderItems = OrderItem::where('ma_don_hang', $request->ma_don_hang)->with('variant.product')->get();
        return view('homeUI.orderDetail', compact('order', 'orderItems'));
    }

    public function viewOrder(Request $request) {
        $orders = Order::where('ma_nguoi_dung', $request->user_id)->get();
        return view('homeUI.orderDetail', compact('orders'));
    }
}
