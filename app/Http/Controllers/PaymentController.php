<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\UserAddress;

class PaymentController extends Controller
{
    public function viewPayment($ma_bien_the = null)
    {
        $user_id = Auth::id();
        $user_address = UserAddress::where('ma_nguoi_dung', $user_id)->get();
        $cartItems = session('cartItems', []);
        if ($ma_bien_the) {
            $variant = ProductVariant::where('ma_bien_the', $ma_bien_the)->first();
            if ($variant) {
                $cartItems = [
                    [
                        'ma_san_pham' => $variant->ma_san_pham,
                        'ma_bien_the' => $variant->ma_bien_the,
                        'ten_bien_the' => $variant->ten_bien_the,
                        'gia_ban' => $variant->gia_ban,
                        'so_luong' => 1,
                        'link_anh_dai_dien' => $variant->link_anh_bien_the ?: ($variant->product->link_anh_dai_dien ?? '')
                    ]
                ];
            }
        }
        return view('homeUI.pay', compact('user_address', 'cartItems'));
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

        return redirect()->route('viewPayment');
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
        $orderId = $maDonHang;
        $amount = $order->tong_tien_hang;
        $orderInfo = 'Thanh toán đơn hàng ' . $orderId;
        $requestId = $orderId . '_' . time();
        $extraData = '';
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
        $signature = hash_hmac('sha256', $rawSignature, $secretKey);

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
    }

    // GET momo/return
    public function momoReturn(Request $request)
    {
        $resultCode = $request->query('resultCode');
        $orderId = $request->query('orderId');

        if ($resultCode == 0 || $resultCode == 9000) {
            return redirect()->route('viewOrder', ['ma_don_hang' => $orderId]);
        }

        $message = $request->query('message');

        return view('homeUI.paymentFailed', compact('message', 'orderId', 'resultCode'));
    }

    // POST momo/ipn
    public function momoIpn(Request $request)
    {
        
        $resultCode = $request->input('resultCode');
        $orderId = $request->input('orderId');

        if ($resultCode == 0 || $resultCode == 9000) {
            Order::where('ma_don_hang', $orderId)->update([
                'trang_thai' => OrderStatus::WAITING_PICKUP->value,
            ]);
        }

        return response()->json('');
    }
    
}
