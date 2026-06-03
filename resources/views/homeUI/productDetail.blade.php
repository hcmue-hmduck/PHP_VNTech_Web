@extends('layouts.app')

@section('title', $productDetail->ten_san_pham)

@section('content')
@php
    $selectedVariant = $variants->firstWhere('ma_bien_the', request('ma_bien_the')) ?? $variants->first();
    $flashSaleInfo = $selectedVariant->flash_sale_info;
    $flashSaleCampaign = $selectedVariant->flash_sale_campaign;
    $isFlashSaleActive = $flashSaleInfo && $flashSaleCampaign;
    
    // Calculate discount specifications
    $daBan = (int)($flashSaleInfo->so_luong_da_ban ?? 0);
    $gioiHan = max(1, (int)($flashSaleInfo->so_luong_gioi_han ?? 1));
    $soLuongFlashConLai = max(0, $gioiHan - $daBan);
    $percent = min(100, round(($daBan / $gioiHan) * 100));
    
    $endTimeStr = '';
    if ($isFlashSaleActive) {
        $endTimeStr = is_string($flashSaleCampaign->ket_thuc) 
            ? $flashSaleCampaign->ket_thuc 
            : ($flashSaleCampaign->ket_thuc instanceof \Carbon\Carbon 
                ? $flashSaleCampaign->ket_thuc->toIso8601String() 
                : (string)$flashSaleCampaign->ket_thuc);
    }

    $originalPrice = $selectedVariant->gia_niem_yet ?: ($selectedVariant->gia_ban * 1.25);
    $currentPrice = $isFlashSaleActive ? $flashSaleInfo->gia_flash_sale : $selectedVariant->gia_ban;
    $savingsPercent = $originalPrice > $currentPrice ? round((($originalPrice - $currentPrice) / $originalPrice) * 100) : 0;
    $tietKiemVal = $originalPrice - $currentPrice;
@endphp

<!-- Custom Styles for Product Detail -->
<style>
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="relative bg-white text-gray-800 font-['Inter'] selection:bg-orange-100 selection:text-[#FF5C00] min-h-screen">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-8 pt-10 pb-20 w-full relative z-10">
        
        <!-- Breadcrumb navigation -->
        <nav class="flex flex-wrap items-center gap-1.5 mb-6 text-xs sm:text-sm text-gray-500 font-medium text-left">
            <a href="/" class="hover:text-[#FF5C00] transition-colors">Trang chủ</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
            <a href="/products" class="hover:text-[#FF5C00] transition-colors">Sản phẩm</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
            <span class="text-gray-900 font-extrabold">{{ $productDetail->ten_san_pham }}</span>
        </nav>

        <!-- Main Product Grid layout (Matches reference layout) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-16">
            
            <!-- Left Column: Gallery (Horizontal thumbs below main image, matching reference) -->
            <div class="lg:col-span-7 space-y-4">
                <div class="w-full bg-[#F5F5F7]/85 rounded-3xl overflow-hidden aspect-square flex items-center justify-center relative border border-neutral-100 group p-4">
                    
                    <!-- Main image -->
                    <img
                        id="main-product-image"
                        src="{{ $selectedVariant->link_anh_bien_the ?: $productDetail->link_anh_dai_dien }}"
                        alt="{{ $productDetail->ten_san_pham }}"
                        class="w-full h-full max-w-full max-h-full object-contain transition-all duration-300"
                    />

                    <!-- Savings discount badge -->
                    @if($savingsPercent > 0)
                        <div class="absolute top-6 left-6 bg-red-500 text-white font-extrabold px-3 py-1.5 rounded-xl text-xs uppercase z-10 shadow-sm">
                            -{{ $savingsPercent }}%
                        </div>
                    @endif

                    <!-- Prev & Next slider buttons -->
                    <button
                        type="button"
                        onclick="prevImage()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white hover:bg-neutral-50 text-gray-800 p-2.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 cursor-pointer border border-neutral-200/50"
                    >
                        <i data-lucide="chevron-left" class="w-5 h-5 text-gray-700"></i>
                    </button>
                    <button
                        type="button"
                        onclick="nextImage()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white hover:bg-neutral-50 text-gray-800 p-2.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 cursor-pointer border border-neutral-200/50"
                    >
                        <i data-lucide="chevron-right" class="w-5 h-5 text-gray-700"></i>
                    </button>
                </div>

                <!-- Gallery Thumbnails directly below (Horizontal & Left-aligned, matching reference) -->
                @php
                    $galleryImages = array_merge([$selectedVariant->link_anh_bien_the ?: $productDetail->link_anh_dai_dien], $productDetail->hinh_anh ?? []);
                @endphp
                @if(count($galleryImages) > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-none justify-start">
                        @foreach($galleryImages as $idx => $img)
                            <button
                                type="button"
                                onclick="changeMainImage(this.dataset.index)"
                                data-index="{{ $idx }}"
                                class="thumb-container w-16 h-16 rounded-xl border p-1 bg-[#F5F5F7] shrink-0 transition-all overflow-hidden flex items-center justify-center cursor-pointer relative {{ $idx === 0 ? 'border-[#FF5C00] ring-2 ring-orange-100' : 'border-neutral-200 hover:border-neutral-400' }}"
                            >
                                <img
                                    src="{{ $img }}"
                                    alt="Thumbnail {{ $idx + 1 }}"
                                    class="w-full h-full object-contain rounded-lg"
                                />
                                <div class="thumb-border absolute inset-0 border-2 border-[#FF5C00] rounded-xl transition-opacity pointer-events-none {{ $idx === 0 ? 'opacity-100' : 'opacity-0' }}"></div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column: Details & actions (White container matching mockup) -->
            <div class="lg:col-span-5 flex flex-col justify-between space-y-6 text-left">
                
                <!-- Product header information -->
                <div class="space-y-4">
                    <!-- Badges (Matching mockup layout) -->
                    <div class="flex gap-2">
                        <span class="bg-orange-50 text-[#FF5C00] px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Trả góp 0%</span>
                        <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Bán chạy</span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $productDetail->ten_san_pham }}
                    </h1>
                    
                    <!-- Rating blocks -->
                    <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-slate-500">
                        <div class="flex text-amber-450">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 fill-current {{ $i > 4 ? 'text-neutral-200 fill-none' : 'text-amber-500' }}"></i>
                            @endfor
                        </div>
                        <span class="font-semibold text-slate-700">
                            <b class="text-slate-950 mr-1">4.8</b>
                            (4,527 đánh giá)
                        </span>
                        <span class="h-4 w-[1px] bg-slate-200"></span>
                        <span class="text-emerald-600 font-bold">Còn hàng</span>
                    </div>

                    <!-- Price Section (Matches layout in attachment) -->
                    <div class="pt-2">
                        <div class="flex items-baseline gap-3">
                            <span class="text-4xl font-extrabold text-[#E04F2A] tracking-tight font-display">
                                {{ number_format($currentPrice, 0, ',', '.') }}₫
                            </span>
                            <span class="text-base text-slate-400 line-through font-semibold">
                                {{ number_format($originalPrice, 0, ',', '.') }}₫
                            </span>
                        </div>

                        <!-- Savings Pink alert box (Matches attachment) -->
                        <div class="bg-[#FFF5F5] border border-[#FFE3E3] text-[#D32F2F] p-3.5 rounded-2xl flex items-center justify-between text-xs font-bold mt-3.5">
                            <span>Tiết kiệm {{ number_format($tietKiemVal, 0, ',', '.') }}₫</span>
                            <span class="text-[#E04F2A] font-extrabold">Giá khuyến mãi</span>
                        </div>
                    </div>

                    @if($isFlashSaleActive)
                        <div class="overflow-hidden rounded-3xl border border-orange-200 bg-gradient-to-br from-[#fff7ed] via-white to-[#fff1f1] shadow-[0_18px_35px_rgba(255,92,0,0.10)]">
                            <div class="flex flex-col gap-4 p-4 sm:p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FF5C00] text-white shadow-[0_10px_22px_rgba(255,92,0,0.22)]">
                                            <i data-lucide="zap" class="w-5 h-5 fill-current"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-[0.22em] text-[#FF5C00]">Flash sale</p>
                                            <h2 class="mt-1 font-display text-lg font-black leading-tight text-slate-900">
                                                {{ $flashSaleCampaign->ten_flash_sales ?? 'Ưu đãi giờ vàng' }}
                                            </h2>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl bg-white px-3 py-2 text-right shadow-sm ring-1 ring-orange-100">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Giá flash</p>
                                        <p class="font-display text-base font-black text-[#E04F2A]">
                                            {{ number_format($flashSaleInfo->gia_flash_sale, 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                </div>

                                <div
                                    id="flash-sale-countdown"
                                    data-endtime="{{ $endTimeStr }}"
                                    class="grid grid-cols-4 gap-2"
                                >
                                    @foreach([
                                        'days' => 'Ngày',
                                        'hours' => 'Giờ',
                                        'minutes' => 'Phút',
                                        'seconds' => 'Giây',
                                    ] as $unit => $label)
                                        <div class="rounded-2xl bg-slate-950 px-2.5 py-3 text-center text-white shadow-sm">
                                            <div id="countdown-{{ $unit }}" class="font-display text-xl font-black leading-none">00</div>
                                            <div class="mt-1 text-[9px] font-bold uppercase tracking-widest text-white/55">{{ $label }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider">
                                        <span class="text-slate-500">Đã bán {{ $daBan }}/{{ $gioiHan }}</span>
                                        <span class="text-[#FF5C00]">Còn {{ $soLuongFlashConLai }} suất</span>
                                    </div>
                                    <div class="h-3 overflow-hidden rounded-full bg-orange-100">
                                        <div
                                            id="flash-sale-progress-bar"
                                            data-width="{{ $percent }}%"
                                            class="h-full rounded-full bg-gradient-to-r from-[#FF5C00] to-[#E04F2A] shadow-[0_0_16px_rgba(255,92,0,0.35)] transition-all duration-700"
                                            style="width: 0"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Capacity pills section (Matches "Dung lượng" in attachment) -->
                <div class="border-t border-neutral-100 pt-5">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">CHỌN CẤU HÌNH:</p>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($variants as $idx => $variant)
                        @php
                            $thong_tin_bien_the = '';
                            if (isset($variant->thong_so_ky_thuat_rieng) && is_array($variant->thong_so_ky_thuat_rieng)) {
                                foreach($variant->thong_so_ky_thuat_rieng as $item) {
                                    $thong_tin_bien_the .= $item['gia_tri'] . '/';
                                }
                            } 
                            $thong_tin_bien_the = rtrim($thong_tin_bien_the, '/');
                            $isActive = $variant->ma_bien_the === $selectedVariant->ma_bien_the;
                        @endphp
                        <a href="?ma_bien_the={{ $variant->ma_bien_the }}" 
                           class="py-2.5 px-4 rounded-xl text-xs font-semibold tracking-wide border transition-all duration-200 {{ $isActive ? 'border-[#FF5C00] text-[#FF5C00] bg-orange-50/15 font-bold shadow-xs' : 'border-neutral-200 text-slate-600 bg-white hover:border-neutral-400' }}">
                            {{ $thong_tin_bien_the ?: 'Bản mặc định' }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-neutral-100 pt-5">
                    <div class="inline-flex items-center gap-2.5 rounded-2xl border border-emerald-100 bg-emerald-50/70 px-3.5 py-2 text-emerald-700">
                        <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-xs">
                            <i data-lucide="package-check" class="w-4 h-4"></i>
                        </span>
                        <div class="leading-tight">
                            <p class="text-[10px] font-black uppercase tracking-wider text-emerald-600/80">Tình trạng kho</p>
                            <p class="text-xs font-extrabold">
                                Còn {{ $selectedVariant->so_luong_ton_kho ?? 142 }} sản phẩm
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Key call-to-actions buttons blocks (Matches layout buttons in mockup) -->
                <div class="border-t border-neutral-100 pt-6 space-y-3.5">
                    <div class="flex gap-3">
                        <button
                            type="button"
                            onclick="submitAddToCart()"
                            class="flex-1 py-4.5 bg-[#FF5C00] text-white font-extrabold rounded-2xl text-xs hover:bg-[#e55300] transition-all flex items-center justify-center gap-2 cursor-pointer uppercase tracking-wider shadow-[0_8px_20px_rgba(255,92,0,0.15)]"
                        >
                            <i data-lucide="shopping-cart" class="w-4.5 h-4.5"></i>
                            Thêm vào giỏ hàng
                        </button>

                        <button
                            type="button"
                            onclick="toggleFavorite()"
                            id="wishlist-btn"
                            class="w-14 h-14 border border-neutral-200 rounded-2xl flex items-center justify-center transition-all shadow-xs active:scale-95 cursor-pointer bg-white text-neutral-400 hover:text-red-500 hover:bg-red-50/20"
                            title="Lưu sản phẩm yêu thích"
                        >
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Mua ngay outline button (Matching attachment layout) -->
                    <button
                        type="button"
                        onclick="submitBuyNow()"
                        class="w-full py-4 bg-white border-2 border-[#FF5C00] text-[#FF5C00] hover:bg-orange-50/15 transition-all text-xs sm:text-sm font-black rounded-2xl flex items-center justify-center gap-2 cursor-pointer uppercase tracking-widest text-center"
                    >
                        <i data-lucide="zap" class="w-5 h-5 fill-current"></i>
                        Mua ngay sản phẩm
                    </button>
                </div>

            </div>
        </div>

        @php
            $hasThongTinThem = false;
            if (isset($productDetail->thong_tin_them) && (is_array($productDetail->thong_tin_them) || $productDetail->thong_tin_them instanceof \Traversable)) {
                foreach ($productDetail->thong_tin_them as $row) {
                    $rowTen = is_array($row) ? ($row['ten'] ?? null) : ($row->ten ?? null);
                    $rowVal = is_array($row) ? ($row['gia_tri'] ?? null) : ($row->gia_tri ?? null);
                    if (!empty($rowTen) || !empty($rowVal)) {
                        $hasThongTinThem = true;
                        break;
                    }
                }
            }
        @endphp

        <!-- Interactive Tabbed Product Details -->
        <div class="mt-20 border-t border-neutral-200 pt-12 text-left animate-fade-in" x-data="{ activeTab: 'specs' }">
            <!-- Tabs Navigation -->
            <div class="flex border-b border-neutral-200 gap-8 mb-8 pb-px">
                <button
                    @click="activeTab = 'specs'"
                    :class="activeTab === 'specs' ? 'border-[#FF5C00] text-[#FF5C00]' : 'border-transparent text-slate-400 hover:text-slate-700'"
                    class="pb-4 font-display font-black text-base border-b-2 transition-all cursor-pointer flex items-center gap-2"
                >
                    <i data-lucide="cpu" class="w-4 h-4"></i>
                    Thông số kỹ thuật
                </button>
                @if ($productDetail->mo_ta_chi_tiet)
                    <button
                        @click="activeTab = 'desc'"
                        :class="activeTab === 'desc' ? 'border-[#FF5C00] text-[#FF5C00]' : 'border-transparent text-slate-400 hover:text-slate-700'"
                        class="pb-4 font-display font-black text-base border-b-2 transition-all cursor-pointer flex items-center gap-2"
                    >
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Mô tả chi tiết
                    </button>
                @endif
                <button
                    @click="activeTab = 'reviews'"
                    :class="activeTab === 'reviews' ? 'border-[#FF5C00] text-[#FF5C00]' : 'border-transparent text-slate-400 hover:text-slate-700'"
                    class="pb-4 font-display font-black text-base border-b-2 transition-all cursor-pointer flex items-center gap-2"
                >
                    <i data-lucide="star" class="w-4 h-4"></i>
                    Đánh giá từ chuyên gia
                </button>
                @if($hasThongTinThem)
                    <button
                        @click="activeTab = 'additional'"
                        :class="activeTab === 'additional' ? 'border-[#FF5C00] text-[#FF5C00]' : 'border-transparent text-slate-400 hover:text-slate-700'"
                        class="pb-4 font-display font-black text-base border-b-2 transition-all cursor-pointer flex items-center gap-2"
                    >
                        <i data-lucide="list" class="w-4 h-4"></i>
                        Thông tin bổ sung
                    </button>
                @endif
            </div>

            <!-- Specs Tab -->
            <div x-show="activeTab === 'specs'" class="space-y-8 animate-fade-in">
                <div class="bg-white rounded-3xl border border-neutral-100 overflow-hidden shadow-xs">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-neutral-100">
                            @foreach($productDetail->thong_so_ky_thuat_chung ?? [] as $row)
                            @php
                                $rowTen = is_array($row) ? ($row['ten'] ?? '') : ($row->ten ?? '');
                                $rowVal = is_array($row) ? ($row['gia_tri'] ?? '') : ($row->gia_tri ?? '');
                            @endphp
                            @if(!empty($rowTen) || !empty($rowVal))
                            <tr class="hover:bg-neutral-50/50 transition-colors group">
                                <td class="p-5 font-bold text-xs tracking-wider text-slate-400 w-1/3 uppercase">
                                    {{ $rowTen }}
                                </td>
                                <td class="p-5 text-slate-800 font-semibold text-sm">
                                    {{ $rowVal }}
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            <!-- Variant Specific Specs -->
                            @if(isset($selectedVariant->thong_so_ky_thuat_rieng) && (is_array($selectedVariant->thong_so_ky_thuat_rieng) || $selectedVariant->thong_so_ky_thuat_rieng instanceof \Traversable))
                                @foreach($selectedVariant->thong_so_ky_thuat_rieng as $spec)
                                @php
                                    $specTen = is_array($spec) ? ($spec['ten'] ?? '') : ($spec->ten ?? '');
                                    $specVal = is_array($spec) ? ($spec['gia_tri'] ?? '') : ($spec->gia_tri ?? '');
                                @endphp
                                @if(!empty($specTen) || !empty($specVal))
                                <tr class="hover:bg-neutral-50/50 transition-colors group">
                                    <td class="p-5 font-bold text-xs tracking-wider text-slate-400 w-1/3 uppercase">
                                        {{ $specTen }}
                                    </td>
                                    <td class="p-5 text-slate-800 font-semibold text-sm">
                                        {{ $specVal }}
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Description Tab -->
            @if ($productDetail->mo_ta_chi_tiet)
                <div x-show="activeTab === 'desc'" class="space-y-8 animate-fade-in" style="display: none;">
                    <div class="bg-white rounded-3xl border border-neutral-100 p-6 sm:p-10 shadow-xs">
                        <div class="prose prose-lg max-w-none 
                                    prose-headings:font-display prose-headings:text-slate-900 prose-headings:font-black 
                                    prose-p:text-slate-600 prose-p:leading-relaxed 
                                    prose-img:rounded-3xl prose-img:shadow-[0_10px_30px_rgba(0,0,0,0.05)] prose-img:mx-auto prose-img:my-8
                                    prose-a:text-[#FF5C00] hover:prose-a:text-orange-600 transition-colors
                                    prose-strong:text-slate-900">
                            {!! $productDetail->mo_ta_chi_tiet !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" class="space-y-8 animate-fade-in" style="display: none;">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white rounded-3xl border border-neutral-100 p-6 sm:p-8 gap-6 shadow-xs">
                    <div class="text-left">
                        <h3 class="font-display text-lg font-black text-slate-900">Đánh giá hiệu năng thực tế</h3>
                        <p class="text-xs text-neutral-400 mt-1 uppercase tracking-wider font-bold">VNTech Verified</p>
                    </div>
                    <div class="flex items-center gap-3 text-amber-500 bg-amber-50/50 px-5 py-2.5 rounded-2xl border border-amber-100/50">
                        <i data-lucide="star" class="w-8 h-8 fill-current"></i>
                        <span class="text-4xl font-display font-black italic text-slate-900">4.8</span>
                        <span class="text-amber-600/70 text-lg">/ 5.0</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $reviews = [
                            [
                                'name' => 'Alex_Vortex',
                                'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDLREUz_-iIyRNpbNGQ71fZWgQQkbeLSyWrMgYxwibJTaVC97a3yxKmSZBazf8DxEOpOQxA-K1fUshf5BQyAa4ynB05JGOWr6fvlH8uJ6i1EqdrY-TFTFbZTGpwN4MfYQaL26EPE3TKgybQoJaxFOHc7r_ZyttpS2KvhK_vIhQUfF0jB1sTdHCQHmETdNa_aKZj-GeDbjOzhOMJcXcrEGLJ04qHKpITJkU5x1SxIapvS3MKIuAyC4fixtXYwBpX_Xmu12DsQQjeSjbJ',
                                'rating' => 5,
                                'text' => 'Khả năng tản nhiệt trên thiết bị này cực kỳ ổn định. Tôi chạy các tác vụ nặng liên tục trong nhiều giờ mà CPU không hề bị quá nhiệt. Thiết kế khung sườn cứng cáp, bền bỉ.'
                            ],
                            [
                                'name' => 'Neon_Rebel',
                                'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBabewNTVTEQP_9W0ijnA8kFDxBPe4BDpAAj1ldgpwpxFkKMvldf7jNc_CdQjykz5AYFXDp71-8Hk9GNww14YNIM39wYSpgT7bvrXAlbAfaPt9mTYECLQPwMWNHQuty4alnBGDkpDj54sxQCbhzYbvSRT3nUhX9Vx2QAK8jSH45GgsId6Vq8IKfIxOmRVvZlF97lKbOM93O4YuLjDrb8oz5py8yXNLt9I5m0veq4eToJGpdoazlGiom2qfE3Y_TMgJylzwo4hQhTGDd',
                                'rating' => 4,
                                'text' => 'Màu sắc màn hình hiển thị chuẩn xác, tốc độ làm mới siêu mượt. Phản hồi phím nhạy và êm ái. Rất xứng đáng với số tiền bỏ ra để phục vụ cả công việc lẫn giải trí.'
                            ]
                        ];
                    @endphp
                    @foreach($reviews as $review)
                    <div class="bg-white rounded-3xl border border-neutral-100 p-6 sm:p-8 space-y-4 hover:-translate-y-1 transition-all duration-300 shadow-xs">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-neutral-100 shadow-sm">
                                <img src="{{ $review['avatar'] }}" class="w-full h-full object-cover">
                            </div>
                            <div class="text-left">
                                <div class="font-extrabold text-slate-900 text-sm">{{ $review['name'] }}</div>
                                <div class="flex text-amber-500 mt-0.5">
                                    @for($i = 0; $i < 5; $i++)
                                        <i data-lucide="star" class="w-3.5 h-3.5 {{ $i < $review['rating'] ? 'fill-current' : 'text-neutral-250 fill-none' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-600 italic font-medium text-sm leading-relaxed border-l-2 border-[#FF5C00] pl-4 text-left">
                            "{{ $review['text'] }}"
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Additional Info Tab -->
            @if($hasThongTinThem)
                <div x-show="activeTab === 'additional'" class="space-y-8 animate-fade-in" style="display: none;">
                    <div class="bg-white rounded-3xl border border-neutral-100 overflow-hidden shadow-xs">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-neutral-100">
                                @foreach($productDetail->thong_tin_them as $row)
                                @php
                                    $rowTen = is_array($row) ? ($row['ten'] ?? '') : ($row->ten ?? '');
                                    $rowVal = is_array($row) ? ($row['gia_tri'] ?? '') : ($row->gia_tri ?? '');
                                @endphp
                                @if(!empty($rowTen) || !empty($rowVal))
                                <tr class="hover:bg-neutral-50/50 transition-colors group">
                                    <td class="p-5 font-bold text-xs tracking-wider text-slate-400 w-1/3 uppercase">
                                        {{ $rowTen }}
                                    </td>
                                    <td class="p-5 text-slate-800 font-semibold text-sm">
                                        {{ $rowVal }}
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->isNotEmpty())
        <div class="mt-24 border-t border-neutral-200 pt-16">
            <div class="space-y-1 text-left mb-10">
                <h2 class="font-display font-black text-2xl text-neutral-900 border-l-4 border-[#ff5c00] pl-3.5 uppercase">
                    Sản phẩm liên quan
                </h2>
                <p class="text-neutral-400 text-xs font-semibold">Các thiết bị công nghệ cùng danh mục dành cho bạn</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $prod)
                @php
                    $originalPrice = $prod->gia_thap_nhat * 1.25;
                    $currentPrice = $prod->gia_thap_nhat;
                @endphp
                <a href="{{ route('viewProductDetail', $prod->ma_san_pham) }}" 
                   class="bg-white border border-neutral-150/70 rounded-3xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group text-left shadow-xs">
                    <!-- Image Wrapper -->
                    <div class="relative bg-neutral-50/50 aspect-square overflow-hidden flex items-center justify-center p-4 border-b border-neutral-100/60">
                        <img src="{{ $prod->link_anh_dai_dien ?? 'https://via.placeholder.com/400' }}" 
                             alt="{{ $prod->ten_san_pham }}" 
                             class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105" />
                        
                        <!-- Badge -->
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-[#ff5c00] text-white px-2.5 py-1 rounded-xl text-[9px] font-black uppercase tracking-wider shadow-sm">
                                Chính hãng
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-5 flex flex-col flex-1">
                        <span class="font-sans text-xs text-neutral-400 font-semibold tracking-wide mb-1.5 uppercase">
                            {{ $categoryName }}
                        </span>
                        <h3 class="font-display text-sm font-black text-neutral-800 line-clamp-2 group-hover:text-[#FF5C00] transition-colors min-h-[40px] leading-snug">
                            {{ $prod->ten_san_pham }}
                        </h3>
                        <p class="text-[11px] text-neutral-450 line-clamp-2 leading-relaxed mt-1">
                            {{ $prod->mo_ta_ngan ?? 'Chưa có mô tả ngắn cho sản phẩm này.' }}
                        </p>

                        <!-- Price & CTA -->
                        <div class="flex justify-between items-center mt-auto pt-4 border-t border-neutral-100/50 w-full">
                            <div class="text-left">
                                <span class="text-[9px] text-neutral-400 uppercase tracking-widest block font-bold leading-none mb-1">Chỉ từ</span>
                                <span class="font-display text-[15px] font-black text-[#E04F2A] tracking-tight block leading-none">
                                    {{ number_format($currentPrice, 0, ',', '.') }}₫
                                </span>
                            </div>
                            <div class="w-9 h-9 bg-neutral-50 border border-neutral-150/70 group-hover:bg-[#ff5c00] group-hover:border-[#ff5c00] text-slate-400 group-hover:text-white rounded-xl flex items-center justify-center transition-all duration-300 transform active:scale-90 shadow-xs">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<script id="gallery-json" type="application/json">
    {!! json_encode(array_merge([$selectedVariant->link_anh_bien_the ?: $productDetail->link_anh_dai_dien], $productDetail->hinh_anh ?? [])) !!}
</script>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Set progress bar width dynamically
        const progressBar = document.getElementById('flash-sale-progress-bar');
        if (progressBar) {
            progressBar.style.width = progressBar.getAttribute('data-width');
        }

        // Countdown Timer Logic
        const timerContainer = document.getElementById('flash-sale-countdown');
        if (timerContainer) {
            const endTimeStr = timerContainer.getAttribute('data-endtime');
            const targetTime = new Date(endTimeStr).getTime();

            const daysEl = document.getElementById('countdown-days');
            const hoursEl = document.getElementById('countdown-hours');
            const minutesEl = document.getElementById('countdown-minutes');
            const secondsEl = document.getElementById('countdown-seconds');

            const updateTimer = () => {
                const now = new Date().getTime();
                const diff = targetTime - now;

                if (diff <= 0) {
                    if (daysEl) daysEl.innerText = '00';
                    if (hoursEl) hoursEl.innerText = '00';
                    if (minutesEl) minutesEl.innerText = '00';
                    if (secondsEl) secondsEl.innerText = '00';
                    clearInterval(timerInterval);
                    return;
                }

                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                if (daysEl) daysEl.innerText = d.toString().padStart(2, '0');
                if (hoursEl) hoursEl.innerText = h.toString().padStart(2, '0');
                if (minutesEl) minutesEl.innerText = m.toString().padStart(2, '0');
                if (secondsEl) secondsEl.innerText = s.toString().padStart(2, '0');
            };

            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        }
    });

    // Gallery navigation
    let activeImageIndex = 0;
    const gallery = JSON.parse(document.getElementById('gallery-json').textContent);

    function changeMainImage(index) {
        activeImageIndex = index;
        const mainImg = document.getElementById('main-product-image');
        if (mainImg) {
            mainImg.src = gallery[activeImageIndex];
        }

        // Highlight active thumbnail border
        const thumbs = document.querySelectorAll('.thumb-container');
        thumbs.forEach((thumb, idx) => {
            if (idx === index) {
                thumb.classList.remove('border-neutral-200');
                thumb.classList.add('border-[#FF5C00]', 'ring-2', 'ring-orange-100');
                const border = thumb.querySelector('.thumb-border');
                if (border) {
                    border.classList.remove('opacity-0');
                    border.classList.add('opacity-100');
                }
            } else {
                thumb.classList.remove('border-[#FF5C00]', 'ring-2', 'ring-orange-100');
                thumb.classList.add('border-neutral-200');
                const border = thumb.querySelector('.thumb-border');
                if (border) {
                    border.classList.remove('opacity-100');
                    border.classList.add('opacity-0');
                }
            }
        });
    }

    function prevImage() {
        let newIndex = activeImageIndex - 1;
        if (newIndex < 0) {
            newIndex = gallery.length - 1;
        }
        changeMainImage(newIndex);
    }

    function nextImage() {
        let newIndex = (activeImageIndex + 1) % gallery.length;
        changeMainImage(newIndex);
    }

    // Quantity selectors
    // Cart operations
    function submitAddToCart() {
        window.location.href = "{{ route('cart.addItem') }}?ma_bien_the={{ $selectedVariant->ma_bien_the }}&so_luong=1";
    }

    // Buy now redirect
    function submitBuyNow() {
        window.location.href = "{{ route('payment.view', $selectedVariant->ma_bien_the) }}?so_luong=1";
    }

    // Wishlist favorite toggle
    let isFavorite = false;
    function toggleFavorite() {
        isFavorite = !isFavorite;
        const btn = document.getElementById('wishlist-btn');
        if (isFavorite) {
            btn.innerHTML = '<i data-lucide="heart" class="w-5 h-5 fill-current text-red-500"></i>';
            btn.className = 'w-14 h-14 border border-red-200 rounded-xl flex items-center justify-center transition-colors shadow-sm active:scale-95 cursor-pointer bg-red-50 text-red-500';
            alert('Đã thêm sản phẩm vào danh sách yêu thích!');
        } else {
            btn.innerHTML = '<i data-lucide="heart" class="w-5 h-5"></i>';
            btn.className = 'w-14 h-14 border border-gray-200 rounded-xl flex items-center justify-center transition-colors shadow-sm active:scale-95 cursor-pointer bg-white text-gray-400 hover:text-red-500 hover:bg-red-50/35';
            alert('Đã xóa sản phẩm khỏi danh sách yêu thích!');
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endsection
