<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function viewPayment(Request $request)
    {
        $cartItems = session('cartItems', []);
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
