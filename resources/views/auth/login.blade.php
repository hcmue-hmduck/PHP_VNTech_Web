@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="min-h-screen relative overflow-hidden bg-[#121414] flex flex-col items-center justify-center p-6 py-12">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#00ff66]/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[#00ff66]/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png')"></div>
    </div>
    <!-- Main Login Card -->
    <div class="w-full max-w-[480px] relative z-10">
        <div class="bg-slate-900/40 backdrop-blur-md p-10 md:p-12 border border-white/10 shadow-2xl relative overflow-hidden group">
            <!-- Decorative Glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#00ff66]/10 blur-3xl pointer-events-none"></div>
            
            <div class="text-center mb-10">
                <h2 class="text-5xl md:text-6xl font-black font-space tracking-tighter text-lime-400 uppercase mb-2 glow-text">
                    Đăng Nhập
                </h2>
                <div class="h-0.5 w-12 bg-lime-400 mx-auto mb-2 opacity-50"></div>
            </div>
            <!-- Laravel Login Form -->
            <form class="space-y-8" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="text-[10px] font-space font-bold tracking-[0.2em] uppercase text-zinc-500 ml-1">
                        Tên Đăng Nhập
                    </label>
                    <div class="relative group/field">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="user" class="text-zinc-600 group-focus-within/field:text-lime-400 transition-colors w-5 h-5"></i>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            placeholder="OPERATOR_EMAIL@VNTECH.VN"
                            class="w-full bg-slate-950/80 border border-white/5 py-4 pl-12 pr-4 text-sm font-space tracking-wider focus:outline-none focus:border-lime-400/50 focus:bg-slate-950 transition-all @error('email') border-red-500 @enderror"
                            required
                        />
                        <div class="absolute bottom-0 left-0 h-[1px] bg-lime-400 w-0 group-focus-within/field:w-full transition-all duration-500"></div>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Password Field -->
                <div class="space-y-2">
                    <label class="text-[10px] font-space font-bold tracking-[0.2em] uppercase text-zinc-500 ml-1">
                        Mật Khẩu
                    </label>
                    <div class="relative group/field">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="text-zinc-600 group-focus-within/field:text-lime-400 transition-colors w-5 h-5"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password"
                            name="password"
                            placeholder="••••••••••••"
                            class="w-full bg-slate-950/80 border border-white/5 py-4 pl-12 pr-12 text-sm font-space tracking-widest focus:outline-none focus:border-lime-400/50 focus:bg-slate-950 transition-all @error('password') border-red-500 @enderror"
                            required
                        />
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-4 flex items-center text-zinc-600 hover:text-lime-400 transition-colors z-20">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                        <div class="absolute bottom-0 left-0 h-[1px] bg-lime-400 w-0 group-focus-within/field:w-full transition-all duration-500"></div>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-3 cursor-pointer group/check">
                        <div class="relative w-4 h-4">
                            <input type="checkbox" name="remember" class="peer absolute inset-0 opacity-0 cursor-pointer" />
                            <div class="w-4 h-4 border border-zinc-700 bg-slate-950 rounded-sm peer-checked:bg-lime-400 peer-checked:border-lime-400 transition-all"></div>
                            <i data-lucide="shield-check" class="absolute inset-x-0 mx-auto top-0.5 text-black opacity-0 peer-checked:opacity-100 transition-opacity w-3 h-3"></i>
                        </div>
                        <span class="text-[11px] font-bold font-space uppercase tracking-wider text-zinc-500 group-hover/check:text-zinc-300 transition-colors">
                            Ghi nhớ thiết bị
                        </span>
                    </label>
                    <a href="#" class="text-[11px] font-bold font-space uppercase tracking-wider text-lime-400/70 hover:text-lime-400 transition-colors">
                        Quên mật khẩu ?
                    </a>
                </div>
                <button 
                    type="submit"
                    class="w-full bg-lime-400 text-slate-950 py-5 font-space font-black text-sm tracking-[0.25em] uppercase shadow-[0_0_20px_rgba(0,255,102,0.3)] hover:shadow-[0_0_40px_rgba(0,255,102,0.4)] hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group"
                >
                    <span class="relative z-10">Đăng Nhập</span>
                    <i data-lucide="arrow-right" class="relative z-10 w-5 h-5"></i>
                    <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>
            </form>
            <div class="mt-12 pt-8 border-t border-white/5 space-y-6 text-center">
                <p class="text-[9px] text-zinc-600 uppercase font-bold tracking-[0.3em]">
                    Phương thức đăng nhập khác
                </p>
                
                <a href="{{route('google.login')}}">
                    <button class="w-full bg-white/5 border border-white/10 py-3 rounded-sm flex items-center justify-center gap-3 group hover:bg-white/10 transition-all">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="text-[10px] font-space font-bold text-white uppercase tracking-[0.1em] group-hover:text-lime-400 transition-colors">
                            Tiếp tục với Google
                        </span>
                    </button>
                </a>
                <p class="text-xs text-zinc-500 font-space uppercase tracking-widest mt-4">
                    Chưa có tài khoản? <a href="{{ route('register') }}" class="text-lime-400 hover:underline">Đăng ký ngay</a>
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
            btn.innerHTML = '<i data-lucide="eye-off" class="w-5 h-5"></i>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<i data-lucide="eye" class="w-5 h-5"></i>';
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endsection