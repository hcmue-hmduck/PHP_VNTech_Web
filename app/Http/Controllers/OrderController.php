<?php

namespace App\Http\Controllers;

use App\Mail\OrderNotificationMail;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Voucher;
use App\Models\FlashSaleItem;
use App\Models\User;
use App\Models\Notification;
use App\Models\Review;
use App\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function viewAdminOrder()
    {
        $orders = Order::latest()->paginate(10);
        return view('adminUI.ordersAdmin', compact('orders'));
    }

    public function viewAdminOrderDetail(Request $request)
    {
        $order = Order::where('ma_don_hang', $request->ma_don_hang)->first();
        $orderItems = OrderItem::where('ma_don_hang', $request->ma_don_hang)->with('variant.product')->get();
        return view('adminUI.orderDetailsAdmin', compact('order', 'orderItems'));
    }

    public function printInvoice(Request $request)
    {
        $order = Order::where('ma_don_hang', $request->ma_don_hang)->firstOrFail();
        $orderItems = OrderItem::where('ma_don_hang', $request->ma_don_hang)->with('variant.product')->get();
        return view('printInvoice', compact('order', 'orderItems'));
    }

    public function updateAdminOrderStatus(Request $request)
    {
        $order = Order::where('ma_don_hang', $request->ma_don_hang)->first();
        if ($order) {
            $order->trang_thai = $request->trang_thai;
            $order->save();

            $customer = User::where('ma_nguoi_dung', $order->ma_nguoi_dung)->first();
            if ($customer && $customer->email) {
                Mail::to($customer->email)->send(new OrderNotificationMail($order));
            }

            $trangThaiText = '';
            switch ($order->trang_thai) {
                case OrderStatus::PENDING_PAYMENT->value:
                    $trangThaiText = 'đang chờ thanh toán';
                    break;
                case OrderStatus::PENDING_CONFIRMATION->value:
                    $trangThaiText = 'đang chờ xác nhận';
                    break;
                case OrderStatus::WAITING_PICKUP->value:
                    $trangThaiText = 'đã được xác nhận và đang được đóng gói';
                    break;
                case OrderStatus::WAITING_DELIVERY->value:
                    $trangThaiText = 'đang được giao đến bạn';
                    break;
                case OrderStatus::DELIVERED->value:
                    $trangThaiText = 'đã được giao thành công';
                    break;
                case OrderStatus::CANCELLED->value:
                    $trangThaiText = 'đã bị hủy';
                    break;
                default:
                    $trangThaiText = 'đã cập nhật trạng thái mới';
            }

            $noti = Notification::create([
                'ma_nguoi_dung' => $order->ma_nguoi_dung,
                'tieu_de' => 'Cập nhật trạng thái đơn hàng',
                'noi_dung' => 'Đơn hàng #' . $order->ma_don_hang . ' của bạn ' . $trangThaiText . '.',
                'loai' => 'order',
                'duong_dan' => '/orders/' . $order->ma_don_hang,
                'da_doc'    => false,
            ]);
            $noti->ma_thong_bao = $noti->_id;
            $noti->save();

            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy đơn hàng!');
    }

    public function storeCreateOrder(Request $request)
    {
        $data = $request->validate([
            'ma_nguoi_dung' => 'required|string',
            'ho_ten_nguoi_nhan' => 'required|string',
            'so_dien_thoai_nhan' => 'required|string',
            'dia_chi_giao_hang' => 'required|string',
            'ghi_chu' => 'nullable|string',
            'ma_voucher' => 'nullable|string',
            'tong_tien_hang' => 'required|numeric',
            'phi_van_chuyen' => 'required|numeric',
            'gia_tri_giam_voucher' => 'nullable|numeric',
            'tong_thanh_toan' => 'required|numeric',
            'phuong_thuc_thanh_toan' => 'required|in:momo,cod',
            'cart_items' => 'required|json',
        ]);

        $paymentMethod = $data['phuong_thuc_thanh_toan'];
        $data['trang_thai'] = $paymentMethod === 'cod'
            ? OrderStatus::PENDING_CONFIRMATION->value
            : OrderStatus::PENDING_PAYMENT->value;

        // Parse cart_items JSON
        $cartItems = json_decode($data['cart_items'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['cart_items' => 'Dữ liệu giỏ hàng không hợp lệ']);
        }

        // Create Order
        $order = Order::create($data);
        $order->ma_don_hang = $order->_id;
        $order->save();

        // Tạo thông báo đặt hàng cho người dùng
        $noti = Notification::create([
            'ma_nguoi_dung' => $order->ma_nguoi_dung,
            'tieu_de' => 'Đặt hàng thành công',
            'noi_dung' => 'Đơn hàng #' . $order->ma_don_hang . ' đã được tạo thành công. Vui lòng theo dõi trạng thái đơn hàng của bạn.',
            'loai' => 'order',
            'duong_dan' => '/orders/' . $order->ma_don_hang,
            'da_doc'    => false,
        ]);
        $noti->ma_thong_bao = $noti->_id;
        $noti->save();

        // Create OrderItems for each cart item
        foreach ($cartItems as $item) {
            $orderItems = OrderItem::create([
                'ma_don_hang' => $order->ma_don_hang,
                'ma_bien_the' => $item['ma_bien_the'],
                'ma_flash_sales' => $item['ma_flash_sales'] ?? '',
                'ten_bien_the' => $item['ten_bien_the'],
                'gia_ban' => $item['gia_ban'],
                'so_luong' => $item['so_luong'],
                'link_anh_dai_dien' => $item['link_anh_dai_dien'],
                'thanh_tien' => $item['gia_ban'] * $item['so_luong']
            ]);
            $orderItems->ma_chi_tiet_don_hang = $orderItems->_id;
            $orderItems->save();

            ProductVariant::where('ma_bien_the', $item['ma_bien_the'])->decrement('so_luong_ton_kho', $item['so_luong']);

            if (!empty($item['ma_flash_sales'])) {
                FlashSaleItem::where('ma_flash_sales', $item['ma_flash_sales'])
                    ->where('ma_bien_the', $item['ma_bien_the'])
                    ->increment('so_luong_da_ban',  $item['so_luong']);
            }
        }

        // Clear only the purchased items from user's cart
        $cart = Cart::where('ma_nguoi_dung', Auth::id())->first();
        if ($cart) {
            $purchasedVariantIds = collect($cartItems)->pluck('ma_bien_the')->filter()->toArray();
            CartItem::where('ma_gio_hang', $cart->_id)
                ->whereIn('ma_bien_the', $purchasedVariantIds)
                ->delete();
        }

        $request->session()->forget('cartItems');

        if ($request->filled('ma_voucher')) {
            Voucher::where('ma_voucher', $request->ma_voucher)->increment('da_dung');
        }

        if ($paymentMethod === 'momo') {
            $totalBill = $data['tong_thanh_toan'];

            if ($totalBill != 0) {
                return redirect()->route('momo.create', ['ma_don_hang' => $order->ma_don_hang]);
            }

            $order['trang_thai'] = OrderStatus::WAITING_PICKUP;
            $order->save();
        }

        $customer = User::where('ma_nguoi_dung', $order->ma_nguoi_dung)->first();
        if ($customer && $customer->email) {
            Mail::to($customer->email)->send(new OrderNotificationMail($order));
        }

        return redirect()->route('order_detail.view', ['ma_don_hang' => $order->ma_don_hang])->with('success', 'Tạo đơn hàng thành công!');
    }

    public function viewOrderDetail(Request $request)
    {
        $userId = Auth::user()->id;
        $order = Order::where('ma_don_hang', $request->ma_don_hang)
            ->where('ma_nguoi_dung', $userId)
            ->firstOrFail();
        $orders = Order::where('ma_nguoi_dung', $userId)->latest()->get();
        $this->attachReviewActions($orders, $userId);
        $orderItems = OrderItem::where('ma_don_hang', $request->ma_don_hang)->with('variant.product')->get();
        return view('homeUI.orderDetail', compact('order', 'orders', 'orderItems'));
    }

    public function viewOrder()
    {
        $userId = Auth::user()->id;
        $orders = Order::where('ma_nguoi_dung', $userId)->latest()->get();
        $this->attachReviewActions($orders, $userId);
        return view('homeUI.orderDetail', compact('orders'));
    }

    private function attachReviewActions($orders, string $userId): void
    {
        foreach ($orders as $order) {
            $order->review_is_expired = $this->isReviewExpired($order);
            $order->review_action = $this->resolveReviewAction($order, $userId);
        }
    }

    private function resolveReviewAction(Order $order, string $userId): string
    {
        if ($order->trang_thai !== OrderStatus::DELIVERED->value) {
            return 'none';
        }

        $orderItemIds = OrderItem::where('ma_don_hang', $order->ma_don_hang)
            ->pluck('ma_chi_tiet_don_hang')
            ->filter()
            ->values();

        if ($orderItemIds->isEmpty()) {
            return 'none';
        }

        $reviewedItemCount = Review::where('ma_don_hang', $order->ma_don_hang)
            ->where('ma_nguoi_dung', $userId)
            ->where('trang_thai', 'active')
            ->whereIn('ma_chi_tiet_don_hang', $orderItemIds->all())
            ->pluck('ma_chi_tiet_don_hang')
            ->filter()
            ->unique()
            ->count();

        if ($reviewedItemCount >= $orderItemIds->count()) {
            return 'view';
        }

        $canCreateReview = !$this->isReviewExpired($order);

        if ($canCreateReview) {
            return 'create';
        }

        return $reviewedItemCount > 0 ? 'view' : 'none';
    }

    private function isReviewExpired(Order $order): bool
    {
        if ($order->trang_thai !== OrderStatus::DELIVERED->value) {
            return false;
        }

        return !($order->created_at?->greaterThanOrEqualTo(now()->subDays(30)) ?? false);
    }
}
