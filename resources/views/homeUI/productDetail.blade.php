@extends('layouts.app')

@section('title', $productDetail->ten_san_pham)

@section('content')
<!-- Loaded data and configurations from ProductDetailController -->

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
    @keyframes compareBlink {
        0%, 100% {
            color: rgb(148 163 184);
            transform: scale(1);
            box-shadow: none;
        }
        50% {
            color: #ff4f00;
            transform: scale(1.18);
            box-shadow: 0 0 0 10px rgba(255, 79, 0, 0.16);
        }
    }
    .compare-blink {
        animation: compareBlink 0.45s ease-in-out 2;
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
                <!-- Gallery list loaded from Controller -->
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
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                @php $starFill = max(0, min(100, ($averageRating - ($i - 1)) * 100)); @endphp
                                <span class="relative inline-block w-4 h-4 overflow-hidden text-base leading-none text-neutral-200">
                                    ★
                                    <span class="absolute left-0 top-0 h-full overflow-hidden text-amber-500" style="--fill: {{ $starFill }}%; width: var(--fill);">★</span>
                                </span>
                            @endfor
                        </div>
                        <span class="font-semibold text-slate-700">
                            <b class="text-slate-950 mr-1">{{ number_format($averageRating, 1) }}</b>
                            ({{ number_format($reviewsCount, 0, ',', '.') }} đánh giá)
                        </span>
                        <span class="h-4 w-[1px] bg-slate-200"></span>
                        <span class="text-slate-700 font-semibold">
                            Đã bán: <b class="text-slate-950">{{ $selectedVariant->da_ban ?? 0 }}</b>
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

                @if ($productDetail->kiem_tra_bien_the)
                <!-- Capacity pills section (Matches "Dung lượng" in attachment) -->
                <div class="border-t border-neutral-100 pt-5">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">CHỌN PHIÊN BẢN:</p>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($variants as $idx => $variant)
                        <a href="{{ route('home.product_detail', ['ma_san_pham' => $productDetail->ma_san_pham, 'ma_bien_the' => $variant->ma_bien_the]) }}" 
                           class="py-2.5 px-4 rounded-xl text-xs font-semibold tracking-wide border transition-all duration-200 {{ $variant->ma_bien_the === $selectedVariant->ma_bien_the ? 'border-[#FF5C00] text-[#FF5C00] bg-orange-50/15 font-bold shadow-xs' : 'border-neutral-200 text-slate-600 bg-white hover:border-neutral-400' }}">
                            {{ $variant->thong_tin_hien_thi ?: 'Bản mặc định' }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

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
                            onclick="addCurrentVariantToCompare()"
                            id="compare-variant-btn"
                            class="w-14 h-14 border border-neutral-200 rounded-2xl flex items-center justify-center transition-all shadow-xs active:scale-95 cursor-pointer bg-white text-neutral-400 hover:text-[#FF5C00] hover:bg-orange-50/20"
                            title="Thêm vào danh sách so sánh"
                        >
                            <div class="relative">
                                <i data-lucide="git-compare" class="w-5 h-5"></i>
                                <span class="absolute -top-0.5 -right-1.5 w-3 h-3 bg-brand-500 text-white text-[6px] font-black rounded-full flex items-center justify-center leading-none">AI</span>
                            </div>
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


        <!-- Interactive Tabbed Product Details -->
        <div class="mt-20 border-t border-neutral-200 pt-12 text-left animate-fade-in" x-data="{ activeTab: 'specs' }">
            <!-- Tabs Navigation -->
            <div class="flex border-b border-neutral-200 gap-8 mb-8 pb-px">
                <button
                    x-on:click="activeTab = 'specs'"
                    :class="activeTab === 'specs' ? 'border-[#FF5C00] text-[#FF5C00]' : 'border-transparent text-slate-400 hover:text-slate-700'"
                    class="pb-4 font-display font-black text-base border-b-2 transition-all cursor-pointer flex items-center gap-2"
                >
                    <i data-lucide="cpu" class="w-4 h-4"></i>
                    Thông số kỹ thuật
                </button>
                @if ($productDetail->mo_ta_chi_tiet)
                    <button
                        x-on:click="activeTab = 'desc'"
                        :class="activeTab === 'desc' ? 'border-[#FF5C00] text-[#FF5C00]' : 'border-transparent text-slate-400 hover:text-slate-700'"
                        class="pb-4 font-display font-black text-base border-b-2 transition-all cursor-pointer flex items-center gap-2"
                    >
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Mô tả chi tiết
                    </button>
                @endif
                <button
                    x-on:click="activeTab = 'reviews'"
                    :class="activeTab === 'reviews' ? 'border-[#FF5C00] text-[#FF5C00]' : 'border-transparent text-slate-400 hover:text-slate-700'"
                    class="pb-4 font-display font-black text-base border-b-2 transition-all cursor-pointer flex items-center gap-2"
                >
                    <i data-lucide="star" class="w-4 h-4"></i>
                    Đánh giá sản phẩm
                </button>
                @if($hasThongTinThem)
                    <button
                        x-on:click="activeTab = 'additional'"
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
                            @foreach($formattedChung as $row)
                            <tr class="hover:bg-neutral-50/50 transition-colors group">
                                <td class="p-5 font-bold text-xs tracking-wider text-slate-400 w-1/3 uppercase">
                                    {{ $row['ten'] }}
                                </td>
                                <td class="p-5 text-slate-800 font-semibold text-sm">
                                    {{ $row['gia_tri'] }}
                                </td>
                            </tr>
                            @endforeach

                            <!-- Variant Specific Specs -->
                            @foreach($formattedRieng as $spec)
                            <tr class="hover:bg-neutral-50/50 transition-colors group">
                                <td class="p-5 font-bold text-xs tracking-wider text-slate-400 w-1/3 uppercase">
                                    {{ $spec['ten'] }}
                                </td>
                                <td class="p-5 text-slate-800 font-semibold text-sm">
                                    {{ $spec['gia_tri'] }}
                                </td>
                            </tr>
                            @endforeach
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
            <div
                x-show="activeTab === 'reviews'"
                x-data="productReviews({{ json_encode(route('reviews.index', $productDetail->ma_san_pham)) }}, {{ json_encode(asset('images/AvatarDefault.jpg')) }})"
                x-init="load(initialPage())"
                class="space-y-6 animate-fade-in"
                style="display: none;"
            >
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white rounded-2xl border border-neutral-100 p-5 sm:p-6 gap-5 shadow-xs">
                    <div class="text-left">
                        <h3 class="font-display text-lg font-black text-slate-900">Đánh giá sản phẩm</h3>
                        <p class="text-xs text-neutral-400 mt-1 font-bold">
                            Chia sẻ thực tế từ khách hàng đã mua tại VNTech
                        </p>
                    </div>
                    <div class="flex items-end gap-3">
                        <div class="text-4xl font-display font-black text-[#FF5C00] leading-none">
                            {{ number_format($averageRating, 1) }}
                        </div>
                        <div class="pb-0.5">
                            <div class="flex text-sm leading-none">
                                @for($i = 1; $i <= 5; $i++)
                                    @php $starFill = max(0, min(100, ($averageRating - ($i - 1)) * 100)); @endphp
                                    <span class="relative inline-block w-3.5 h-3.5 overflow-hidden text-sm leading-none text-gray-200">
                                        ★
                                        <span class="absolute left-0 top-0 h-full overflow-hidden text-amber-400" style="--fill: {{ $starFill }}%; width: var(--fill);">★</span>
                                    </span>
                                @endfor
                            </div>
                            <p class="text-xs text-neutral-400 mt-1 font-bold">
                                {{ number_format($reviewsCount, 0, ',', '.') }} đánh giá
                            </p>
                        </div>
                    </div>
                </div>

                <div x-show="loading" class="bg-white rounded-2xl border border-neutral-100 p-8 text-center text-sm font-bold text-neutral-400">
                    Đang tải đánh giá...
                </div>

                <div x-show="!loading && reviews.length === 0" class="bg-white rounded-2xl border border-neutral-100 p-8 text-center">
                    <i data-lucide="message-square" class="w-10 h-10 text-neutral-300 mx-auto mb-3"></i>
                    <p class="text-sm font-bold text-slate-500">Sản phẩm chưa có đánh giá nào</p>
                </div>

                <div x-show="!loading && reviews.length > 0" class="bg-white rounded-2xl border border-neutral-100 divide-y divide-neutral-100 shadow-xs overflow-hidden">
                    <template x-for="review in reviews" :key="review.id || review.ma_danh_gia || review.created_at">
                        <article class="p-5 sm:p-6">
                            <div class="flex gap-4">
                                <img
                                    :src="review.is_anonymous ? defaultAvatar : (review.user?.avatar_url || defaultAvatar)"
                                    alt="Avatar người đánh giá"
                                    class="w-10 h-10 rounded-full object-cover border border-neutral-200 shrink-0 bg-neutral-50"
                                >

                                <div class="min-w-0 flex-1 space-y-2">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-extrabold text-slate-900 truncate" x-text="review.is_anonymous ? 'Ẩn danh' : (review.user?.ho_ten || 'Khách hàng VNTech')"></h4>
                                            <div class="flex text-sm leading-none mt-1" :aria-label="`${review.so_sao} sao`">
                                                <template x-for="star in 5" :key="star">
                                                    <span :class="star <= Number(review.so_sao || 0) ? 'text-amber-400' : 'text-neutral-200'">★</span>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span
                                                x-show="review.is_updated"
                                                class="rounded-full bg-neutral-100 px-2 py-1 text-[10px] font-extrabold uppercase tracking-wider text-neutral-500"
                                            >
                                                Đã chỉnh sửa
                                            </span>
                                            <time class="text-xs text-neutral-400 font-semibold" x-text="formatDate(review.created_at)"></time>
                                        </div>
                                    </div>

                                    <p x-show="review.ten_hien_thi || review.ten_bien_the" class="text-xs text-neutral-500 font-semibold">
                                        Phân loại hàng: <span class="text-slate-600" x-text="review.ten_hien_thi || review.ten_bien_the"></span>
                                    </p>

                                    <p x-show="review.noi_dung" class="text-sm text-slate-700 leading-relaxed whitespace-pre-line" x-text="review.noi_dung"></p>

                                    <div x-show="mediaItems(review).length > 0" class="flex flex-wrap gap-2 pt-1">
                                        <template x-for="item in mediaItems(review)" :key="`${item.type}-${item.url}`">
                                            <a :href="item.url" target="_blank" rel="noopener" class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden border border-neutral-200 bg-neutral-50 group">
                                                <template x-if="item.type === 'video'">
                                                    <video :src="item.url" class="w-full h-full object-cover bg-slate-900" muted preload="metadata"></video>
                                                </template>
                                                <template x-if="item.type === 'image'">
                                                    <img :src="item.url" alt="Ảnh đánh giá" class="w-full h-full object-cover" loading="lazy">
                                                </template>
                                                <div x-show="item.type === 'video'" class="absolute inset-0 flex items-center justify-center bg-black/25 text-white">
                                                    <i data-lucide="play" class="w-7 h-7 fill-current"></i>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </template>
                </div>

                <div x-show="!loading && lastPage > 1" class="flex flex-wrap items-center justify-center gap-2">
                    <button
                        type="button"
                        x-on:click="goToPage(page - 1)"
                        :disabled="page <= 1"
                        class="w-9 h-9 rounded-lg border border-neutral-200 bg-white text-slate-500 hover:border-[#FF5C00] hover:text-[#FF5C00] disabled:opacity-40 disabled:hover:border-neutral-200 disabled:hover:text-slate-500 transition-colors text-xs font-extrabold"
                    >
                        Trước
                    </button>

                    <template x-for="item in paginationItems()" :key="item.key">
                        <span>
                            <span x-show="item.type === 'ellipsis'" class="w-9 h-9 flex items-center justify-center text-xs font-extrabold text-neutral-400">...</span>
                            <button
                                x-show="item.type === 'page'"
                                type="button"
                                x-on:click="goToPage(item.page)"
                                :class="item.page === page ? 'bg-[#FF5C00] border-[#FF5C00] text-white' : 'bg-white border-neutral-200 text-slate-600 hover:border-[#FF5C00] hover:text-[#FF5C00]'"
                                class="w-9 h-9 rounded-lg border transition-colors text-xs font-extrabold"
                                x-text="item.page"
                            ></button>
                        </span>
                    </template>

                    <button
                        type="button"
                        x-on:click="goToPage(page + 1)"
                        :disabled="page >= lastPage"
                        class="w-9 h-9 rounded-lg border border-neutral-200 bg-white text-slate-500 hover:border-[#FF5C00] hover:text-[#FF5C00] disabled:opacity-40 disabled:hover:border-neutral-200 disabled:hover:text-slate-500 transition-colors text-xs font-extrabold"
                    >
                        Sau
                    </button>
                </div>
            </div>

            <!-- Additional Info Tab -->
            @if($hasThongTinThem)
                <div x-show="activeTab === 'additional'" class="space-y-8 animate-fade-in" style="display: none;">
                    <div class="bg-white rounded-3xl border border-neutral-100 overflow-hidden shadow-xs">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-neutral-100">
                                @foreach($formattedThem as $row)
                                <tr class="hover:bg-neutral-50/50 transition-colors group">
                                    <td class="p-5 font-bold text-xs tracking-wider text-slate-400 w-1/3 uppercase">
                                        {{ $row['ten'] }}
                                    </td>
                                    <td class="p-5 text-slate-800 font-semibold text-sm">
                                        {{ $row['gia_tri'] }}
                                    </td>
                                </tr>
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
                <a href="{{ route('home.product_detail', ['ma_san_pham' => $prod->ma_san_pham, 'ma_bien_the' => $prod->default_ma_bien_the]) }}" 
                   class="bg-white border border-neutral-150/70 rounded-3xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group text-left shadow-xs">
                    <!-- Image Wrapper -->
                    <div class="relative bg-neutral-50/50 aspect-square overflow-hidden flex items-center justify-center p-4 border-b border-neutral-100/60">
                        <img src="{{ $prod->link_anh_dai_dien ?: asset('images/no-image.png') }}" 
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
                                <span class="text-[11px] text-slate-700 uppercase tracking-wider block font-extrabold leading-none mb-1.5">Đã bán: {{ $prod->tong_luot_ban ?? 0 }}</span>
                                <span class="text-[9px] text-neutral-400 uppercase tracking-widest block font-bold leading-none mb-1">Chỉ từ</span>
                                <span class="font-display text-[15px] font-black text-[#E04F2A] tracking-tight block leading-none">
                                    {{ number_format($prod->current_price, 0, ',', '.') }}₫
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
    function productReviews(endpoint, defaultAvatar) {
        return {
            endpoint,
            defaultAvatar,
            reviews: [],
            loading: false,
            page: 1,
            lastPage: 1,

            initialPage() {
                const page = Number(new URL(window.location.href).searchParams.get('page') || 1);

                return page > 0 ? page : 1;
            },

            async load(page = 1) {
                this.loading = true;

                try {
                    const url = new URL(this.endpoint, window.location.origin);
                    url.searchParams.set('page', page);

                    const response = await fetch(url.toString(), {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Không tải được đánh giá');
                    }

                    const payload = await response.json();
                    const items = Array.isArray(payload.data) ? payload.data : [];

                    this.reviews = items;
                    this.page = Number(payload.current_page || page);
                    this.lastPage = Number(payload.last_page || 1);
                    this.syncPageQuery();

                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                } catch (error) {
                    console.error(error);
                    if (typeof window.showToast === 'function') {
                        window.showToast('Không tải được đánh giá sản phẩm', 'error');
                    }
                } finally {
                    this.loading = false;
                }
            },

            goToPage(pageNumber) {
                const nextPage = Number(pageNumber);

                if (!nextPage || nextPage < 1 || nextPage > this.lastPage || nextPage === this.page) return;

                this.load(nextPage);
            },

            paginationItems() {
                const pages = new Set([1, this.lastPage]);
                const start = Math.max(1, this.page - 2);
                const end = Math.min(this.lastPage, this.page + 2);

                for (let page = start; page <= end; page += 1) {
                    pages.add(page);
                }

                const sortedPages = [...pages].sort((a, b) => a - b);
                const items = [];

                sortedPages.forEach((pageNumber, index) => {
                    const previousPage = sortedPages[index - 1];

                    if (previousPage && pageNumber - previousPage > 1) {
                        items.push({
                            type: 'ellipsis',
                            key: `ellipsis-${previousPage}-${pageNumber}`,
                        });
                    }

                    items.push({
                        type: 'page',
                        key: `page-${pageNumber}`,
                        page: pageNumber,
                    });
                });

                return items;
            },

            syncPageQuery() {
                const url = new URL(window.location.href);

                if (this.page > 1) {
                    url.searchParams.set('page', this.page);
                } else {
                    url.searchParams.delete('page');
                }

                window.history.replaceState({}, '', url.toString());
            },

            mediaItems(review) {
                const media = [];

                if (review.video?.url) {
                    media.push({
                        type: 'video',
                        url: review.video.url,
                    });
                }

                (review.danh_sach_anh || []).forEach((image) => {
                    const url = typeof image === 'string' ? image : image?.url;

                    if (url) {
                        media.push({
                            type: 'image',
                            url,
                        });
                    }
                });

                return media;
            },

            formatDate(value) {
                if (!value) return '';

                return new Intl.DateTimeFormat('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                }).format(new Date(value));
            },
        };
    }

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

    const COMPARE_STORAGE_KEY = window.VNTECH_COMPARE_STORAGE_KEY || 'vntech_compare_variants';
    const MAX_COMPARE_ITEMS = 3;

    function getCompareVariantIds() {
        try {
            const parsed = JSON.parse(localStorage.getItem(COMPARE_STORAGE_KEY) || '[]');
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    }

    function saveCompareVariantIds(variantIds) {
        localStorage.setItem(COMPARE_STORAGE_KEY, JSON.stringify(variantIds));
    }

    function blinkAiCompareButton() {
        const compareTrigger = document.getElementById('ai-compare-trigger');
        if (!compareTrigger) return;

        compareTrigger.classList.remove('compare-blink');
        void compareTrigger.offsetWidth;
        compareTrigger.classList.add('compare-blink');
    }

    function addCurrentVariantToCompare() {
        const variantId = '{{ $selectedVariant->ma_bien_the }}';
        let variantIds = getCompareVariantIds().filter((id) => id !== variantId);

        variantIds.push(variantId);

        if (variantIds.length > MAX_COMPARE_ITEMS) {
            variantIds = variantIds.slice(variantIds.length - MAX_COMPARE_ITEMS);
        }

        saveCompareVariantIds(variantIds);
        window.updateCompareCount?.();
        blinkAiCompareButton();

        if (typeof window.showToast === 'function') {
            window.showToast('Đã thêm sản phẩm vào danh sách so sánh', 'success');
        }
    }
</script>
@endsection
