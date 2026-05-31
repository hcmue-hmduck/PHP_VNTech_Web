<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\Routing\Annotation\Route;

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

    public function showVerifyOtpForm()
    {
        return view('auth.verifyOtp');
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
        $data =  $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $email = $data['email'];

        Cache::put('registration_' . $email, $data, 60 * 5);
        $this->generateOtp($email);
        session(['register_email' => $email]);

        return redirect()->route('show.verify.otp');
    }

    public function resendOtp()
    {
        $email = session('register_email');
        if ($email) {
            $this->generateOtp($email);
            return redirect()->route('show.verify.otp')->with('success', 'Mã OTP đã được gửi lại');
        }
        return redirect()->route('show.verify.otp')->withErrors('Phiên đăng ký đã hết hạn, vui lòng đăng ký lại');
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp' => 'required|string'
        ]);

        $registerEmail = session('register_email');
        $registrationKey = 'registration_' . $registerEmail;
        $otpKey = 'otp_' . $registerEmail;

        $registration = Cache::get($registrationKey);
        $otp1 = Cache::get($otpKey);
        $otp2 = $data['otp'];

        if ($otp1 === $otp2) {
            $user = User::create([
                'ho_ten' => $registration['ho_ten'],
                'email' => $registration['email'],
                'password' => Hash::make($registration['password']),
                'vai_tro' => 'user',
            ]);

            $user->ma_nguoi_dung = $user->_id;
            $user->save();

            Auth::login($user);

            Cache::forget($registrationKey);
            Cache::forget($otpKey);
            $request->session()->forget('register_email');

            return redirect('/')->with('clear_chatbot', true);
        }

        return redirect()->route('show.verify.otp')->withErrors('Mã OTP không chính xác');
    }

    private function generateOtp(string $email)
    {
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $email, $otp, 60 * 5);
        Mail::to($email)->send(new SendOtpMail($email, $otp));
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
