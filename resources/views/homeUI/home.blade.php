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
                PHẦN CỨNG THẾ HỆ MỚI ĐÃ RA MẮT
            </div>
            <h1 class="font-['Space_Grotesk'] text-[72px] md:text-[90px] uppercase italic tracking-tighter leading-none glow-text font-bold" id="hero-title">
                BỨT PHÁ SỨC MẠNH CÙNG <span class="text-lime-400">VNTECH</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto" id="hero-description">
                Trải nghiệm hiệu năng đỉnh cao cùng hệ sinh thái VNTech mới. Thiết kế tối ưu cho mọi tác vụ đồ họa và chiến game mượt mà.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-4" id="hero-actions">
                <button onclick="document.getElementById('product-grid-section').scrollIntoView({ behavior: 'smooth' })" class="bg-lime-400 text-black px-10 py-4 rounded-none font-bold uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-[0_0_30px_rgba(0,229,91,0.4)]" id="btn-explore">
                    Khám Phá Sản Phẩm
                </button>
            </div>
        </div>
    </section>



    <style>
        #flash-sale-section {
            background: rgba(0, 0, 0, 0.4) !important;
            padding: 80px 0;
        }
        .flash-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1400px;
            margin: 30px auto;
            background: linear-gradient(180deg, #0f1112 0%, #070809 100%);
            border: 3px solid #00ff66;
            border-radius: 28px;
            box-shadow:
                0 4px 15px rgba(0, 255, 102, 0.15),
                0 8px 30px rgba(0, 0, 0, 0.7);
            overflow: visible;
            padding-bottom: 24px;
        }
        .flash-top {
            position: absolute;
            top: -42px;
            left: 50px;
            width: calc(100% - 100px);
            height: 44px;
            background: linear-gradient(90deg, #00ff66, #008833);
            clip-path: polygon(6% 0%, 94% 0%, 97% 20%, 98.5% 60%, 100% 100%, 0% 100%, 1.5% 60%, 3% 20%);
            border-radius: 0;
            z-index: 5;
            opacity: 0.95;
        }
        .flash-label {
            position: absolute;
            top: -55px;
            left: 50%;
            transform: translateX(-50%);
            width: 280px;
            height: 52px;
            background: linear-gradient(180deg, #020304, #0b0d0e);
            border: 2px solid #00ff66;
            border-radius: 18px 18px 8px 8px;
            color: #00ff66;
            font-weight: 900;
            font-size: 22px;
            font-style: italic;
            text-transform: uppercase;
            box-shadow: 0 4px 20px rgba(0,255,102,0.4), inset 0 0 10px rgba(0,255,102,0.1);
            z-index: 10;
            text-shadow: 0 0 12px rgba(0, 255, 102, 0.6);
            letter-spacing: 0.08em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .flash-header {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 30px 0 30px;
            position: relative;
            z-index: 20;
        }
        .date-tabs {
            display: flex;
            gap: 10px;
        }
        .date-tab {
            padding: 0 16px;
            height: 36px;
            background: #121414;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.1);
            color: #888;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .date-tab.active {
            background: #00ff66;
            color: #000;
            border-color: #00ff66;
            box-shadow: 0 0 12px rgba(0, 255, 102, 0.3);
        }
        .countdown {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            font-weight: 900;
            font-size: 16px;
        }
        .time-box {
            width: 34px;
            height: 34px;
            background: #121414;
            color: #00ff66;
            border: 1px solid rgba(0, 255, 102, 0.3);
            border-radius: 6px;
            font-size: 20px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 8px rgba(0, 255, 102, 0.1);
        }
        .time-separator {
            color: #00ff66;
            font-size: 24px;
            font-weight: 900;
            text-shadow: 0 0 5px rgba(0, 255, 102, 0.5);
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
            background: #070809;
            border-radius: 999px;
            color: white;
            font-size: 10px;
            font-weight: 800;
            text-align: center;
            line-height: 16px;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255,255,255,0.05);
            margin-top: 12px;
            width: 100%;
        }
        .sold-progress {
            height: 100%;
            background: linear-gradient(90deg, #008833, #00ff66);
            border-radius: 999px;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
        }
        .sold-text {
            position: relative;
            z-index: 2;
            display: block;
            width: 100%;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
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
            background: rgba(163, 230, 53, 0.2);
            border-radius: 999px;
        }
        .filter-scrollable::-webkit-scrollbar-thumb:hover {
            background: rgba(163, 230, 53, 0.5);
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
            <div class="flash-top"></div>
            <div class="flash-label">
                <svg class="w-6 h-6 text-[#00ff66] fill-[#00ff66] animate-pulse" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <span>FLASHSALE</span>
            </div>

            <div class="flash-header">
                <div class="date-tabs">
                    @foreach($flashSales as $campaign)
                    <button 
                        @click="activeCampaignId = '{{ $campaign->id }}'"
                        :class="activeCampaignId === '{{ $campaign->id }}' ? 'active' : ''"
                        class="date-tab"
                    >
                        {{ $campaign->ten_flash_sales }}
                    </button>
                    @endforeach
                </div>

                <div class="countdown">
                    <span>KẾT THÚC SAU</span>
                    <span class="time-box" x-text="days">00</span>
                    <span class="time-separator">:</span>
                    <span class="time-box" x-text="hours">00</span>
                    <span class="time-separator">:</span>
                    <span class="time-box" x-text="minutes">00</span>
                    <span class="time-separator">:</span>
                    <span class="time-box" x-text="seconds">00</span>
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
                             class="product-item group bg-slate-900/80 border border-white/10 rounded-2xl overflow-hidden hover:border-lime-400/50 hover:-translate-y-1 transition-all duration-300 flex flex-col"
                        >
                            <a href="{{ $item->variant ? route('viewProductDetail', $item->variant->ma_san_pham) : '#' }}"
                               class="aspect-square bg-slate-950 overflow-hidden relative block">
                                <img
                                    class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700"
                                    src="{{ $item->variant->link_anh_bien_the ?? $item->variant->product->link_anh_dai_dien ?? 'https://via.placeholder.com/400' }}"
                                    alt="{{ $item->variant->ten_bien_the ?? 'Sản phẩm Flash Sale' }}"
                                >
                            </a>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h5 class="font-black uppercase text-sm text-white text-center line-clamp-1 group-hover:text-lime-400 transition-colors">
                                        {{ $item->variant->ten_bien_the ?? 'Sản phẩm Flash Sale' }}
                                    </h5>
                                </div>

                                <div class="pt-5 space-y-4">
                                    <div class="text-center">
                                        <span class="text-lime-400 font-black text-2xl">
                                            {{ number_format($item->gia_flash_sale, 0, ',', '.') }}₫
                                        </span>
                                        @if($item->variant)
                                        <span class="text-xs text-slate-500 text-center block line-through mt-1">
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
                                        class="block w-full rounded-xl py-3 bg-lime-400 text-black font-black text-[10px] uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all text-center">
                                         Săn ngay
                                     </a>
                                     <a href="{{ $item->variant ? route('viewProductDetail', $item->variant->ma_san_pham) : '#' }}"
                                        class="block w-full rounded-xl py-3 bg-white/5 border border-white/10 text-white font-black text-[10px] uppercase tracking-widest hover:bg-white/10 active:scale-95 transition-all text-center">
                                         Xem chi tiết
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

    <!-- Product Grid Section -->
    <section class="py-20 px-8 md:px-16 w-full max-w-none flex flex-col lg:flex-row gap-12" id="product-grid-section" x-data="{ activeCategories: [] }">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-80 bg-slate-900 border border-white/5 h-[calc(100vh-140px)] sticky top-24 flex flex-col overflow-hidden rounded-2xl shrink-0" id="sidebar-filters">
            <div class="flex items-center gap-3 px-6 pt-6 pb-4 border-b border-white/10 shrink-0">
                <i data-lucide="filter" class="text-lime-400 w-6 h-6"></i>
                <h3 class="font-bold uppercase tracking-[0.2em] text-base text-lime-400">Lọc Dữ liệu</h3>
            </div>
            
            <div class="space-y-6 filter-scrollable flex-1 pl-6 pr-4 py-6">
                <div>
                    <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-5 tracking-[0.2em]">Danh mục</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 group cursor-pointer" @click="activeCategories = []">
                            <div class="w-4 h-4 border transition-all"
                                 :class="activeCategories.length === 0 ? 'bg-lime-400 border-lime-400' : 'border-white/20'"></div>
                            <span class="text-xs uppercase tracking-tight transition-colors"
                                  :class="activeCategories.length === 0 ? 'text-lime-400 font-bold' : 'text-slate-400 group-hover:text-lime-400'">
                                Tất cả danh mục
                            </span>
                        </li>
                        @foreach($categories as $cat)
                        <li class="flex items-center gap-3 group cursor-pointer" @click="activeCategories.includes('{{ $cat->ma_danh_muc }}') ? activeCategories = activeCategories.filter(id => id !== '{{ $cat->ma_danh_muc }}') : activeCategories.push('{{ $cat->ma_danh_muc }}')">
                            <div class="w-4 h-4 border transition-all"
                                 :class="activeCategories.includes('{{ $cat->ma_danh_muc }}') ? 'bg-lime-400 border-lime-400' : 'border-white/20'"></div>
                            <span class="text-xs uppercase tracking-tight transition-colors"
                                  :class="activeCategories.includes('{{ $cat->ma_danh_muc }}') ? 'text-lime-400 font-bold' : 'text-slate-400 group-hover:text-lime-400'">
                                {{ $cat->ten_danh_muc }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-5 tracking-[0.2em]">Khoảng giá</h4>
                    <input class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-lime-400" max="100000000" min="0" step="1000000" type="range"/>
                    <div class="flex justify-between mt-2 text-[10px] font-mono text-slate-500">
                        <span>0đ</span>
                        <span>100.000.000đ+</span>
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
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl p-1 overflow-hidden shrink-0 border border-white/10 relative {{ !empty($brand->logo_url) ? 'bg-white' : 'bg-white/5' }}">
                                @if(!empty($brand->logo_url))
                                    <img
                                        src="{{ $brand->logo_url }}"
                                        alt="{{ $brand->ten_thuong_hieu }}"
                                        class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110"
                                        onerror="this.style.display='none'; this.parentNode.classList.remove('bg-white'); this.parentNode.classList.add('bg-white/5'); this.nextElementSibling.style.display='flex';"
                                    >
                                    <span class="text-xs font-black uppercase text-lime-400 hidden">
                                        {{ substr($brand->ten_thuong_hieu, 0, 1) }}
                                    </span>
                                @else
                                    <span class="text-xs font-black uppercase text-lime-400 flex">
                                        {{ substr($brand->ten_thuong_hieu, 0, 1) }}
                                    </span>
                                @endif
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
                
                <button @click="activeCategories = []" class="w-full py-4 border border-lime-400 text-lime-400 text-xs font-bold uppercase tracking-widest hover:bg-lime-400/10 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Xóa Bộ Lọc
                </button>
            </div>
        </aside>

        <!-- Main Grid -->
        <div class="flex-1 space-y-12">

            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-black uppercase tracking-tight text-white flex items-center gap-3">
                        <span class="w-2.5 h-8 bg-lime-400 inline-block"></span>
                        Danh sách Sản phẩm
                    </h2>
                    <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">
                        Có tất cả {{ count($products) }} sản phẩm đang sẵn có
                    </p>
                </div>

                <div class="flex items-center gap-2 bg-white/5 px-4 py-2 border border-white/10 rounded-xl">
                    <span class="text-[10px] uppercase text-slate-400 font-black tracking-wider">Sắp xếp:</span>
                    <div class="flex items-center gap-1 text-[10px] text-lime-400 font-black uppercase cursor-pointer tracking-wider">
                        Mới nhất
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            @foreach($categories as $category)
                @php
                    $categoryProducts = $products->where('ma_danh_muc', $category->ma_danh_muc);
                @endphp

                @if($categoryProducts->count() > 0)
                    <section class="category-section mb-16" data-category="{{ $category->ma_danh_muc }}" x-show="activeCategories.length === 0 || activeCategories.includes('{{ $category->ma_danh_muc }}')" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <!-- Solid Category Header Bar (VNTech themed) -->
                        <div class="flex items-center justify-between bg-lime-400 text-black px-6 py-3.5 rounded-2xl mb-8 shadow-lg shadow-lime-400/10 border border-lime-500/20">
                            <div class="flex items-center gap-3">
                                <span class="bg-black text-lime-400 text-[10px] font-black px-2.5 py-1 uppercase tracking-widest rounded-lg">
                                    VNTech
                                </span>
                                <h3 class="font-['Space_Grotesk'] text-base md:text-lg font-black uppercase tracking-wider">
                                    {{ $category->ten_danh_muc }}
                                </h3>
                            </div>
                            
                            <a href="#" class="text-[10px] font-black uppercase tracking-widest hover:opacity-80 transition-opacity flex items-center gap-1.5 border-b-2 border-black/80 pb-0.5">
                                Tất cả sản phẩm <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>

                        <!-- Responsive Product Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 product-list">
                            @foreach($categoryProducts as $product)
                                <div class="product-item group bg-[#111313]/60 border border-white/5 hover:border-lime-400/30 rounded-2xl overflow-hidden transition-all duration-300 flex flex-col relative">
                                    
                                    <!-- Product Image Box -->
                                    <a href="{{ route('viewProductDetail', $product->ma_san_pham) }}"
                                       class="aspect-square bg-slate-950/80 flex items-center justify-center p-6 border-b border-white/5 overflow-hidden">
                                        <img
                                            class="w-full h-full object-contain group-hover:scale-105 transition-all duration-700"
                                            src="{{ $product->link_anh_dai_dien ?? 'https://via.placeholder.com/400' }}"
                                            alt="{{ $product->ten_san_pham }}"
                                        >
                                    </a>

                                    <!-- Product Info Details (Centered) -->
                                    <div class="p-5 flex-1 flex flex-col justify-between items-center text-center gap-4">
                                        <div class="space-y-2 text-center">
                                            <a href="{{ route('viewProductDetail', $product->ma_san_pham) }}">
                                                <h5 class="font-['Space_Grotesk'] font-bold text-xs md:text-sm text-slate-200 group-hover:text-lime-400 transition-colors uppercase line-clamp-2 leading-snug text-center">
                                                    {{ $product->ten_san_pham }}
                                                </h5>
                                            </a>
                                            <p class="text-[10px] text-slate-500 line-clamp-2 text-center">
                                                {{ $product->mo_ta_ngan }}
                                            </p>
                                        </div>

                                        <div class="space-y-3 text-center w-full">
                                            <div class="flex items-baseline justify-center gap-1.5 w-full">
                                                <span class="text-lime-400 font-bold text-sm md:text-base text-center">
                                                    Chỉ từ {{ number_format($product->gia_thap_nhat, 0, ',', '.') }}₫
                                                </span>
                                            </div>
                                            <a href="{{ route('viewProductDetail', $product->ma_san_pham) }}" 
                                               class="inline-flex items-center justify-center gap-2 w-full py-2 bg-lime-400 hover:bg-lime-500 text-black font-black uppercase text-[10px] tracking-widest transition-all duration-300 rounded-xl hover:scale-[1.03] active:scale-[0.97] shadow-[0_0_15px_rgba(163,230,53,0.1)]">
                                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> Mua ngay
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

    <!-- Mobile Nav Mockup -->
    <nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-20 bg-slate-950/90 backdrop-blur-lg border-t border-lime-400/20 md:hidden px-4" id="mobile-nav">
        <button class="flex flex-col items-center justify-center text-lime-400">
            <i data-lucide="bolt" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Trang chủ</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-500">
            <i data-lucide="gamepad-2" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Cửa hàng</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-500">
            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Giỏ hàng</span>
        </button>
        <button class="flex flex-col items-center justify-center text-slate-500">
            <i data-lucide="user" class="w-6 h-6"></i>
            <span class="text-[8px] font-bold uppercase mt-1">Tài khoản</span>
        </button>
    </nav>
</div>

<!-- Scripts for Lucide Icons & Timer -->
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@vite(['resources/js/home.js'])

@endsection
