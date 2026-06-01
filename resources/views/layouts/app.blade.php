<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VNTECH | Hệ sinh thái phần cứng thế hệ mới')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'space': ['"Space Grotesk"', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts & Global Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;500;700&display=swap');
        .glow-text { text-shadow: 0 0 20px rgba(0, 229, 91, 0.5); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: transparent; border-radius: 4px; }
        html:hover::-webkit-scrollbar-thumb { background: #444646; }
        html:hover::-webkit-scrollbar-thumb:hover { background: #00e55b; }
        html { scrollbar-width: thin; scrollbar-color: transparent transparent; }
        html:hover { scrollbar-color: #444646 transparent; }
    </style>
</head>
<body class="bg-[#121414] text-white selection:bg-lime-400 selection:text-black min-h-screen">

    <!-- Header / Navbar -->
    <header class="fixed top-0 w-full flex items-center px-8 h-20 bg-slate-950/80 backdrop-blur-2xl z-50 border-b border-white/10 shadow-[0_0_20px_rgba(0,255,102,0.1)]">
        <!-- Left: Logo -->
        <div class="flex-1 flex items-center">
            <a class="flex items-center gap-2 text-2xl font-black italic tracking-tighter text-lime-400 font-space uppercase" id="nav-logo" href="/">
                <img src="{{ asset('vntech_logo.ico') }}" alt="VNTech Logo" class="w-8 h-8 rounded-full border border-lime-400/30 object-cover" />
                <span>VNTech</span>
            </a>
        </div>

        <!-- Center: Navigation Links -->
        <nav class="hidden md:flex items-center gap-8">
            <a class="{{ request()->routeIs('viewHome') ? 'text-lime-400 border-b-2 border-lime-400' : 'text-white/70 hover:text-white border-b-2 border-transparent hover:border-white/20' }} pb-1 text-sm font-medium uppercase tracking-wider transition-all" href="{{ route('viewHome') }}">Trang chủ</a>
            <a class="{{ request()->routeIs('support') ? 'text-lime-400 border-b-2 border-lime-400' : 'text-white/70 hover:text-white border-b-2 border-transparent hover:border-white/20' }} pb-1 text-sm font-medium uppercase tracking-wider transition-all" href="{{ route('support') }}">Hỗ trợ</a>
            <a class="{{ request()->routeIs('policy') ? 'text-lime-400 border-b-2 border-lime-400' : 'text-white/70 hover:text-white border-b-2 border-transparent hover:border-white/20' }} pb-1 text-sm font-medium uppercase tracking-wider transition-all" href="{{ route('policy') }}">Chính sách</a>
            <a class="{{ request()->routeIs('contact') ? 'text-lime-400 border-b-2 border-lime-400' : 'text-white/70 hover:text-white border-b-2 border-transparent hover:border-white/20' }} pb-1 text-sm font-medium uppercase tracking-wider transition-all" href="{{ route('contact') }}">Liên hệ</a>
        </nav>

        <!-- Right: Actions (Search, Compare, Cart, User) -->
        <div class="flex-1 flex items-center justify-end gap-4" x-data="{ searchExpanded: false }">
            <!-- Search Bar (Expandable) -->
            <div class="flex items-center bg-white/5 border border-white/10 rounded-full transition-all duration-500 ease-out overflow-hidden"
                 :class="searchExpanded ? 'w-64 px-4 py-1.5 border-lime-400 shadow-[0_0_15px_rgba(0,229,91,0.2)]' : 'w-10 h-10 justify-center cursor-pointer hover:bg-white/10'"
                 @click="searchExpanded = true">
                <i data-lucide="search" class="text-white/50 w-5 h-5 shrink-0" :class="searchExpanded ? 'text-lime-400' : ''"></i>
                <input x-show="searchExpanded" 
                       x-transition:enter="transition opacity duration-300 delay-200"
                       x-transition:enter-start="opacity-0"
                       x-transition:enter-end="opacity-100"
                       class="bg-transparent border-none focus:outline-none text-sm text-white w-full placeholder:text-white/30 ml-2" 
                       placeholder="Tìm kiếm gear..." 
                       type="text"
                       @click.away="searchExpanded = false"/>
            </div>

            <!-- AI Compare Button -->
            <button class="hover:bg-white/5 p-2 rounded-full transition-all text-lime-400 relative group" title="So sánh AI">
                <i data-lucide="zap" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
                <span class="absolute -top-1 -right-1 bg-lime-400 text-black text-[7px] font-bold px-1 py-0.5 rounded-full animate-pulse border border-black">AI</span>
            </button>

            @php
                $cartCount = 0;
                if (auth()->check()) {
                    $cart = \App\Models\Cart::where('ma_nguoi_dung', auth()->id())->first();
                    if ($cart) {
                        $cartCount = \App\Models\CartItem::where('ma_gio_hang', $cart->_id)->count();
                    }
                }
            @endphp
               <!-- Shopping Cart & User Section -->
            <div class="flex items-center gap-4">
                <a href="{{ auth()->check() ? route('cart.view') : route('login') }}" class="hover:bg-white/5 p-2 rounded-full transition-all text-lime-400 relative inline-block">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    <span id="cart-badge" class="absolute top-0 right-0 bg-white text-black text-[8px] font-bold px-1 rounded-full">{{ $cartCount }}</span>
                </a>
                
                @guest
                <a href="{{ route('login') }}" class="flex items-center gap-2 hover:bg-white/5 px-4 py-2 rounded-full transition-all text-lime-400 border border-lime-400/20">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Đăng nhập</span>
                </a>
                @endguest

                @auth
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <!-- Avatar Trigger -->
                    <button class="flex items-center gap-3 focus:outline-none">
                        <div class="flex flex-col items-end hidden sm:flex gap-1">
                            <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest leading-none">Xin chào,</span>
                            <span class="text-[11px] font-bold text-white leading-none">{{ Auth::user()->ho_ten }}</span>
                        </div>
                        <div class="w-10 h-10 rounded-full border-2 border-lime-400/30 overflow-hidden group-hover:border-lime-400 transition-all shadow-[0_0_15px_rgba(0,229,91,0.1)]">
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 class="w-full h-full object-contain" 
                                  alt="Avatar">
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-[#1a1c1c] border border-white/10 shadow-2xl z-[60] py-2 overflow-hidden"
                         style="display: none;">
                        
                        <div class="px-4 py-3 border-b border-white/5 mb-2">
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Tài khoản</p>
                            <p class="text-sm font-bold text-lime-400 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        @if (Auth::user()->vai_tro == 'admin') 
                            <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-white/5 hover:text-lime-400 transition-all">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Trang quản trị
                            </a>
                        @endif

                        <a href="{{ route('user.view') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-white/5 hover:text-lime-400 transition-all">
                            <i data-lucide="user-cog" class="w-4 h-4"></i> Hồ sơ cá nhân
                        </a>
                        
                        <a href="{{ route('order.view') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-white/5 hover:text-lime-400 transition-all">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Lịch sử mua hàng
                        </a>

                        <div class="border-t border-white/5 mt-2 pt-2">
                            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                    class="w-full flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-red-400 hover:bg-red-500/10 transition-all">
                                <i data-lucide="log-out" class="w-4 h-4"></i> Đăng xuất
                            </button>
                        </div>
                    </div>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <!-- Footer -->
    <footer class="bg-black/80 backdrop-blur-md border-t border-lime-400/10 relative overflow-hidden">
        <!-- Subtle neon glow under the border -->
        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-lime-400/50 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-8 pt-16 pb-12 relative z-10">
            <!-- Top Section with Grid (3 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-12 border-b border-white/5">
                <!-- Column 1: Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('vntech_logo.ico') }}" alt="VNTech Logo" class="w-9 h-9 rounded-full border border-lime-400/30 object-cover" />
                        <span class="text-3xl font-black text-lime-400 font-space uppercase italic tracking-tighter">VNTech</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Nhà cung cấp máy tính, laptop và linh kiện phần cứng thế hệ mới hàng đầu. Mang lại hiệu năng đỉnh cao cho mọi tác vụ gaming và thiết kế chuyên nghiệp.
                    </p>
                </div>

                <!-- Column 2: Policy & Support Links -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white">Chính sách & Hỗ trợ</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('policy') }}" class="text-xs text-slate-400 hover:text-lime-400 transition-colors">Chính sách bảo hành & Đổi trả</a></li>
                        <li><a href="{{ route('policy') }}" class="text-xs text-slate-400 hover:text-lime-400 transition-colors">Bảo mật thông tin khách hàng</a></li>
                        <li><a href="{{ route('support') }}" class="text-xs text-slate-400 hover:text-lime-400 transition-colors">Hướng dẫn mua hàng trực tuyến</a></li>
                        <li><a href="{{ route('support') }}" class="text-xs text-slate-400 hover:text-lime-400 transition-colors">Dịch vụ hỗ trợ kỹ thuật 24/7</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact & Showroom -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white">Thông tin liên hệ</h4>
                    <ul class="space-y-3 text-xs text-slate-400">
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-lime-400 shrink-0 mt-0.5"></i>
                            <span>Công ty TNHH Công nghệ VNTech, Quận 5, TP. Hồ Chí Minh</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i data-lucide="phone" class="w-4 h-4 text-lime-400 shrink-0"></i>
                            <span class="font-mono">1900 1234 (Hotline chăm sóc)</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i data-lucide="mail" class="w-4 h-4 text-lime-400 shrink-0"></i>
                            <span class="font-mono">support@vntech.vn</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Section (Copyright centered) -->
            <div class="pt-8 text-center space-y-2">
                <p class="text-xs text-slate-500 leading-relaxed">
                    © 2026 VNTech Shop. Bản quyền thuộc về HMDuck và VTTam
                </p>
                <p class="text-xs">
                    <a href="https://github.com/hcmue-hmduck/PHP_VNTech_Web" target="_blank" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-lime-400 transition-colors">
                        <span>Source code:</span>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                    </a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        lucide.createIcons();

        // Global SweetAlert2 Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#0f172a', // slate-900
            color: '#ffffff',
            customClass: {
                popup: 'border border-white/10 rounded-2xl shadow-2xl backdrop-blur-md',
                htmlContainer: 'font-space text-xs font-bold uppercase tracking-wider !text-white',
                timerProgressBar: 'bg-lime-400'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.showToast = function(message, type = 'success') {
            Toast.fire({
                icon: type,
                html: message
            });
        };
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast("{{ session('success') }}", 'success');
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast("{{ session('error') }}", 'error');
            });
        </script>
    @endif
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast("{!! implode('<br>', $errors->all()) !!}", 'error');
            });
        </script>
    @endif

    @include('layouts.chatbot')
    @yield('scripts')
</body>
</html>
