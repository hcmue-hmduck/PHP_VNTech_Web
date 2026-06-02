@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="min-h-screen relative overflow-hidden bg-[#121414] flex flex-col items-center justify-center p-6 py-12">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#00ff66]/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[#00ff66]/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png')"></div>
    </div>

    <!-- Main Forgot Password Card -->
    <div class="w-full max-w-[480px] relative z-10">
        <div class="bg-slate-900/40 backdrop-blur-md p-10 md:p-12 border border-white/10 shadow-2xl relative overflow-hidden group">
            <!-- Decorative Glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#00ff66]/10 blur-3xl pointer-events-none"></div>

            <div class="text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-black font-space tracking-tighter text-lime-400 uppercase mb-2 glow-text">
                    Quên Mật Khẩu
                </h2>
                <div class="h-0.5 w-12 bg-lime-400 mx-auto mb-2 opacity-50"></div>
            </div>

            <form class="space-y-8" action="{{ route('otp.send', ['flow' => 'forgot-password'])}}" method="POST">
                @csrf

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="text-[10px] font-space font-bold tracking-[0.2em] uppercase text-zinc-500 ml-1">
                        Email tài khoản
                    </label>
                    <div class="relative group/field">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="text-zinc-600 group-focus-within/field:text-lime-400 transition-colors w-5 h-5"></i>
                        </div>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="USER@VNTECH.VN"
                            class="w-full bg-slate-950/80 border border-white/5 py-4 pl-12 pr-4 text-sm font-space tracking-wider text-white placeholder-zinc-700 focus:outline-none focus:border-lime-400/50 focus:bg-slate-950 transition-all"
                            required
                            autofocus
                        />
                        <div class="absolute bottom-0 left-0 h-[1px] bg-lime-400 w-0 group-focus-within/field:w-full transition-all duration-500"></div>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-lime-400 text-slate-950 py-5 font-space font-black text-sm tracking-[0.25em] uppercase shadow-[0_0_20px_rgba(0,255,102,0.3)] hover:shadow-[0_0_40px_rgba(0,255,102,0.4)] hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group"
                >
                    <span class="relative z-10">Xác Nhận</span>
                    <i data-lucide="arrow-right" class="relative z-10 w-5 h-5"></i>
                    <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-white/5 text-center">
                <p class="text-xs text-zinc-500 font-space uppercase tracking-widest">
                    Nhớ mật khẩu?
                    <a href="{{ route('login') }}" class="text-lime-400 hover:underline">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
