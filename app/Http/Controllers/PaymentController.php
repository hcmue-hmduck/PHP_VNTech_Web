<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\UserAddress;
use App\Models\Voucher;
use App\Models\User;
use App\Mail\OrderNotificationMail;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function viewPayment($ma_bien_the = null)
    {
        $voucher = Voucher::active()->get();
        $user_id = Auth::id();
        $user_address = UserAddress::where('ma_nguoi_dung', $user_id)->get();
        $cartItems = session('cartItems', []);
        if ($ma_bien_the) {
            $variant = ProductVariant::with(['activeFlashSaleItem.campaign'])->where('ma_bien_the', $ma_bien_the)->first();
            if ($variant) {
                $cartItems = [
                    [
                        'ma_san_pham' => $variant->ma_san_pham,
                        'ma_bien_the' => $variant->ma_bien_the,
                        'ma_flash_sales' => $variant?->flash_sale_info->ma_flash_sales ?? '',
                        'ten_bien_the' => $variant->ten_bien_the,
                        'gia_ban' => $variant->flash_sale_info ? $variant->flash_sale_info->gia_flash_sale : $variant->gia_ban,
                        'so_luong' => 1,
                        'link_anh_dai_dien' => $variant->link_anh_bien_the ?: ($variant->product->link_anh_dai_dien ?? '')
                    ]
                ];
            }
        }

        return view('homeUI.pay', compact('user_address', 'cartItems', 'voucher'));
    }

    public function preparePayment(Request $request)
    {
        $request->validate([
            'cart_json' => 'required|string',
        ]);

        $cartItems = json_decode($request->input('cart_json'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['cart_json' => 'Dữ liệu giỏ hàng không hợp lệ']);
        }

        session(['cartItems' => $cartItems]);

        return redirect()->route('payment.view');
    }

    public function createMomoPayment(Request $request)
    {
        $maDonHang = $request->route('ma_don_hang');
        $order = Order::where('ma_don_hang', $maDonHang)->firstOrFail();
        if (!$order) return redirect()->route('viewHome');

        $partnerCode = config('services.momo.partner_code');
        $requestType = 'captureWallet';
        $ipnUrl = config('services.momo.ipn_url');
        $returnUrl = config('services.momo.return_url');
        $orderId = $maDonHang . '_' . now()->format('YmdHisv');
        $amount = $order->tong_thanh_toan;
        $orderInfo = 'Thanh toán đơn hàng';
        $requestId = (string) Str::uuid();
        $extraData = base64_encode(json_encode([
            'ma_don_hang' => $maDonHang,
        ], JSON_UNESCAPED_UNICODE));
        $lang = 'vi';

        $endpoint = config('services.momo.endpoint');
        $accessKey = config('services.momo.access_key');
        $secretKey = config('services.momo.secret_key');

        $rawSignature = "accessKey={$accessKey}"
            . "&amount={$amount}"
            . "&extraData={$extraData}"
            . "&ipnUrl={$ipnUrl}"
            . "&orderId={$orderId}"
            . "&orderInfo={$orderInfo}"
            . "&partnerCode={$partnerCode}"
            . "&redirectUrl={$returnUrl}"
            . "&requestId={$requestId}"
            . "&requestType={$requestType}";
        $signature = hash_hmac('sha256', $rawSignature, (string) $secretKey);

        $payload = [
            "partnerCode" => $partnerCode,
            "requestType" => $requestType,
            "ipnUrl" => $ipnUrl,
            "redirectUrl" => $returnUrl,
            "orderId" => $orderId,
            "amount" => $amount,
            "orderInfo" => $orderInfo,
            "requestId" => $requestId,
            "extraData" => $extraData,
            "signature" => $signature,
            "lang" => $lang,
        ];

        $response = Http::post($endpoint, $payload);
        $result = $response->json();

        if (isset($result['payUrl'])) {
            return redirect()->away($result['payUrl']);
        }

        dd($result);

        return redirect()->route('order_detail.view', ['ma_don_hang' => $maDonHang])
            ->with('error', $result['message'] ?? 'Không thể tạo thanh toán MoMo, vui lòng thử lại.');
    }

    // GET momo/return
    public function momoReturn(Request $request)
    {
        $resultCode = $request->query('resultCode');
        $orderId = $request->query('orderId');
        $internalOrderId = $this->resolveInternalOrderId($request->query('extraData'), $orderId);

        if ($resultCode == 0 || $resultCode == 9000) {
            return redirect()->route('order_detail.view', ['ma_don_hang' => $internalOrderId]);
        }

        $message = $request->query('message');

        return view('homeUI.paymentFailed', [
            'message' => $message,
            'orderId' => $internalOrderId,
            'resultCode' => $resultCode,
        ]);
    }

    // POST momo/ipn
    public function momoIpn(Request $request)
    {
        $resultCode = $request->input('resultCode');
        $orderId = $request->input('orderId');
        $internalOrderId = $this->resolveInternalOrderId($request->input('extraData'), $orderId);

        if (($resultCode == 0 || $resultCode == 9000)) {
            $order = Order::where('ma_don_hang', $internalOrderId)->first();
            if ($order) {
                $order->update([
                    'trang_thai' => OrderStatus::WAITING_PICKUP->value,
                ]);

                $customer = User::where('ma_nguoi_dung', $order->ma_nguoi_dung)->first();
                if ($customer && $customer->email) {
                    Mail::to($customer->email)->send(new OrderNotificationMail($order));
                }
            }
        }

        return response()->json('');
    }

    private function resolveInternalOrderId(?string $extraData, ?string $externalOrderId): ?string
    {
        if (!empty($extraData)) {
            $decodedBase64 = base64_decode($extraData, true);
            if ($decodedBase64 !== false) {
                $decodedJson = json_decode($decodedBase64, true);
                if (json_last_error() === JSON_ERROR_NONE && !empty($decodedJson['ma_don_hang'])) {
                    return $decodedJson['ma_don_hang'];
                }
            }
        }

        if (!empty($externalOrderId)) {
            return explode('_', $externalOrderId)[0];
        }

        return null;
    }
}
