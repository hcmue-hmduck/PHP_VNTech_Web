<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VNTech Admin')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#0a0a0a',
                        'surface': '#141414',
                        'surface-high': '#1f1f1f',
                        'neon-green': '#00e55b',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains+Mono', 'monospace'],
                    },
                }
            }
        }
    </script>
    
    <style>
        @layer utilities {
            .glass-panel {
                background: rgba(31, 31, 31, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .neon-glow {
                box-shadow: 0 0 15px rgba(0, 229, 91, 0.3);
            }
            .neon-text-glow {
                text-shadow: 0 0 10px rgba(0, 229, 91, 0.5);
            }
        }
        
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f1f1f;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2a2a2a;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-dark-bg text-gray-200 overflow-hidden font-sans">

    <div class="flex h-screen w-full overflow-hidden">
        <!-- SIDEBAR -->
        <aside class="w-72 flex flex-col bg-surface border-r border-white/5 h-full z-20">
            <div class="p-6 flex flex-col h-full">
                <!-- Brand -->
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 rounded-full overflow-hidden border border-neon-green/30 bg-surface-high p-1">
                        <img 
                            alt="VNTech Logo" 
                            className="w-full h-full object-cover rounded-full" 
                            src="https://lh3.googleusercontent.com/aida/ADBb0uhanRuLJfnvA2jjEGxpLSacersyQ3FNxZGF9MNqkePAdiYmnbGs6zM1qzw_PTIAS7HsGtzUCOabNl13qMn9IaEqlWyGlAwE1k2DDWQohWP1ZsC_xOSY9voC4cWC58skHBix4UcNj70mU-ddPjdgyrRiVGq3zE19TWt8fkxRUxSB6jgqYcGyJAxtCay_kI6odunrqMDYwkJWH9zOdSQ463u-lsxeow54m7ip0o7lMA4JG5weFKGPG8TQwMU6"
                        />
                    </div>
                    <div class="flex flex-col">
                        <h1 class="font-display text-lg font-bold tracking-tight text-white uppercase leading-none">VNTech Admin</h1>
                        <p class="text-[10px] font-mono text-neon-green opacity-70 tracking-widest uppercase mt-1">v2.0.4 Terminal</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex flex-col gap-2">
                    @php
                        $navItems = [
                            ['name' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'admin.dashboard.index', 'active' => 'admin.dashboard*'],
                            ['name' => 'Sản phẩm', 'icon' => 'package', 'route' => 'admin.products.index', 'active' => 'admin.products*'],
                            ['name' => 'Hãng / Danh mục', 'icon' => 'layers', 'route' => 'admin.brandscategories.index', 'active' => 'admin.brandscategories*'],
                            ['name' => 'Đơn hàng', 'icon' => 'shopping-cart', 'route' => 'admin.order.index', 'active' => 'admin.order*'],
                            ['name' => 'Flash Sales', 'icon' => 'zap', 'route' => 'admin.flashsales.index', 'active'=>'admin.flashsales*'],
                            ['name' => 'Voucher', 'icon' => 'ticket', 'route' => 'admin.voucher.view', 'active' => 'admin.voucher*'],
                            ['name' => 'Người dùng', 'icon' => 'users', 'route' => 'admin.user.view', 'active' => 'admin.user*'],
                            ['name' => 'Banner quảng cáo', 'icon' => 'image', 'route' => 'admin.banners'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        @php $hasRoute = Route::has($item['route']); @endphp
                        <a href="{{ $hasRoute ? route($item['route']) : '#' }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs($item['active'] ?? $item['route']) ? 'bg-neon-green/10 border border-neon-green/20 text-neon-green' : 'hover:bg-surface-high/50 text-gray-400 hover:text-white' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="size-5 {{ request()->routeIs($item['route']) ? 'text-neon-green' : 'text-gray-500 group-hover:text-gray-300' }}"></i>
                            <span class="text-sm font-medium tracking-wide">{{ $item['name'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <!-- Profile Footer -->
                <div class="border-t border-white/5 pt-6 mt-auto">
                    <!-- Nút Quay lại Trang chủ dài -->
                    <a href="{{ route('viewHome') }}" class="flex items-center justify-center gap-2 w-full py-3 mb-4 rounded-xl bg-neon-green/10 border border-neon-green/20 text-neon-green hover:bg-neon-green/20 font-bold text-xs uppercase tracking-widest transition-all duration-300">
                        <i data-lucide="home" class="size-4"></i>
                        <span>Quay lại Trang chủ</span>
                    </a>
                    
                    <div class="flex items-center gap-3 p-3 bg-surface-high rounded-xl border border-white/5">
                        <div class="size-10 rounded-lg overflow-hidden bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                            <img src="{{ Auth::user()->avatar_url }}" alt="avatar" class="w-full h-full object-contain">
                        </div>
                        <div class="flex flex-col flex-1">
                            <span class="text-[9px] text-gray-500 uppercase tracking-widest leading-none">Xin chào,</span>
                            <span class="text-sm font-bold text-white mt-1">{{ Auth::user()->ho_ten }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 flex items-center">
                            @csrf
                            <button type="submit"
                                    class="flex items-center justify-center size-8 bg-red-500/10 text-red-500 rounded-lg cursor-pointer hover:bg-red-500/20 transition-colors" 
                                    title="Đăng xuất">
                                <i data-lucide="power" class="size-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col h-full overflow-y-auto relative">

            <!-- CONTENT AREA -->
            <div class="pt-6 pb-12 px-12 space-y-8 w-full">
                @yield('content')
            </div>
        </main>
    </div>
    


    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>