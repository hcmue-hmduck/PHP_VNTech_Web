@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="min-h-[calc(100vh-102px)] relative overflow-hidden bg-[#FAF8F2] flex flex-col items-center justify-center p-6 py-12">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-brand-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Main Forgot Password Card -->
    <div class="w-full max-w-[460px] relative z-10">
        <div class="bg-white border border-neutral-200/60 p-8 md:p-10 rounded-3xl shadow-xl shadow-slate-200/50 relative overflow-hidden">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-display font-black tracking-tight text-neutral-900 uppercase">
                    Quên Mật Khẩu
                </h2>
                <p class="text-neutral-500 text-xs font-semibold mt-2">Nhập email của bạn để nhận mã xác thực OTP khôi phục tài khoản</p>
            </div>

            <form class="space-y-6" action="{{ route('otp.send', ['flow' => 'forgot-password'])}}" method="POST">
                @csrf

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i> Email tài khoản
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nhap-email@vntech.vn"
                        class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-brand-500 focus:bg-white text-sm rounded-xl py-3 px-4 focus:ring-2 focus:ring-brand-500/20 transition-all text-neutral-800 font-medium focus:outline-none"
                        required
                        autofocus
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white py-3.5 px-6 font-display font-bold text-sm uppercase rounded-xl transition-all shadow-md shadow-brand-500/10 active:scale-95 flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span>Xác Nhận</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-neutral-100 text-center">
                <p class="text-xs text-neutral-500 font-semibold">
                    Đã nhớ mật khẩu?
                    <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-600 font-bold ml-1 hover:underline">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
