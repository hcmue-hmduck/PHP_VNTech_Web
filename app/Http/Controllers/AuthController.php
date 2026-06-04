<?php

namespace App\Http\Controllers;

use App\Mail\sendNewPasswordMail;
use App\Mail\SendOtpMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgotPassword');
    }

    public function showOtpForm(string $flow)
    {
        return view('auth.verifyOtp', [
            'email' => session($this->getOtpSessionKey($flow)),
            'sendAction' => route('otp.verify', ['flow' => $flow]),
            'resendAction' => route('otp.resend', ['flow' => $flow]),
        ]);
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

    public function sendOtp(Request $request, string $flow)
    {
        $data = null;

        if ($flow === 'register') {
            $data =  $request->validate([
                'ho_ten' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed'
            ]);
        } 
        else if ($flow === 'forgot-password') {
            $data =  $request->validate([
                'email' => 'required|email',
            ]);

            $userFound = User::where('email', $data['email'])->first();
            if(!$userFound) {
                return back()->withErrors('Địa chỉ Email chưa được đăng ký');
            }
        }

        $this->otpService->send(
            $flow,
            $data['email'],
            fn(string $otp) => Mail::to($data['email'])->send(new SendOtpMail($data['email'], $otp)),
            $data,
        );
        session()->put($this->getOtpSessionKey($flow), $data['email']);

        return redirect()->route('otp.show', ['flow' => $flow]);
    }

    public function resendOtp(string $flow)
    {
        $email = session($this->getOtpSessionKey($flow));

        if ($email) {
            $registration = $this->otpService->getPayload($flow, $email);
            if (!$registration) {
                return redirect()->route('otp.show', ['flow' => $flow])->withErrors('Phiên OTP đã hết hạn, vui lòng thực hiện lại');
            }

            $this->otpService->send(
                $flow,
                $email,
                fn(string $otp) => Mail::to($email)->send(new SendOtpMail($email, $otp)),
                $registration,
            );
            return redirect()->route('otp.show', ['flow' => $flow])->with('success', 'Mã OTP đã được gửi lại');
        }

        return redirect()->route('otp.show', ['flow' => $flow])->withErrors('Phiên OTP đã hết hạn, vui lòng thực hiện lại');
    }

    public function verifyOtp(Request $request, string $flow)
    {
        $data = $request->validate([
            'otp' => 'required|string'
        ]);

        $email = session($this->getOtpSessionKey($flow));
        if (!$email) {
            return redirect()->route('otp.show', ['flow' => $flow])->withErrors('Phiên OTP đã hết hạn, vui lòng thực hiện lại');
        }

        $payload = $this->otpService->getPayload($flow, $email);
        $otpIsValid = $this->otpService->verify($flow, $email, $data['otp']);

        if ($payload && $otpIsValid) {
            $response = $this->handleOtpSuccess($flow, $payload);

            $this->otpService->forget($flow, $email);
            $request->session()->forget($this->getOtpSessionKey($flow));

            return $response;
        }

        return redirect()->route('otp.show', ['flow' => $flow])->withErrors('Mã OTP không chính xác');
    }

    private function getOtpSessionKey(string $flow): string
    {
        return 'otp_email_' . $flow;
    }

    private function handleOtpSuccess(string $flow, array $payload)
    {
        return match ($flow) {
            'register' => $this->completeRegister($payload),
            'forgot-password' => $this->resetPassword($payload['email']),
            default => abort(404),
        };
    }

    private function completeRegister(array $registration)
    {
        $user = User::create([
            'ho_ten' => $registration['ho_ten'],
            'email' => $registration['email'],
            'password' => Hash::make($registration['password']),
            'vai_tro' => 'user',
            'trang_thai' => 'active'
        ]);

        $user->ma_nguoi_dung = $user->_id;
        $user->save();

        Auth::login($user);

        return redirect('/')->with('clear_chatbot', true);
    }

    private function resetPassword(string $email) {
        $user = User::where('email', $email)->first();
        if($user) {
            $newPassword = str()->random(16);
            $user->password = Hash::make($newPassword);
            $user->save();
            Mail::to($email)->send(new sendNewPasswordMail($email, $newPassword));
            Auth::login($user);
            return redirect('/')->with('clear_chatbot', true);
        }
        return false;
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
                'avatar_url' => $avatar,
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
