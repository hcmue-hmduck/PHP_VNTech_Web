<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller {
    public function viewCart(string $user_id) {
        $cart = Cart::where('ma_nguoi_dung', $user_id)->firstOrFail();
        $cartItems = CartItem::with('variant.product')->where('ma_gio_hang', $cart->_id)->get(); 
        
        return view('homeUI.cart', compact('cartItems', 'cart'));
    }

    public function updateQuantity(Request $request) {
    // Tìm và cập nhật số lượng trong MongoDB
        CartItem::where('_id', $request->id)->update(['so_luong' => (int)$request->quantity]);
        return response()->json(['status' => 'updated']);
    }

    public function removeItem(Request $request) {
        // Xóa sản phẩm khỏi giỏ hàng trong MongoDB
        CartItem::where('_id', $request->id)->delete();
        return response()->json(['status' => 'removed']);
    }

}

?>