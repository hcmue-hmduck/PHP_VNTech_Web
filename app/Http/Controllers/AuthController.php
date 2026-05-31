<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            if (Auth::user()->trang_thai !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa hoặc chưa kích hoạt.'
                ])->withInput($request->only('email'));
            }
            $request->session()->regenerate();
            return redirect()->intended('/')->with('clear_chatbot', true);
        }
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng'
        ])->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'vai_tro' => 'user',
        ]);

        $user->ma_nguoi_dung = $user->_id;
        $user->save();

        Auth::login($user);
        return redirect('/')->with('clear_chatbot', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('clear_chatbot', true);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $email = $googleUser->getEmail();
        $name = $googleUser->getName();
        $avatar = $googleUser->getAvatar();

        $foundUser = User::where('email', $email)->first();

        if (!$foundUser) {
            $foundUser = User::create([
                'ho_ten' => $name,
                'email' => $email,
                'vai_tro' => 'user',
                'trang_thai' => 'active',
            ]);
            
            $foundUser->ma_nguoi_dung = $foundUser->_id;
            $foundUser->save();
        }

        Auth::login($foundUser);
        return redirect('/')->with('clear_chatbot', true);
    }
}
