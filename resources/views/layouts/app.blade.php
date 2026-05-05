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
            <a class="text-2xl font-black italic tracking-tighter text-lime-400 font-space uppercase" id="nav-logo" href="/">VNTech</a>
        </div>

        <!-- Center: Navigation Links -->
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-lime-400 border-b-2 border-lime-400 pb-1 text-sm font-medium uppercase tracking-wider" href="#">Store</a>
            <a class="text-white/70 hover:text-white transition-colors text-sm font-medium uppercase tracking-wider" href="#">Hardware</a>
            <a class="text-white/70 hover:text-white transition-colors text-sm font-medium uppercase tracking-wider" href="#">Gear</a>
            <a class="text-white/70 hover:text-white transition-colors text-sm font-medium uppercase tracking-wider" href="#">Community</a>
            <a class="text-white/70 hover:text-white transition-colors text-sm font-medium uppercase tracking-wider" href="#">Support</a>
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

            <!-- Shopping Cart & User Section -->
            <div class="flex items-center gap-4">
                <a href="{{ auth()->check() ? route('viewCart', ['user_id' => auth()->id()]) : route('login') }}" class="hover:bg-white/5 p-2 rounded-full transition-all text-lime-400 relative inline-block">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    <span class="absolute top-0 right-0 bg-white text-black text-[8px] font-bold px-1 rounded-full">3</span>
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
                                 class="w-full h-full object-cover" 
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

                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-white/5 hover:text-lime-400 transition-all">
                            <i data-lucide="user-cog" class="w-4 h-4"></i> Hồ sơ cá nhân
                        </a>
                        
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-white/5 hover:text-lime-400 transition-all">
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
    <footer class="bg-slate-950 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-12">
            <div class="flex flex-col items-center md:items-start gap-4">
                <span class="text-3xl font-black text-lime-400 font-space uppercase italic">VNTech</span>
                <p class="text-sm text-slate-500 max-w-xs text-center md:text-left leading-relaxed">
                    © 2024 VNTech Gaming Store. All Rights Reserved. Engineered for the future of play with uncompromising quality.
                </p>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach(['Privacy Policy', 'Terms of Service', 'Warranty', 'Returns'] as $link)
                    <a class="text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-white transition-colors" href="#">{{ $link }}</a>
                @endforeach
            </div>
            <div class="flex gap-4">
                <div class="w-12 h-12 border border-white/10 flex items-center justify-center hover:border-lime-400 transition-all cursor-pointer group">
                    <i data-lucide="share-2" class="text-slate-500 w-5 h-5 group-hover:text-lime-400 transition-colors"></i>
                </div>
                <div class="w-12 h-12 border border-white/10 flex items-center justify-center hover:border-lime-400 transition-all cursor-pointer group">
                    <i data-lucide="layout-grid" class="text-slate-500 w-5 h-5 group-hover:text-lime-400 transition-colors"></i>
                </div>
                <div class="w-12 h-12 border border-white/10 flex items-center justify-center hover:border-lime-400 transition-all cursor-pointer group">
                    <i data-lucide="globe" class="text-slate-500 w-5 h-5 group-hover:text-lime-400 transition-colors"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        lucide.createIcons();
    </script>
    @yield('scripts')
</body>
</html>
