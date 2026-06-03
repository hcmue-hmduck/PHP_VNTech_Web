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
                    colors: {
                        brand: {
                            50: '#fff5f0',
                            100: '#ffe6d5',
                            200: '#ffc8a6',
                            300: '#ffa16c',
                            400: '#ff7332',
                            500: '#ff4f00', // Electric Orange
                            600: '#e03d00',
                            700: '#ba2e00',
                            800: '#942503',
                            900: '#782005',
                        },
                        accent: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#b9dffd',
                            300: '#7cc4fb',
                            400: '#36a5f7',
                            500: '#0c87eb', // Bright Cyber Blue
                            600: '#0069c6',
                            700: '#0054a1',
                            800: '#034785',
                            900: '#093c6e',
                        },
                        cyber: {
                            pink: '#ff007a',
                            purple: '#bd00ff',
                            green: '#00e575',
                            yellow: '#ffd600',
                        }
                    },
                    fontFamily: {
                        'space': ['"Space Grotesk"', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                        'display': ['"Space Grotesk"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts & Global Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;500;700&display=swap');
        .glow-text { text-shadow: 0 0 20px rgba(255, 79, 0, 0.4); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: transparent; border-radius: 4px; }
        html:hover::-webkit-scrollbar-thumb { background: #cbd5e1; }
        html:hover::-webkit-scrollbar-thumb:hover { background: #ff4f00; }
        html { scrollbar-width: thin; scrollbar-color: transparent transparent; }
        html:hover { scrollbar-color: #cbd5e1 transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#FAF8F2] text-slate-800 selection:bg-brand-500/20 selection:text-brand-500 min-h-screen">


    <!-- Header Container -->
    <header class="fixed top-0 left-0 w-full z-40 shadow-[0_4px_25px_rgba(0,0,0,0.05)]">
        <!-- Tier 1: Top Branding & Search Bar -->
        <div class="bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3">
                <div class="flex items-center justify-between gap-4">
                    
                    <!-- Store Branding Header Logo -->
                    <a href="{{ route('home.index') }}" class="flex items-center gap-2 px-1 focus:ring-2 focus:ring-brand-500 rounded-lg">
                        <img src="{{ asset('vntech_logo.ico') }}" alt="VNTech Logo" class="w-9 h-9 rounded-full border border-slate-800 object-cover" />
                        <span class="text-3xl font-display font-black tracking-tighter bg-gradient-to-r from-brand-500 to-brand-600 bg-clip-text text-transparent hover:opacity-90 transition-opacity">
                            VNTech
                        </span>
                    </a>

                    <!-- Interactive Products Search Command Box -->
                    <div class="flex-grow max-w-xl relative hidden md:block">
                        <div class="relative">
                            <input
                                type="text"
                                placeholder="Bạn cần tìm siêu phẩm công nghệ gì hôm nay?..."
                                class="w-full bg-slate-800 border border-slate-700 hover:border-slate-600 focus:border-accent-500 focus:bg-slate-950 text-xs rounded-full py-3 px-6 pl-11 pr-12 focus:ring-4 focus:ring-accent-500/10 transition-all duration-300 text-slate-100 placeholder-slate-400 outline-none"
                            />
                            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-[15px] h-[15px]"></i>
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-brand-500 hover:bg-brand-600 p-1.5 rounded-full hover:shadow-[0_2px_10px_rgba(255,79,0,0.3)] transition-all duration-300 text-white">
                                <i data-lucide="search" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>

                    <!-- User Interaction Cart/Wishlist Utilities -->
                    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                        
                        <!-- Liked list increment trigger -->
                        <button 
                            class="relative p-2 rounded-full hover:bg-slate-800 text-slate-400 hover:text-rose-500 transition-all duration-300"
                            title="Danh sách yêu thích"
                        >
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>

                        <button class="relative p-2 rounded-full hover:bg-slate-800 text-slate-400 hover:text-white transition-all duration-300">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-1 right-1 bg-brand-500 text-[9px] w-4 h-4 flex items-center justify-center rounded-full text-white font-bold animate-pulse">2</span>
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

                        <!-- Shopping Cart Drawer Trigger badge block -->
                        <a href="{{ auth()->check() ? route('cart.view') : route('login') }}"
                           class="flex items-center gap-1.5 sm:gap-2 bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 shadow-md hover:shadow-brand-500/20 active:scale-95 transition-all py-2.5 px-3 sm:px-5 rounded-2xl text-white font-display duration-300"
                        >
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            <span class="text-xs font-extrabold hidden sm:inline">Giỏ hàng</span>
                            <span class="bg-white/20 text-white text-[10px] font-black px-2.5 py-0.5 rounded-lg">
                                {{ $cartCount }}
                            </span>
                        </a>

                        @guest
                        <a href="{{ route('login') }}" class="flex items-center gap-2 text-slate-300 hover:text-accent-400 p-1 rounded-full transition-colors duration-300">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <span class="hidden lg:inline text-xs font-extrabold">Đăng nhập</span>
                        </a>
                        @endguest

                        @auth
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <!-- Avatar Trigger -->
                            <button class="flex items-center gap-2.5 text-slate-300 hover:text-accent-400 p-1 rounded-xl transition-colors focus:outline-none cursor-pointer">
                                <div class="text-right leading-none hidden lg:flex flex-col items-end">
                                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Xin chào</span>
                                    <span class="text-xs font-black text-slate-200 mt-1">{{ Auth::user()->ho_ten }}</span>
                                </div>
                                <img src="{{ Auth::user()->avatar_url }}" 
                                     alt="Avatar" 
                                     class="w-8 h-8 rounded-full object-cover border border-slate-700 hover:border-brand-500 transition-colors"
                                     onerror="this.src='https://api.dicebear.com/7.x/adventurer-neutral/svg?seed={{ Auth::user()->email }}'">
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-slate-950 border border-slate-850 shadow-2xl z-[60] py-2 rounded-2xl overflow-hidden text-slate-200"
                                 style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-slate-800/80 mb-2">
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Tài khoản</p>
                                    <p class="text-xs font-black text-slate-200 truncate mt-1">{{ Auth::user()->ho_ten }}</p>
                                    <p class="text-xs font-bold text-brand-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                @if (Auth::user()->vai_tro == 'admin') 
                                    <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-slate-900 hover:text-accent-400 transition-all">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Trang quản trị
                                    </a>
                                @endif

                                <a href="{{ route('user.view') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-slate-900 hover:text-accent-400 transition-all">
                                    <i data-lucide="user-cog" class="w-4 h-4"></i> Hồ sơ cá nhân
                                </a>
                                
                                <a href="{{ route('order.view') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-300 hover:bg-slate-900 hover:text-accent-400 transition-all">
                                    <i data-lucide="shopping-bag" class="w-4 h-4"></i> Lịch sử mua hàng
                                </a>

                                <div class="border-t border-slate-800/80 mt-2 pt-2">
                                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                            class="w-full flex items-center gap-3 px-4 py-3 text-xs font-bold uppercase tracking-widest text-red-400 hover:bg-red-950/30 transition-all text-left">
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
            </div>
        </div>

        <!-- Secondary Category Ribbon Navigation Option layout -->
        <nav class="border-y border-slate-200/50 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-2 flex items-center justify-between gap-6">
                
                @php
                    $allCategories = \App\Models\Category::where('trang_thai', 'active')->get();
                    if ($allCategories->isEmpty()) {
                        $allCategories = \App\Models\Category::all();
                    }
                    
                    // Level 1: Root categories (no parent, or parent = 'none')
                    $level1 = $allCategories->filter(fn($cat) => empty($cat->ma_danh_muc_cha) || $cat->ma_danh_muc_cha === 'none');
                    
                    // Level 2: Children of Level 1
                    $level2 = $allCategories->filter(function($cat) use ($level1) {
                        if (empty($cat->ma_danh_muc_cha) || $cat->ma_danh_muc_cha === 'none') return false;
                        return $level1->contains('ma_danh_muc', $cat->ma_danh_muc_cha);
                    });
                    
                    // Level 3: Children of Level 2 (Grandchildren)
                    $level3 = $allCategories->filter(function($cat) use ($level2) {
                        if (empty($cat->ma_danh_muc_cha) || $cat->ma_danh_muc_cha === 'none') return false;
                        return $level2->contains('ma_danh_muc', $cat->ma_danh_muc_cha);
                    });

                    // Build maps for Alpine
                    $l1HasChildren = [];
                    foreach ($level1 as $l1) {
                        $l1HasChildren[$l1->ma_danh_muc] = $level2->contains('ma_danh_muc_cha', $l1->ma_danh_muc);
                    }
                    
                    $l2HasChildren = [];
                    foreach ($level2 as $l2) {
                        $l2HasChildren[$l2->ma_danh_muc] = $level3->contains('ma_danh_muc_cha', $l2->ma_danh_muc);
                    }
                @endphp

                <!-- Category Dropdown Trigger -->
                <div class="relative shrink-0" 
                     x-data='{ 
                         openCategories: false, 
                         activeL1: "{{ $level1->first()?->ma_danh_muc }}",
                         activeL2: "",
                         l1HasChildren: @json($l1HasChildren),
                         l2HasChildren: @json($l2HasChildren),
                         
                         setActiveL1(id, firstChildId) {
                             this.activeL1 = id;
                             this.activeL2 = firstChildId;
                         }
                     }' 
                     @click.away="openCategories = false"
                >
                    <button 
                        @click="openCategories = !openCategories"
                        class="bg-gradient-to-r from-accent-600 to-accent-700 hover:from-accent-500 hover:to-accent-600 hover:shadow-md hover:shadow-accent-500/10 text-white px-4 py-2.5 rounded-xl flex items-center gap-2 text-xs font-extrabold transition-all active:scale-95 whitespace-nowrap focus:outline-none"
                    >
                        <i data-lucide="menu" class="w-3.5 h-3.5"></i>
                        Danh mục
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        x-show="openCategories"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        class="absolute left-0 mt-2 bg-white border border-slate-100 shadow-[0_15px_50px_rgba(0,0,0,0.08)] z-[60] rounded-2xl text-slate-800 flex overflow-hidden transition-all duration-300"
                        :class="l1HasChildren[activeL1] ? (l2HasChildren[activeL2] ? 'w-[768px]' : 'w-[512px]') : 'w-64'"
                        style="display: none;"
                    >
                        <!-- Column 1: Parent Categories (Level 1) -->
                        <div 
                            :class="l1HasChildren[activeL1] ? (l2HasChildren[activeL2] ? 'w-1/3 border-r' : 'w-1/2 border-r') : 'w-full'"
                            class="border-slate-100 bg-slate-50/50 py-3 max-h-[400px] overflow-y-auto no-scrollbar transition-all duration-300"
                        >
                            <div class="px-4 pb-2 border-b border-slate-100 mb-2">
                                <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest">Danh mục</span>
                            </div>
                            @foreach($level1 as $parent)
                                @php
                                    $firstChild = $level2->firstWhere('ma_danh_muc_cha', $parent->ma_danh_muc);
                                    $firstChildId = $firstChild ? $firstChild->ma_danh_muc : '';
                                @endphp
                                <div class="px-2">
                                    <a 
                                        href="/?category={{ $parent->ma_danh_muc }}#product-grid-section"
                                        @mouseenter="setActiveL1('{{ $parent->ma_danh_muc }}', '{{ $firstChildId }}')"
                                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-left"
                                        :class="activeL1 === '{{ $parent->ma_danh_muc }}' ? 'bg-accent-50/60 text-accent-600' : 'text-slate-600 hover:bg-slate-50'"
                                    >
                                        <span class="flex items-center gap-2">
                                            @if(!empty($parent->logo_url))
                                                <img src="{{ $parent->logo_url }}" class="w-4.5 h-4.5 object-contain" alt="" />
                                            @else
                                                <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                                            @endif
                                            {{ $parent->ten_danh_muc }}
                                        </span>
                                        @if($l1HasChildren[$parent->ma_danh_muc])
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 opacity-60"></i>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Column 2: Child Categories (Level 2) -->
                        <div 
                            x-show="l1HasChildren[activeL1]"
                            :class="l2HasChildren[activeL2] ? 'w-1/3 border-r' : 'w-1/2'"
                            class="border-slate-100 bg-white py-3 max-h-[400px] overflow-y-auto no-scrollbar transition-all duration-300"
                        >
                            <div class="px-4 pb-2 border-b border-slate-100 mb-2">
                                <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest">Danh mục con</span>
                            </div>
                            @foreach($level1 as $l1)
                                @php
                                    $l2Children = $level2->filter(fn($sub) => $sub->ma_danh_muc_cha === $l1->ma_danh_muc);
                                @endphp
                                @if($l2Children->isNotEmpty())
                                    <div x-show="activeL1 === '{{ $l1->ma_danh_muc }}'" class="space-y-1">
                                        @foreach($l2Children as $l2)
                                            <div class="px-2">
                                                <a 
                                                    href="/?category={{ $l2->ma_danh_muc }}#product-grid-section"
                                                    @mouseenter="activeL2 = '{{ $l2->ma_danh_muc }}'"
                                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all text-left"
                                                    :class="activeL2 === '{{ $l2->ma_danh_muc }}' ? 'bg-accent-50/60 text-accent-600' : 'text-slate-600 hover:bg-slate-50'"
                                                >
                                                    <span class="flex items-center gap-2">
                                                        @if(!empty($l2->logo_url))
                                                            <img src="{{ $l2->logo_url }}" class="w-4 h-4 object-contain" alt="" />
                                                        @else
                                                            <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                                                        @endif
                                                        {{ $l2->ten_danh_muc }}
                                                    </span>
                                                    @if($l2HasChildren[$l2->ma_danh_muc])
                                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 opacity-60"></i>
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Column 3: Grandchild Categories (Level 3) -->
                        <div 
                            x-show="l1HasChildren[activeL1] && l2HasChildren[activeL2]"
                            class="w-1/3 bg-slate-50/30 py-3 max-h-[400px] overflow-y-auto no-scrollbar transition-all duration-300"
                        >
                            <div class="px-4 pb-2 border-b border-slate-100 mb-2">
                                <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest">Chi tiết</span>
                            </div>
                            @foreach($level2 as $l2)
                                @php
                                    $l3Children = $level3->filter(fn($sub) => $sub->ma_danh_muc_cha === $l2->ma_danh_muc);
                                @endphp
                                @if($l3Children->isNotEmpty())
                                    <div x-show="activeL2 === '{{ $l2->ma_danh_muc }}'" class="space-y-1">
                                        @foreach($l3Children as $l3)
                                            <div class="px-2">
                                                <a 
                                                    href="/?category={{ $l3->ma_danh_muc }}#product-grid-section"
                                                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium text-slate-500 hover:bg-accent-50/60 hover:text-accent-600 transition-all duration-300"
                                                >
                                                    @if(!empty($l3->logo_url))
                                                        <img src="{{ $l3->logo_url }}" class="w-4 h-4 object-contain" alt="" />
                                                    @else
                                                        <i data-lucide="tag" class="w-3 h-3 text-slate-400"></i>
                                                    @endif
                                                    {{ $l3->ten_danh_muc }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Navigation List Options -->
                <div class="overflow-x-auto no-scrollbar flex-grow flex justify-center">
                    <ul class="flex items-center gap-6 text-xs text-slate-500 font-bold whitespace-nowrap tracking-wide">
                        <li><a href="{{ route('home.index') }}" class="{{ request()->routeIs('home.index') ? 'text-brand-500 font-extrabold border-b-2 border-brand-500 pb-1' : 'hover:text-slate-800 transition-colors duration-300' }}">Trang chủ</a></li>
                        <li><a href="{{ route('home.products') }}" class="{{ request()->routeIs('home.products') ? 'text-brand-500 font-extrabold border-b-2 border-brand-500 pb-1' : 'hover:text-slate-800 transition-colors duration-300' }}">Sản phẩm</a></li>
                        <li><a href="{{ route('home.news') }}" class="{{ request()->routeIs('home.news') ? 'text-brand-500 font-extrabold border-b-2 border-brand-500 pb-1' : 'hover:text-slate-800 transition-colors duration-300' }}">Tin tức</a></li>
                        <li><a href="{{ route('support') }}" class="{{ request()->routeIs('support') ? 'text-brand-500 font-extrabold border-b-2 border-brand-500 pb-1' : 'hover:text-slate-800 transition-colors duration-300' }}">Chính sách và Hỗ trợ</a></li>
                        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-brand-500 font-extrabold border-b-2 border-brand-500 pb-1' : 'hover:text-slate-800 transition-colors duration-300' }}">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="pt-[102px]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0b0f19] border-t border-slate-800 relative text-slate-300">
        <div class="max-w-7xl mx-auto px-8 pt-12 pb-8">
            <!-- Top Section with Grid (2 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pb-8 border-b border-slate-800">
                <!-- Column 1: Brand Info -->
                <div class="flex items-center gap-6">
                    <img src="{{ asset('vntech_logo.ico') }}" alt="VNTech Logo" class="w-32 h-32 rounded-full border border-slate-800 object-cover shrink-0" />
                    <div class="space-y-3">
                        <span class="text-4xl font-black bg-gradient-to-r from-brand-500 via-orange-400 to-cyber-pink bg-clip-text text-transparent font-space uppercase italic tracking-tighter block leading-none">VNTech</span>
                        <p class="text-xs text-slate-400 leading-relaxed max-w-md">
                            Nhà cung cấp máy tính, laptop và linh kiện phần cứng thế hệ mới hàng đầu. Mang lại hiệu năng đỉnh cao cho mọi tác vụ gaming và thiết kế chuyên nghiệp.
                        </p>
                    </div>
                </div>

                <!-- Column 2: Contact & Showroom -->
                <div class="space-y-4 md:text-right flex flex-col justify-center">
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 md:text-right font-space">Thông tin liên hệ</h4>
                    <ul class="space-y-3.5 text-xs text-slate-400 font-sans">
                        <li class="flex items-start md:flex-row-reverse md:justify-start gap-2.5 text-left md:text-right">
                            <i data-lucide="map-pin" class="w-4 h-4 text-brand-500 shrink-0 mt-0.5"></i>
                            <span>Công ty TNHH Công nghệ VNTech, Quận 5, TP. Hồ Chí Minh</span>
                        </li>
                        <li class="flex items-center md:flex-row-reverse md:justify-start gap-2.5">
                            <i data-lucide="phone" class="w-4 h-4 text-brand-500 shrink-0"></i>
                            <span class="font-sans">1900 1234 (Hotline chăm sóc)</span>
                        </li>
                        <li class="flex items-center md:flex-row-reverse md:justify-start gap-2.5">
                            <i data-lucide="mail" class="w-4 h-4 text-brand-500 shrink-0"></i>
                            <span class="font-sans">support@vntech.vn</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Section (Copyright centered) -->
            <div class="pt-6 text-center space-y-2">
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    © 2026 VNTech Shop. Bản quyền thuộc về HMDuck, VTTam, NMPTuyn, ZungFan
                </p>
                <p class="text-xs">
                    <a href="https://github.com/hcmue-hmduck/PHP_VNTech_Web" target="_blank" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-white transition-colors duration-300">
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
    <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
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
                timerProgressBar: 'bg-[#ff4f00]'
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
