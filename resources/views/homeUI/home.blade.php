@extends('layouts.app')

@section('title', 'VNTECH | Laptop, PC, Linh kiện máy tính giá rẻ')

@section('content')
@php
    $activeBanners = [];
    if (isset($banner_images)) {
        foreach ($banner_images as $b) {
            if (($b->trang_thai ?? '') !== 'inactive' && ($b->trang_thai ?? '') !== 'deleted') {
                $activeBanners[] = [
                    'image' => $b->image_url,
                    'url' => $b->lien_ket ?? '/products',
                    'title' => $b->tieu_de,
                    'desc' => $b->mo_ta
                ];
            }
        }
    }
@endphp
<div class="bg-[#FAF8F2] text-slate-800 font-['Inter'] selection:bg-brand-500/20 selection:text-brand-500 min-h-screen" x-data="{ activeCategories: [] }">
    @vite(['resources/css/home.css'])


    <!-- HERO BANNER SLIDER with robust layout match -->
    <script>
        window.homeBanners = <?php echo json_encode($activeBanners ?? []); ?>;
    </script>
    <section class="max-w-[1400px] mx-auto px-4 sm:px-8 pt-6" x-data="{
        activeSlide: 0,
        banners: window.homeBanners || [],
        init() {
            if (this.banners.length > 0) {
                setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.banners.length;
                }, 8500);
            }
        }
    }">
        <div class="relative bg-white rounded-[32px] border border-slate-100 overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.04)] w-full h-[400px] sm:h-[520px] md:h-[600px] lg:h-[720px] flex items-center transition-all duration-500">
            <!-- Slides Wrapper -->
            <div class="w-full h-full relative">
                <template x-for="(banner, index) in banners" :key="index">
                    <div x-show="activeSlide === index"
                         x-transition:enter="transition transform ease-out duration-700 absolute inset-0"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition transform ease-in duration-700 absolute inset-0"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full"
                         class="w-full h-full">
                        <a :href="banner.url" class="block w-full h-full group relative">
                            <img :src="banner.image" 
                                 alt="VNTech Promotion Banner" 
                                 class="w-full h-full object-cover group-hover:scale-[1.01] transition-transform duration-700" 
                                 referrerpolicy="no-referrer" />
                            
                            <!-- Dark Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/35 to-transparent z-10"></div>
                            
                            <!-- Banner Text Content Overlay -->
                            <div class="absolute inset-y-0 left-0 pl-6 sm:pl-12 md:pl-16 pr-8 flex flex-col justify-center z-20 max-w-[80%] sm:max-w-[70%] text-left select-none">
                                <h3 x-text="banner.title" class="text-base sm:text-2xl md:text-3xl lg:text-4xl font-black text-white uppercase tracking-wide drop-shadow-md leading-tight mb-1 sm:mb-2 md:mb-4"></h3>
                                <p x-text="banner.desc" class="text-[9px] sm:text-xs md:text-sm text-gray-200 font-medium line-clamp-2 leading-relaxed max-w-xs sm:max-w-md lg:max-w-lg"></p>
                                <div class="mt-2 sm:mt-4 md:mt-6">
                                    <span class="inline-flex items-center gap-1.5 sm:gap-2 bg-brand-500 text-white px-3 py-1.5 sm:px-4 sm:py-2 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-lg">
                                        <span>Xem ngay</span>
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </template>
            </div>

            <!-- Slider Arrows -->
            <button @click="activeSlide = (activeSlide - 1 + banners.length) % banners.length"
                    class="absolute left-4 top-1/2 -translate-y-1/2 hidden md:flex items-center justify-center bg-white/90 hover:bg-white text-neutral-800 hover:text-brand-500 w-12 h-12 rounded-full shadow-md border border-neutral-200 transition-all duration-300 z-30 focus:outline-none focus:ring-2 focus:ring-brand-500/50 group"
                    title="Slide trước">
                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button @click="activeSlide = (activeSlide + 1) % banners.length"
                    class="absolute right-4 top-1/2 -translate-y-1/2 hidden md:flex items-center justify-center bg-white/90 hover:bg-white text-neutral-800 hover:text-brand-500 w-12 h-12 rounded-full shadow-md border border-neutral-200 transition-all duration-300 z-30 focus:outline-none focus:ring-2 focus:ring-brand-500/50 group"
                    title="Slide sau">
                <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- Slider Dot Indicators -->
            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-3 z-20">
                <template x-for="(banner, index) in banners" :key="index">
                    <button @click="activeSlide = index"
                            class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="activeSlide === index ? 'bg-brand-500 w-8' : 'bg-neutral-300 hover:bg-neutral-400'"
                            :title="'Trang ' + (index + 1)"></button>
                </template>
            </div>
        </div>
    </section>

    <!-- TRUST FEATURE BADGES -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Badge 1 -->
            <div class="flex items-center gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.035)] hover:-translate-y-1 transition-all duration-300 text-left">
                <div class="p-3 bg-brand-50 text-brand-500 rounded-xl">
                    <i data-lucide="truck" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-neutral-900 text-sm">Miễn phí vận chuyển</h4>
                    <p class="text-xs text-neutral-500">Đơn hàng từ 500.000₫</p>
                </div>
            </div>
            <!-- Badge 2 -->
            <div class="flex items-center gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.035)] hover:-translate-y-1 transition-all duration-300 text-left">
                <div class="p-3 bg-brand-50 text-brand-500 rounded-xl">
                    <i data-lucide="refresh-cw" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-neutral-900 text-sm">Đổi trả dễ dàng</h4>
                    <p class="text-xs text-neutral-500">Trong vòng 7 ngày</p>
                </div>
            </div>
            <!-- Badge 3 -->
            <div class="flex items-center gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.035)] hover:-translate-y-1 transition-all duration-300 text-left">
                <div class="p-3 bg-brand-50 text-brand-500 rounded-xl">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-neutral-900 text-sm">Thanh toán an toàn</h4>
                    <p class="text-xs text-neutral-500">Bảo mật tuyệt đối</p>
                </div>
            </div>
            <!-- Badge 4 -->
            <div class="flex items-center gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.035)] hover:-translate-y-1 transition-all duration-300 text-left">
                <div class="p-3 bg-brand-50 text-brand-500 rounded-xl">
                    <i data-lucide="phone-call" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-neutral-900 text-sm">Hỗ trợ 24/7</h4>
                    <p class="text-xs text-neutral-500">Hotline: 1900 1234</p>
                </div>
            </div>
        </div>
    </section>

    <!-- RECTANGULAR BUBBLES CATEGORIES SHOWCASE AREA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-4">
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
            <div class="flex items-center justify-between mb-8 pb-3 border-b border-slate-100">
                <h3 class="font-['Space_Grotesk'] font-black text-xl text-slate-800 border-l-4 border-accent-500 pl-3.5">
                    Danh mục công nghệ hàng đầu
                </h3>
                <a 
                    href="{{ route('home.products') }}"
                    class="text-xs font-bold text-accent-500 hover:text-accent-600 transition-colors duration-300"
                >
                    Xem tất cả và lọc thiết bị &gt;
                </a>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-8 gap-4 text-center">
                @foreach($categories->take(8) as $cat)
                    @php
                        $iconMap = [
                            'Điện thoại' => 'smartphone',
                            'Laptop' => 'laptop',
                            'Máy tính để bàn' => 'monitor',
                            'Linh kiện máy tính' => 'cpu',
                            'Gaming Gear' => 'gamepad-2',
                            'CPU' => 'cpu',
                            'Card đồ họa' => 'layers',
                            'RAM' => 'database',
                            'Ổ cứng SSD' => 'hard-drive',
                            'Bàn phím' => 'keyboard',
                            'Chuột' => 'mouse',
                            'Tai nghe' => 'headphones',
                        ];
                        $icon = $iconMap[$cat->ten_danh_muc] ?? 'cpu';
                    @endphp
                    <a
                        href="{{ route('home.products', ['category' => $cat->ten_danh_muc]) }}"
                        class="flex flex-col items-center gap-2 group outline-none cursor-pointer"
                    >
                        <div class="w-16 h-16 rounded-full border flex items-center justify-center transition-all duration-300 bg-slate-50 border-slate-100 text-slate-600 group-hover:bg-gradient-to-r group-hover:from-brand-500 group-hover:to-brand-600 group-hover:border-transparent group-hover:text-white group-hover:-translate-y-1.5 group-hover:shadow-[0_10px_20px_rgba(255,79,0,0.15)] shadow-xs">
                            <i data-lucide="{{ $icon }}" class="w-5.5 h-5.5"></i>
                        </div>
                        <span class="text-[11px] font-extrabold transition-colors mt-2 text-slate-500 group-hover:text-brand-500">
                            {{ $cat->ten_danh_muc }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        #flash-sale-section {
            background: transparent !important;
            padding: 40px 0;
        }
        .flash-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1400px;
            margin: 30px auto;
            background: radial-gradient(circle at 10% 20%, #0f172a 0%, #070a13 90%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: visible;
            padding-bottom: 24px;
        }
        .flash-top {
            display: none;
        }
        .flash-label {
            display: none;
        }
        .flash-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 30px;
            position: relative;
            z-index: 20;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .date-tabs {
            display: flex;
            gap: 10px;
        }
        .date-tab {
            padding: 0 16px;
            height: 36px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #94a3b8;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }
        .date-tab:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.15);
        }
        .date-tab.active {
            background: linear-gradient(135deg, #ff4f00 0%, #ff007a 100%);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 0 20px rgba(255, 79, 0, 0.35);
        }
        .countdown {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            font-weight: 900;
            font-size: 16px;
        }
        .time-box {
            width: 38px;
            height: 38px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #ff4f00;
            border: 1px solid rgba(255, 79, 0, 0.3);
            border-radius: 8px;
            font-size: 18px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 15px rgba(255, 79, 0, 0.1);
        }
        .time-separator {
            color: rgba(255, 255, 255, 0.5);
            font-size: 20px;
            font-weight: 900;
        }
        .flash-product-list {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            padding: 24px 24px;
            position: relative;
            z-index: 20;
        }
        @media (max-width: 1024px) {
            .flash-product-list {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .flash-product-list {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
                padding: 16px 12px;
            }
        }

        .sold-bar {
            height: 18px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 999px;
            color: #f1f5f9;
            font-size: 10px;
            font-weight: 800;
            text-align: center;
            line-height: 16px;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-top: 12px;
            width: 100%;
        }
        .sold-progress {
            height: 100%;
            background: linear-gradient(90deg, #ff4f00 0%, #ff007a 100%);
            border-radius: 999px;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
            box-shadow: 0 0 8px rgba(255, 79, 0, 0.5);
        }
        .sold-text {
            position: relative;
            z-index: 2;
            display: block;
            width: 100%;
            line-height: 16px;
        }

        .filter-scrollable {
            overflow-y: auto;
        }
        .filter-scrollable::-webkit-scrollbar {
            width: 5px;
        }
        .filter-scrollable::-webkit-scrollbar-track {
            background: transparent;
        }
        .filter-scrollable::-webkit-scrollbar-thumb {
            background: rgba(12, 135, 235, 0.1);
            border-radius: 999px;
        }
        .filter-scrollable::-webkit-scrollbar-thumb:hover {
            background: rgba(12, 135, 235, 0.3);
        }

    </style>

    @if(isset($flashSales) && $flashSales->count() > 0)
    <!-- Flash Sale Section -->
    <section class="" id="flash-sale-section" x-data="{
        activeCampaignId: '{{ $flashSales->first()->id }}',
        campaigns: {
            @foreach($flashSales as $campaign)
                '{{ $campaign->id }}': {
                    end_time: '{{ $campaign->ket_thuc->toIso8601String() }}',
                    start_time_str: '{{ $campaign->bat_dau->format('H') }}H',
                    end_time_str: '{{ $campaign->ket_thuc->format('H') }}H',
                    name: '{{ $campaign->ten_flash_sales }}'
                },
            @endforeach
        },
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        timerInterval: null,

        init() {
            this.startTimer();
            this.$watch('activeCampaignId', () => {
                this.startTimer();
            });
        },

        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            const targetStr = this.campaigns[this.activeCampaignId].end_time;
            const target = new Date(targetStr).getTime();

            this.timerInterval = setInterval(() => {
                const now = new Date().getTime();
                const diff = target - now;

                if (diff <= 0) {
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    return;
                }

                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                this.days = d.toString().padStart(2, '0');
                this.hours = h.toString().padStart(2, '0');
                this.minutes = m.toString().padStart(2, '0');
                this.seconds = s.toString().padStart(2, '0');
            }, 1000);
        }
    }">
        <div class="flash-wrapper">
            <div class="flash-header flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 border-b border-slate-800">
                <div class="flex items-center gap-4 flex-wrap">
                    <h2 class="font-display font-black text-2xl text-white flex items-center gap-2">
                        <i data-lucide="bolt" class="w-6 h-6 text-brand-500 fill-brand-500"></i>
                        FLASH SALE
                    </h2>
                    
                    <div class="countdown flex items-center gap-1.5 font-mono">
                        <span class="time-box" x-text="days">00</span>
                        <span class="time-separator text-white font-extrabold">:</span>
                        <span class="time-box" x-text="hours">00</span>
                        <span class="time-separator text-white font-extrabold">:</span>
                        <span class="time-box" x-text="minutes">00</span>
                        <span class="time-separator text-white font-extrabold">:</span>
                        <span class="time-box" x-text="seconds">00</span>
                    </div>
                </div>

                <div class="date-tabs flex gap-2">
                    @foreach($flashSales as $campaign)
                    <button 
                        @click="activeCampaignId = '{{ $campaign->id }}'"
                        :class="activeCampaignId === '{{ $campaign->id }}' ? 'active' : ''"
                        class="date-tab text-xs font-bold px-3 py-1.5 rounded-full border border-neutral-200 transition-all"
                    >
                        {{ $campaign->ten_flash_sales }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="flash-product-list">
                @foreach($flashSales as $campaign)
                    @foreach($campaign->flash_sale_items as $item)
                        @php
                            $phanTramGiam = $item->variant && $item->variant->gia_niem_yet > 0 
                                ? round(100 - ($item->gia_flash_sale / $item->variant->gia_niem_yet * 100))
                                : 0;
                            $phanTramDaBan = min(100, (($item->so_luong_da_ban ?? 0) / $item->so_luong_gioi_han) * 100);
                            $progressStyle = 'style="width: ' . $phanTramDaBan . '%"';
                        @endphp
                        <div x-show="activeCampaignId === '{{ $campaign->id }}'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="product-item group bg-slate-900/60 border border-slate-800/80 rounded-2xl overflow-hidden hover:border-brand-500/50 hover:-translate-y-1.5 hover:shadow-[0_15px_30px_rgba(255,79,0,0.2)] transition-all duration-300 flex flex-col"
                        >
                            <a href="{{ $item->variant ? route('home.product_detail', ['ma_san_pham' => $item->variant->ma_san_pham, 'ma_bien_the' => $item->variant->ma_bien_the]) : '#' }}"
                               class="aspect-square bg-slate-950/20 flex items-center justify-center p-6 border-b border-slate-800/80 overflow-hidden relative block">
                                @if($phanTramGiam > 0)
                                    <div class="absolute top-2 left-2 bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-md font-bold tracking-wider z-10">
                                        -{{ $phanTramGiam }}%
                                    </div>
                                @endif
                                <img
                                    class="w-full h-full object-contain group-hover:scale-110 transition-all duration-700"
                                    src="{{ $item->variant->link_anh_bien_the ?: ($item->variant->product->link_anh_dai_dien ?: asset('images/no-image.png')) }}"
                                    alt="{{ $item->variant->ten_hien_thi ?? 'Sản phẩm Flash Sale' }}"
                                >
                            </a>

                            <div class="p-5 flex-1 flex flex-col justify-between text-left">
                                <div>
                                    <h5 class="font-black uppercase text-xs text-slate-100 text-center line-clamp-2 group-hover:text-brand-500 transition-colors leading-tight min-h-[32px]">
                                        {{ $item->variant->ten_hien_thi ?? 'Sản phẩm Flash Sale' }}
                                    </h5>
                                    <p class="text-[10px] text-slate-400 line-clamp-2 leading-relaxed text-center mt-1">
                                        {{ $item->variant->product->mo_ta_ngan ?? 'Chưa có mô tả ngắn cho sản phẩm này.' }}
                                    </p>
                                </div>

                                <div class="pt-4 space-y-4">
                                    <div class="text-center">
                                        <span class="text-brand-500 font-black text-xl">
                                            {{ number_format($item->gia_flash_sale, 0, ',', '.') }}₫
                                        </span>
                                        @if($item->variant)
                                        <span class="text-[11px] text-slate-500 text-center block line-through mt-0.5">
                                            {{ number_format($item->variant->gia_niem_yet, 0, ',', '.') }}₫
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Thanh đã bán -->
                                    <div class="sold-bar flex items-center justify-center relative">
                                        <div class="sold-progress" {!! $progressStyle !!}></div>
                                        <span class="sold-text">Đã bán: {{ $item->so_luong_da_ban ?? 0 }}</span>
                                    </div>

                                     <a href="{{ $item->variant ? route('payment.view', $item->variant->ma_bien_the) : '#' }}"
                                        class="block w-full rounded-xl py-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-black text-[10px] uppercase tracking-widest hover:from-brand-600 hover:to-brand-700 hover:shadow-[0_4px_15px_rgba(255,79,0,0.3)] active:scale-95 transition-all text-center">
                                         Săn ngay
                                     </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- 2. BEST SELLERS products showcasing section -->
    <section id="best-sellers" class="max-w-7xl mx-auto px-4 sm:px-8 py-12"
             x-data="{
                selectedCategory: 'all',
                categories: {{ json_encode($bestSellerCategoriesList) }},
                products: {{ json_encode($bestSellerProductsList) }},
                formatVND(value) {
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '₫';
                },
                get bestSellerProducts() {
                    if (this.selectedCategory === 'all') return this.products;
                    return this.products.filter(p => p.ma_danh_muc === this.selectedCategory);
                },
                init() {
                    this.$watch('selectedCategory', () => {
                        this.$nextTick(() => {
                            if (window.lucide) window.lucide.createIcons();
                        });
                    });
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                }
             }">
        <!-- Styled header container block -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.015)] mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1 text-left border-l-4 border-brand-500 pl-3.5">
                <h2 class="font-['Space_Grotesk'] font-black text-2xl text-slate-800 uppercase">
                    SẢN PHẨM BÁN CHẠY
                </h2>
                <p class="text-neutral-400 text-xs font-semibold">Được mua nhiều nhất ở VNTech</p>
            </div>

            <!-- In-tab categorical product filters matched dynamically -->
            <style>
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
            </style>
            <div class="flex items-center flex-nowrap overflow-x-auto gap-1.5 p-1.5 bg-slate-100 rounded-2xl border border-slate-200/10 shadow-xs max-w-full no-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
                <template x-for="cat in categories" :key="cat.id">
                    <button
                        @click="selectedCategory = cat.id"
                        :class="selectedCategory === cat.id ? 'bg-white text-accent-600 shadow-[0_2px_8px_rgba(0,0,0,0.04)]' : 'text-slate-500 hover:text-slate-900'"
                        class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap shrink-0"
                        x-text="cat.name"
                    ></button>
                </template>
            </div>
        </div>

        <!-- Core products grid -->
        <div x-show="bestSellerProducts.length === 0" class="bg-white rounded-3xl p-12 text-center border border-slate-100" style="display: none;">
            <p class="text-xs text-neutral-400 font-bold">Hiện tạm hết sản phẩm thuộc liên kết bán chạy.</p>
        </div>

        <div x-show="bestSellerProducts.length > 0">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <template x-for="product in bestSellerProducts.slice(0, 5)" :key="product.ma_bien_the">
                    <div
                        @click="window.location.href = '/products/' + product.ma_san_pham + '/product-detail/' + (product.ma_bien_the || '')"
                        class="bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.04)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col cursor-pointer group text-left"
                    >
                        <!-- Product Image Panel -->
                        <div class="relative bg-slate-50/50 aspect-square overflow-hidden flex items-center justify-center p-4 border-b border-slate-100/80">
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
                                referrerpolicy="no-referrer"
                            />

                            <!-- Dynamic Badge Overlays -->
                            <div class="absolute top-3 left-3 flex gap-2 z-10">
                                <span
                                    :class="product.promoBg"
                                    class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider shadow-sm"
                                    x-text="product.promoText"
                                ></span>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="p-4 flex flex-col flex-1">
                            <span class="font-sans text-xs text-neutral-400 font-medium tracking-wide mb-1 uppercase" x-text="product.category"></span>
                            <h3 class="font-['Space_Grotesk'] text-base font-semibold text-slate-800 line-clamp-2 group-hover:text-accent-500 transition-colors min-h-[48px]" x-text="product.name"></h3>
                            <p class="text-[11px] text-neutral-400 line-clamp-2 leading-relaxed" x-text="product.mo_ta_ngan"></p>

                            <!-- Rating Block -->
                            <div class="flex items-center gap-1.5 mb-3">
                                <div class="flex items-center">
                                    <template x-for="i in [1, 2, 3, 4, 5]">
                                        <span class="relative inline-block w-3.5 h-3.5 overflow-hidden text-sm leading-none text-gray-200">
                                            ★
                                            <span
                                                class="absolute left-0 top-0 h-full overflow-hidden text-amber-500"
                                                :style="`width: ${Math.max(0, Math.min(100, (Number(product.rating || 0) - (i - 1)) * 100))}%`"
                                            >★</span>
                                        </span>
                                    </template>
                                </div>
                                <span class="text-xs text-neutral-400 font-sans">
                                    (<span x-text="product.reviewsCount"></span>)
                                </span>
                            </div>

                            <!-- Price and CTA Block -->
                            <div class="flex justify-between items-center mt-auto w-full">
                                <div class="text-left">
                                    <span class="text-[11px] text-slate-700 uppercase tracking-wider block font-extrabold leading-none mb-1.5" x-text="'Đã bán: ' + product.da_ban"></span>
                                    <span class="font-['Space_Grotesk'] text-[15px] font-bold text-accent-600 tracking-tight block leading-none" x-text="formatVND(product.price)"></span>
                                </div>
                                <button
                                    @click.stop="window.location.href = '/products/' + product.ma_san_pham + '/product-detail/' + (product.ma_bien_the || '')"
                                    class="w-10 h-10 bg-brand-500 hover:bg-brand-600 hover:shadow-[0_4px_10px_rgba(255,79,0,0.3)] text-white rounded-lg flex items-center justify-center transition-all duration-300 transform active:scale-90"
                                    title="Xem chi tiết"
                                >
                                    <i data-lucide="eye" class="w-5 h-5 text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Mobile Nav Mockup -->
    <nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-20 bg-white/95 backdrop-blur-md border-t border-slate-100 md:hidden px-4 shadow-[0_-4px_12px_rgba(0,0,0,0.03)]" id="mobile-nav">
        <button class="flex flex-col items-center justify-center text-brand-500">
            <i data-lucide="bolt" class="w-5 h-5 fill-brand-500"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Trang chủ</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-400 hover:text-brand-500 transition-colors">
            <i data-lucide="gamepad-2" class="w-5 h-5"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Cửa hàng</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-400 hover:text-brand-500 transition-colors">
            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Giỏ hàng</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-400 hover:text-brand-500 transition-colors">
            <i data-lucide="user" class="w-5 h-5"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Tài khoản</span>
        </button>
    </nav>
</div>

<!-- Scripts for Lucide Icons & Timer -->
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@vite(['resources/js/home.js'])

@endsection
