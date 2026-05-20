<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductVariant;

class PaymentController extends Controller
{
    public function viewPayment($ma_bien_the = null)
    {
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
        return view('homeUI.pay', compact('cartItems'));
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
}
