@extends('layouts.app')

@section('title', 'VNTECH | Laptop, PC, Linh kiện máy tính giá rẻ')

@section('content')
<div class="bg-[#121414] text-white font-['Inter'] selection:bg-lime-400 selection:text-black min-h-screen">
    @vite(['resources/css/home.css'])


    <!-- Hero Section -->
    <section class="relative h-[870px] flex flex-col items-center justify-center text-center px-4 overflow-hidden" id="hero-section">
        <div class="absolute inset-0 z-0">
            <video autoplay loop muted playsinline class="w-full h-full object-cover opacity-40">
                <source src="{{ asset('category-page-blade-family-2025-main-web-kv-loop-nocompress.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-t from-[#121414] via-transparent to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-transparent to-transparent"></div>
        </div>
        <div class="relative z-10 space-y-8 max-w-5xl">
            <div class="inline-block px-4 py-1 border border-lime-400/30 rounded-full bg-lime-400/10 text-lime-400 text-xs font-bold tracking-[0.2em] uppercase mb-4 transition-all hover:bg-lime-400/20" id="hero-badge">
                NEXT GEN HARDWARE HAS ARRIVED
            </div>
            <h1 class="font-['Space_Grotesk'] text-[72px] md:text-[90px] uppercase italic tracking-tighter leading-none glow-text font-bold" id="hero-title">
                UNLEASH THE <span class="text-lime-400">VNTECH</span> VORTEX
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto" id="hero-description">
                Experience elite-tier performance with the new VNTech ecosystem. Engineered for those who demand precision and power in every frame.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-4" id="hero-actions">
                <button class="bg-lime-400 text-black px-10 py-4 rounded-none font-bold uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-[0_0_30px_rgba(0,229,91,0.4)]" id="btn-explore">
                    Explore Lineup
                </button>
                <button class="border border-lime-400 text-lime-400 px-10 py-4 rounded-none font-bold uppercase tracking-widest hover:bg-lime-400/10 transition-all" id="btn-tech-specs">
                    Technical Specs
                </button>
            </div>
        </div>
    </section>

    <!-- Tin Công Nghệ Section -->
    <section class="w-full bg-[#0d0f0f] border-y border-lime-400/10 py-20" id="tech-news">
        <div class="max-w-[1600px] mx-auto px-16">
            <div class="flex justify-between items-end mb-12">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-8 bg-lime-400"></div>
                        <h2 class="font-['Space_Grotesk'] text-4xl font-bold uppercase tracking-tighter">Tin Công Nghệ</h2>
                    </div>
                    <p class="text-slate-500 text-xs uppercase tracking-widest">Cập nhật những xu hướng phần cứng mới nhất từ VNTech</p>
                </div>
                <button class="text-xs font-bold text-lime-400 uppercase tracking-[0.2em] hover:opacity-70 transition-all flex items-center gap-2">
                    Tất cả tin tức <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- News Card 1 -->
                <div class="group relative bg-[#111313] border border-white/8 overflow-hidden transition-all hover:border-lime-400/50 hover:-translate-y-2">
                    <div class="h-64 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&q=80&w=800" alt="Card Đồ Họa">
                    </div>
                    <div class="p-6 space-y-4">
                        <span class="text-[10px] text-lime-400/70 font-bold uppercase tracking-widest">Hardware</span>
                        <h3 class="font-['Space_Grotesk'] text-2xl font-bold uppercase text-lime-400">Card Đồ Họa Vortex</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">VNTech ra mắt dòng card đồ họa Vortex series mới với hiệu năng vượt trội, hỗ trợ Ray Tracing 2.0 cực đỉnh.</p>
                        <div class="pt-2">
                            <button class="text-xs font-bold tracking-widest text-lime-400 uppercase border-b border-lime-400 pb-1 group-hover:pr-4 transition-all flex items-center gap-2">
                                Xem chi tiết <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- News Card 2 -->
                <div class="group relative bg-[#111313] border border-white/8 overflow-hidden transition-all hover:border-lime-400/50 hover:-translate-y-2">
                    <div class="h-64 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=800" alt="VNTech Expo">
                    </div>
                    <div class="p-6 space-y-4">
                        <span class="text-[10px] text-lime-400/70 font-bold uppercase tracking-widest">Events</span>
                        <h3 class="font-['Space_Grotesk'] text-2xl font-bold uppercase text-lime-400">VNTech Expo 2025</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">Sự kiện công nghệ lớn nhất năm 2025, nơi hội tụ các siêu phẩm PC Custom độc bản và giải đấu Esport rực lửa.</p>
                        <div class="pt-2">
                            <button class="text-xs font-bold tracking-widest text-lime-400 uppercase border-b border-lime-400 pb-1 group-hover:pr-4 transition-all flex items-center gap-2">
                                Xem chi tiết <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- News Card 3 -->
                <div class="group relative bg-[#111313] border border-white/8 overflow-hidden transition-all hover:border-lime-400/50 hover:-translate-y-2">
                    <div class="h-64 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=800" alt="Cryo Cooling">
                    </div>
                    <div class="p-6 space-y-4">
                        <span class="text-[10px] text-lime-400/70 font-bold uppercase tracking-widest">Guide</span>
                        <h3 class="font-['Space_Grotesk'] text-2xl font-bold uppercase text-lime-400">Cryo-Cooling Pro</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">Hướng dẫn tối ưu hóa tản nhiệt Cryo-Cooling Pro cho PC Gaming, giữ nhiệt độ luôn ở mức sub-zero cả ngày.</p>
                        <div class="pt-2">
                            <button class="text-xs font-bold tracking-widest text-lime-400 uppercase border-b border-lime-400 pb-1 group-hover:pr-4 transition-all flex items-center gap-2">
                                Xem chi tiết <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($flashSaleItems) && $flashSaleItems->count() > 0)
    <!-- Flash Sale Section -->
    <section class="py-20 bg-black/40" id="flash-sale-section" x-data="timer()">
        <div class="max-w-[1600px] mx-auto px-8 md:px-16">
            <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-8">
                <div class="space-y-2">
                    <div class="flex items-center gap-4">
                        <i data-lucide="bolt" class="text-lime-400 fill-lime-400 w-10 h-10"></i>
                        <h2 class="font-['Space_Grotesk'] text-5xl font-bold uppercase italic tracking-tighter">FLASH SALE</h2>
                    </div>
                    <p class="text-slate-500 uppercase tracking-widest text-xs">LIMITED TIME OFFERS ON ELITE GEAR</p>
                </div>
                <div class="flex gap-4" id="countdown-timer">
                    <template x-for="(val, label) in timeLeft">
                        <div class="bg-[#282a2b] p-4 border border-white/10 text-center min-w-[80px]">
                            <div class="text-2xl font-bold font-['Space_Grotesk']" x-text="val"></div>
                            <div class="text-[10px] text-slate-500 uppercase" x-text="label"></div>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($flashSaleItems as $item)
                <div class="bg-slate-900 p-2 border border-white/5 relative group hover:scale-[1.02] transition-all duration-300">
                    @php
                        $phanTramGiam = round(100 - ($item->gia_flash_sale / $item->variant?->gia_niem_yet * 100));
                    @endphp
                    <div class="absolute top-4 left-4 bg-red-600 text-white text-[10px] px-2 py-1 font-bold z-10 uppercase">
                        -{{ $phanTramGiam }}% OFF
                    </div>
                    <img class="w-full aspect-square object-cover mb-4 grayscale group-hover:grayscale-0 transition-all duration-500"
                         src="{{ $item->variant?->link_anh_bien_the ?? $item->variant?->product?->link_anh_dai_dien ?? 'https://via.placeholder.com/400' }}"
                         alt="{{ $item->variant?->product?->ten_san_pham ?? 'Sản phẩm Flash Sale' }}">
                    <div class="px-2 pb-4 space-y-3">
                        <h4 class="font-bold uppercase tracking-tight truncate text-sm text-center">
                            {{ $item->variant?->product?->ten_san_pham ?? 'Sản phẩm Flash Sale' }}
                        </h4>
                        <div class="flex justify-center items-center gap-3">
                            <span class="text-lime-400 font-black text-2xl">{{ number_format($item->gia_flash_sale, 0, ',', '.') }}₫</span>
                            <span class="text-slate-500 text-base line-through italic">{{ number_format($item->variant?->gia_niem_yet, 0, ',', '.') }}₫</span>
                        </div>
                        <button class="w-full py-3 bg-lime-400 text-black font-bold text-xs uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all">MUA NGAY</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Product Grid Section -->
    <section class="py-20 px-8 md:px-16 max-w-[1600px] mx-auto flex flex-col md:flex-row gap-12" id="product-grid-section">
        <!-- Sidebar Filters -->
        <aside class="w-full md:w-80 bg-slate-900 border border-white/5 p-8 h-fit sticky top-24" id="sidebar-filters">
            <div class="flex items-center gap-3 mb-8 border-b border-white/10 pb-4">
                <i data-lucide="filter" class="text-lime-400 w-6 h-6"></i>
                <h3 class="font-bold uppercase tracking-[0.2em] text-base text-lime-400">Lọc Dữ liệu</h3>
            </div>
            
            <div class="space-y-8">
                <div>
                    <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-5 tracking-[0.2em]">Danh mục</h4>
                    <ul class="space-y-3">
                        @foreach($categories as $idx => $cat)
                        <li class="flex items-center gap-3 group cursor-pointer">
                            <div class="w-4 h-4 border {{ $idx === 0 ? 'bg-lime-400 border-lime-400' : 'border-white/20' }} transition-all"></div>
                            <span class="text-xs uppercase tracking-tight group-hover:text-lime-400">{{ $cat->ten_danh_muc }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-5 tracking-[0.2em]">Khoảng giá</h4>
                    <input class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-lime-400" max="5000" min="0" step="100" type="range"/>
                    <div class="flex justify-between mt-2 text-[10px] font-mono text-slate-500">
                        <span>$0</span>
                        <span>$5,000+</span>
                    </div>
                </div>

                <div>
                <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-5 tracking-[0.2em]">
                    Thương hiệu
                </h4>

                <div class="grid grid-cols-1 gap-3">
                    @foreach($brands as $brand)
                        <button
                            class="group flex items-center gap-3 w-full rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3 transition-all duration-300 hover:border-lime-400 hover:bg-lime-400/10"
                        >
                            <div class="w-10 h-10 flex items-center justify-center bg-white rounded-md p-1">
                                <img
                                    src="{{ $brand->logo_url }}"
                                    alt="{{ $brand->ten_thuong_hieu }}"
                                    class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110"
                                >
                            </div>

                            <div class="flex flex-col text-left">
                                <span class="text-[11px] font-bold uppercase tracking-[0.18em] text-white">
                                    {{ $brand->ten_thuong_hieu }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
                
                <button class="w-full py-4 border border-lime-400 text-lime-400 text-xs font-bold uppercase tracking-widest hover:bg-lime-400/10 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Xóa Bộ Lọc
                </button>
            </div>
        </aside>

        <!-- Main Grid -->
        <div class="flex-1 space-y-12">

            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tight text-white">
                        Sản phẩm
                    </h2>
                    <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">
                        Showing {{ count($products) }} High-Performance Models
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs uppercase text-slate-500 font-bold">Sort:</span>
                    <div class="flex items-center gap-1 text-xs text-lime-400 font-bold uppercase cursor-pointer">
                        Newest First
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            @foreach($categories as $category)
                @php
                    $categoryProducts = $products->where('ma_danh_muc', $category->ma_danh_muc);
                @endphp

                @if($categoryProducts->count() > 0)
                    <section class="category-section mb-14" data-category="{{ $category->ma_danh_muc }}">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-black uppercase tracking-widest text-white">
                                    {{ $category->ten_danh_muc }}
                                </h3>
                                <div class="mt-2 h-[2px] w-16 bg-lime-400"></div>
                            </div>

                            <span class="text-[10px] uppercase tracking-[0.25em] text-slate-500 font-bold">
                                {{ $categoryProducts->count() }} sản phẩm
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 product-list">
                            @foreach($categoryProducts as $product)
                                <div class="product-item group bg-slate-900/80 border border-white/10 rounded-2xl overflow-hidden hover:border-lime-400/50 hover:-translate-y-1 transition-all duration-300 flex flex-col">

                                    <a href="{{ route('viewProductDetail', $product->ma_san_pham) }}"
                                    class="aspect-square bg-slate-950 overflow-hidden relative block">
                                        <img
                                            class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700"
                                            src="{{ $product->link_anh_dai_dien ?? 'https://via.placeholder.com/400' }}"
                                            alt="{{ $product->ten_san_pham }}"
                                        >
                                    </a>

                                    <div class="p-5 flex-1 flex flex-col justify-between">
                                        <div>
                                            <h5 class="font-black uppercase text-sm text-white text-center line-clamp-1 group-hover:text-lime-400 transition-colors">
                                                {{ $product->ten_san_pham }}
                                            </h5>

                                            <p class="text-xs text-slate-500 text-center mt-3 line-clamp-2">
                                                {{ $product->mo_ta_ngan }}
                                            </p>
                                        </div>

                                        <div class="pt-5 space-y-4">
                                            <div class="text-center">
                                                <span class="text-lime-400 font-black text-2xl">
                                                    {{ number_format($product->gia_thap_nhat, 0, ',', '.') }}₫
                                                </span>
                                            </div>

                                            <a href="{{ route('viewProductDetail', $product->ma_san_pham) }}"
                                            class="block w-full rounded-xl py-3 bg-lime-400 text-black font-black text-[10px] uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all text-center">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pagination mt-8 flex justify-center gap-2"></div>
                    </section>
                @endif
            @endforeach

        </div>
    </section>

    <!-- Mobile Nav Mockup -->
    <nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-20 bg-slate-950/90 backdrop-blur-lg border-t border-lime-400/20 md:hidden px-4" id="mobile-nav">
        <button class="flex flex-col items-center justify-center text-lime-400">
            <i data-lucide="bolt" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Home</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-500">
            <i data-lucide="gamepad-2" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Shop</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-500">
            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Cart</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-500">
            <i data-lucide="user" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">User</span>
        </button>
    </nav>
</div>

<!-- Scripts for Lucide Icons & Timer -->
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@vite(['resources/js/home.js'])

@endsection
