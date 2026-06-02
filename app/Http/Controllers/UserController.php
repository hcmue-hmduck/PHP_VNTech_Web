<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserAddress;
use App\Services\OtpService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    private const OTP_PURPOSE_CHANGE_EMAIL = 'change-email';

    public function __construct(private readonly OtpService $otpService) {}

    public function viewUserInfo()
    {
        $user = User::where('ma_nguoi_dung', Auth::user()->id)->firstOrFail();
        $user_address = UserAddress::where('ma_nguoi_dung', $user->ma_nguoi_dung)->get();
        return view('homeUI.user', compact('user', 'user_address'));
    }

    public function viewUsersAdmin()
    {
        $users = User::select('ma_nguoi_dung', 'ho_ten', 'email', 'avatar_url', 'trang_thai')->where('vai_tro', 'user')->latest()->get();
        return view('adminUI.userAdmin', compact('users'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $data = $request->validate([
            'trang_thai'    => 'nullable|string',
        ]);

        $user->update($data);
        return redirect()->back()->with('success', 'Cập nhật thông tin người dùng thành công');
    }

    public function editUserInfo(Request $request, User $user)
    {
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ], [
                'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            ]);

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác.']);
            }
        }

        $data = $request->validate([
            'ho_ten'        => 'nullable|string',
            'email'         => 'nullable|string',
            'so_dien_thoai' => 'nullable|string',
            'password'      => 'nullable|string',
            'avatar'        => 'nullable|image|max:5120',
            'bio'           => 'nullable|string',
        ]);

        console_log(['request' => $data]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $filePath = $user->ma_nguoi_dung;


        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            try {
                $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => "vntech/avatars/{$filePath}"
                ]);
                $data['avatar_url'] = $upload['secure_url'];
            } catch (\Exception $e) {
                console_log(['cloudinary' => $e->getMessage()]);
                return back()->withErrors(['cloudinary' => 'Lỗi upload: ' . $e->getMessage()]);
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Cập nhật thông tin người dùng thành công');
    }

    // POST
    public function requestEmailChange(Request $request)
    {
        $data = $request->validate(
            [
                'new_email' => 'required|email|unique:users,email'
            ],
            [
                'new_email.unique' => 'Địa chỉ email mới đã được sử dụng'
            ]
        );

        $purposePrefix = self::OTP_PURPOSE_CHANGE_EMAIL;

        $oldEmail = Auth::user()->email;
        $newEmail = $data['new_email'];

        $this->otpService->send(
            $purposePrefix,
            $oldEmail,
            fn(string $otp) => Mail::to($oldEmail)->send(new SendOtpMail($oldEmail, $otp)),
        );

        $this->otpService->send(
            $purposePrefix,
            $newEmail,
            fn(string $otp) => Mail::to($newEmail)->send(new SendOtpMail($newEmail, $otp)),
        );

        return redirect()->route('user.email.change.verify.show', ['new_email' => $newEmail]);
    }

    public function showVerifyChangeEmailOtp(Request $request)
    {
        $data = $request->validate(
            [
                'new_email' => 'required|email|unique:users,email'
            ],
            [
                'new_email.unique' => 'Địa chỉ email mới đã được sử dụng'
            ]
        );

        $oldEmail = Auth::user()->email;
        $newEmail = $data['new_email'];

        return view('homeUI.verifyChangeEmailOtp', [
            'oldEmail' => $oldEmail,
            'newEmail' => $newEmail,
        ]);
    }

    public function verifyChangeEmailOtp(Request $request)
    {
        $data = $request->validate([
            'new_email' => 'required|email',
            'old_email_otp' => 'required|string',
            'new_email_otp' => 'required|string',
        ]);

        $purposePrefix = self::OTP_PURPOSE_CHANGE_EMAIL;

        $oldEmail = Auth::user()->email;
        $newEmail = $data['new_email'];

        $oldEmailOtpValid = $this->otpService->verify(
            $purposePrefix,
            $oldEmail,
            $data['old_email_otp']
        );

        $newEmailOtpValid = $this->otpService->verify(
            $purposePrefix,
            $newEmail,
            $data['new_email_otp']
        );

        if ($oldEmailOtpValid && $newEmailOtpValid) {
            $user = User::where('email', $oldEmail)->first();
            if ($user) {
                $user->email = $newEmail;
                $user->save();

                $this->otpService->forget($purposePrefix, $oldEmail);
                $this->otpService->forget($purposePrefix, $newEmail);

                return redirect()->route('user.view', ['tab' => $purposePrefix])->with('success', 'Cập nhật địa chỉ email thành công');
            }
        }

        return back()->withErrors('Mã OTP không chính xác');
    }

    public function resendChangeEmailOtp(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        $purposePrefix = self::OTP_PURPOSE_CHANGE_EMAIL;
        $email = $data['email'];

        $this->otpService->send(
            $purposePrefix,
            $email,
            fn(string $otp) => Mail::to($email)->send(new SendOtpMail($email, $otp))
        );

        return back()->with('success', 'Đã gửi lại mã OTP');
    }
}
