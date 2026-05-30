<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;

class UserController extends Controller
{
    public function viewUserInfo() {
        $user = User::where('ma_nguoi_dung', Auth::user()->id)->firstOrFail();
        $user_address = UserAddress::where('ma_nguoi_dung', $user->ma_nguoi_dung)->get();
        $orders = Order::where('ma_nguoi_dung', $user->ma_nguoi_dung)->get();
        return view('homeUI.user', compact('user', 'user_address', 'orders'));
    }
}
