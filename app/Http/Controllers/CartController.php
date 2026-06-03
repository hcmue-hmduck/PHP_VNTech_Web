<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {
    public function cartCount(string $ma_nguoi_dung) {
        $cart = Cart::where('ma_nguoi_dung', $ma_nguoi_dung)->first();
        if (!$cart) {
            return 0;
        }
        return CartItem::where('ma_gio_hang', $cart->ma_gio_hang)->count() ?? 0;
    }

    public function viewCart() {
        $user_id = Auth::user()->id;
        $cart = Cart::where('ma_nguoi_dung', $user_id)->first();
        
        if (!$cart) {
            $cartItems = collect();
        } else {
            $cartItems = CartItem::with(['variant.product', 'variant.activeFlashSaleItem.campaign'])
                ->where('ma_gio_hang', $cart->ma_gio_hang)
                ->get(); 
        }
        
        return view('homeUI.cart', compact('cartItems', 'cart'));
    }

    public function updateQuantity(Request $request) {
        CartItem::where('_id', $request->id)->update(['so_luong' => (int)$request->quantity]);
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    public function removeItem(Request $request) {
        CartItem::where('_id', $request->id)->delete();
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    public function addItem(Request $request) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $ma_bien_the = $request->input('ma_bien_the');

        $user_id = Auth::id();
        $card_id = Cart::where('ma_nguoi_dung', $user_id)->first();

        if (!$card_id) {
            $card_id = Cart::create([
                'ma_nguoi_dung' => $user_id,
            ]);
            $card_id->ma_gio_hang = $card_id->_id;
            $card_id->save();
        }

        $cart_item = CartItem::where('ma_gio_hang', $card_id->_id)->where('ma_bien_the', $ma_bien_the)->first();
        if ($cart_item) {
            $cart_item->so_luong += 1;
            $cart_item->save();
            return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
        }

        CartItem::create([
            'ma_gio_hang' => $card_id->_id,
            'ma_bien_the' => $ma_bien_the,
            'so_luong' => 1
        ]);
        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }
}