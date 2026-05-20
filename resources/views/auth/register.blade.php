@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="min-h-screen flex flex-col bg-[#121414] relative overflow-hidden">
    <!-- Ambient background glows -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -left-1/4 w-[800px] h-[800px] bg-lime-400/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 -right-1/4 w-[600px] h-[600px] bg-lime-400/5 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png')"></div>
    </div>

    <main class="flex-grow pt-32 pb-20 flex flex-col items-center justify-center relative z-10 px-6">
        <div class="w-full max-w-[480px]">
            <div class="bg-slate-900/40 backdrop-blur-md rounded-sm border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] py-12 px-10 relative group">
                <div class="mb-10 text-center">
                    <h1 class="font-space text-center uppercase tracking-tighter text-lime-400 glow-text" 
                        style="font-size: 56px; font-weight: 900; white-space: nowrap">
                        ĐĂNG KÝ
                    </h1>
                    <div class="h-0.5 w-12 bg-lime-400 mx-auto mt-2 opacity-50"></div>
                </div>

                <form class="space-y-6" action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Full Name -->
                    <div class="space-y-1 group/field">
                        <label class="font-space text-[10px] uppercase text-zinc-500 tracking-[0.2em] font-semibold">
                            Họ và tên
                        </label>
                        <div class="flex items-center bg-[#1a1c1c] border border-white/5 hover:border-lime-400/50 transition-all">
                            <div class="p-3 text-zinc-500 group-focus-within/field:text-lime-400 transition-colors">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <input 
                                type="text"
                                name="ho_ten"
                                value="{{ old('ho_ten') }}"
                                class="w-full bg-transparent border-none focus:ring-0 text-white font-inter placeholder:text-zinc-700 h-12 text-sm tracking-wide"
                                placeholder="ENTER NAME"
                                required
                            />
                        </div>
                        @error('ho_ten')
                            <p class="text-red-500 text-[10px] font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-1 group/field">
                        <label class="font-space text-[10px] uppercase text-zinc-500 tracking-[0.2em] font-semibold">
                            Email
                        </label>
                        <div class="flex items-center bg-[#1a1c1c] border border-white/5 hover:border-lime-400/50 transition-all">
                            <div class="p-3 text-zinc-500 group-focus-within/field:text-lime-400 transition-colors">
                                <i data-lucide="at-sign" class="w-5 h-5"></i>
                            </div>
                            <input 
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full bg-transparent border-none focus:ring-0 text-white font-inter placeholder:text-zinc-700 h-12 text-sm tracking-wide"
                                placeholder="USER@VNTECH.NETWORK"
                                required
                            />
                        </div>
                        @error('email')
                            <p class="text-red-500 text-[10px] font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1 group/field" x-data="{ show: false }">
                        <label class="font-space text-[10px] uppercase text-zinc-500 tracking-[0.2em] font-semibold">
                            Mật khẩu
                        </label>
                        <div class="flex items-center bg-[#1a1c1c] border border-white/5 hover:border-lime-400/50 transition-all">
                            <div class="p-3 text-zinc-500 group-focus-within/field:text-lime-400 transition-colors">
                                <i data-lucide="key" class="w-5 h-5"></i>
                            </div>
                            <input 
                                :type="show ? 'text' : 'password'"
                                name="password"
                                class="w-full bg-transparent border-none focus:ring-0 text-white font-inter placeholder:text-zinc-700 h-12 text-sm tracking-widest"
                                placeholder="••••••••••••"
                                required
                            />
                            <button type="button" @click="show = !show" class="p-3 text-zinc-500 hover:text-white transition-colors">
                                <i :data-lucide="show ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[10px] font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1 group/field">
                        <label class="font-space text-[10px] uppercase text-zinc-500 tracking-[0.2em] font-semibold">
                            Nhập lại mật khẩu
                        </label>
                        <div class="flex items-center bg-[#1a1c1c] border border-white/5 hover:border-lime-400/50 transition-all">
                            <div class="p-3 text-zinc-500 group-focus-within/field:text-lime-400 transition-colors">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input 
                                type="password"
                                name="password_confirmation"
                                class="w-full bg-transparent border-none focus:ring-0 text-white font-inter placeholder:text-zinc-700 h-12 text-sm tracking-widest"
                                placeholder="••••••••••••"
                                required
                            />
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-lime-400 text-black font-space font-black text-lg uppercase py-4 flex items-center justify-center gap-3 transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,255,102,0.4)] hover:scale-[1.01] active:scale-95">
                            Đăng ký
                            <i data-lucide="rocket" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>

                <div class="pt-10 border-t border-white/5 mt-10">
                    <p class="text-[10px] text-zinc-600 uppercase tracking-[0.3em] font-bold text-center mb-6">
                        Phương thức đăng nhập khác
                    </p>
                    
                    <a href="{{route('google.login')}}">
                        <button class="flex items-center justify-center gap-3 bg-white/5 hover:bg-white/10 border border-white/10 p-4 rounded-none transition-all group active:scale-[0.98] w-full">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                            </svg>
                            <span class="font-space text-xs text-white uppercase tracking-widest font-bold group-hover:text-lime-400 transition-colors">
                                Tiếp tục với Google
                            </span>
                        </button>
                    </a>

                    <p class="text-xs text-zinc-500 font-space uppercase tracking-widest mt-6 text-center">
                        Đã có tài khoản? <a href="{{ route('login') }}" class="text-lime-400 hover:underline">Đăng nhập ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection
