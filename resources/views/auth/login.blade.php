@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="min-h-[calc(100vh-102px)] relative overflow-hidden bg-[#FAF8F2] flex flex-col items-center justify-center p-6 py-12">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-brand-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Main Login Card -->
    <div class="w-full max-w-[460px] relative z-10">
        <div class="bg-white border border-neutral-200/60 p-8 md:p-10 rounded-3xl shadow-xl shadow-slate-200/50 relative overflow-hidden">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-display font-black tracking-tight text-neutral-900 uppercase">
                    Đăng Nhập
                </h2>
                <p class="text-neutral-500 text-xs font-semibold mt-2">Chào mừng bạn trở lại với Hệ sinh thái VNTech</p>
            </div>

            <!-- Laravel Login Form -->
            <form class="space-y-5" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i> Email Đăng Nhập
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="nhap-email@vntech.vn"
                        class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-brand-500 focus:bg-white text-sm rounded-xl py-3 px-4 focus:ring-2 focus:ring-brand-500/20 transition-all text-neutral-800 font-medium focus:outline-none @error('email') border-red-500 @enderror"
                        required
                        autofocus
                    />
                    @error('email')
                        <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i> Mật Khẩu
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password"
                            name="password"
                            placeholder="••••••••••••"
                            class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-brand-500 focus:bg-white text-sm rounded-xl py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-500/20 transition-all text-neutral-800 font-medium focus:outline-none @error('password') border-red-500 @enderror"
                            required
                        />
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-3 flex items-center text-neutral-400 hover:text-neutral-700 transition-colors">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group/check">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-brand-500 border-neutral-300 rounded focus:ring-brand-500/20 cursor-pointer" />
                        <span class="text-xs font-semibold text-neutral-500 group-hover:text-neutral-700 transition-colors">
                            Ghi nhớ đăng nhập
                        </span>
                    </label>
                    <a href="{{route('password.forgot')}}" class="text-xs font-bold text-brand-500 hover:text-brand-650 transition-colors hover:underline">
                        Quên mật khẩu?
                    </a>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white py-3.5 px-6 font-display font-bold text-sm uppercase rounded-xl transition-all shadow-md shadow-brand-500/10 active:scale-95 flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span>Đăng Nhập</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-neutral-100 space-y-4 text-center">
                <p class="text-[10px] text-neutral-400 uppercase font-bold tracking-widest">
                    Hoặc đăng nhập bằng
                </p>
                
                <a href="{{route('google.login')}}" class="block">
                    <button class="w-full bg-white hover:bg-neutral-50 border border-neutral-200 hover:border-neutral-300 py-3 rounded-xl flex items-center justify-center gap-2.5 transition-all active:scale-[0.98] shadow-sm cursor-pointer">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="text-xs font-bold text-neutral-700 uppercase">
                            Tiếp tục với Google
                        </span>
                    </button>
                </a>
                
                <p class="text-xs text-neutral-500 font-semibold mt-4">
                    Chưa có tài khoản? <a href="{{ route('register') }}" class="text-brand-500 hover:text-brand-600 font-bold ml-1 hover:underline">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4"></i>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<i data-lucide="eye" class="w-4 h-4"></i>';
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endsection