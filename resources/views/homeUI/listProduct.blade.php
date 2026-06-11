@extends('layouts.app')
@section('title', 'Danh sách sản phẩm | VNTech')

@section('content')
@php
    $categories = $categories->filter(fn($cat) => ($cat->trang_thai ?? '') !== 'deleted');
    $categoriesMap = [];
    foreach ($categories as $cat) {
        $categoriesMap[$cat->ma_danh_muc] = $cat->ten_danh_muc;
    }

    $categoriesList = $categories->toArray();
    $parentIds = array_column($categoriesList, 'ma_danh_muc');
    $parentCategories = [];
    $childCategories = [];

    foreach ($categories as $cat) {
        if (empty($cat->ma_danh_muc_cha) || !in_array($cat->ma_danh_muc_cha, $parentIds)) {
            $parentCategories[] = $cat;
        } else {
            $childCategories[] = $cat;
        }
    }

    $categoryTree = [];
    foreach ($parentCategories as $parent) {
        $childrenList = [];
        foreach ($childCategories as $child) {
            if ($child->ma_danh_muc_cha === $parent->ma_danh_muc) {
                $childrenList[] = [
                    'ma_danh_muc' => $child->ma_danh_muc,
                    'ten_danh_muc' => $child->ten_danh_muc,
                ];
            }
        }

        $categoryTree[] = [
            'ma_danh_muc' => $parent->ma_danh_muc,
            'ten_danh_muc' => $parent->ten_danh_muc,
            'children' => $childrenList,
        ];
    }

    $selectedCategories = request()->input('categories', []);
    if (!is_array($selectedCategories)) {
        $selectedCategories = [$selectedCategories];
    }

    if (empty($selectedCategories) && request()->filled('category')) {
        $selectedCategories = array_filter(explode(',', request('category')));
    }

    foreach ($selectedCategories as $selectedCat) {
        foreach ($categoryTree as $node) {
            if ($node['ten_danh_muc'] === $selectedCat) {
                foreach ($node['children'] as $child) {
                    if (!in_array($child['ten_danh_muc'], $selectedCategories, true)) {
                        $selectedCategories[] = $child['ten_danh_muc'];
                    }
                }
            }
        }
    }

    $selectedPriceRanges = request()->input('price_ranges', []);
    if (!is_array($selectedPriceRanges)) {
        $selectedPriceRanges = [$selectedPriceRanges];
    }

    $searchTerm = trim((string) request('search', ''));
    $sortOption = request('sort', 'popular');
    $sortLabels = [
        'popular' => 'Phổ biến nhất',
        'price_asc' => 'Giá từ thấp đến cao',
        'price_desc' => 'Giá từ cao đến thấp',
        'newest' => 'Mới nhất cập nhật',
    ];

    $allowedCategories = $selectedCategories;
    foreach ($selectedCategories as $selectedCat) {
        foreach ($categoryTree as $node) {
            if ($node['ten_danh_muc'] === $selectedCat) {
                foreach ($node['children'] as $child) {
                    if (!in_array($child['ten_danh_muc'], $allowedCategories, true)) {
                        $allowedCategories[] = $child['ten_danh_muc'];
                    }
                }
            }
        }
    }


    $productsData = [];
    foreach ($products as $index => $prod) {
        $categoryName = $categoriesMap[$prod->ma_danh_muc] ?? 'Khác';

        $thongTinThem = $prod->thong_tin_them;
        $promoText = '';
        if (is_array($thongTinThem) && count($thongTinThem) > 0) {
            $first = reset($thongTinThem);
            if (is_array($first)) {
                $ten = (string) ($first['ten'] ?? '');
                $giaTri = (string) ($first['gia_tri'] ?? '');
                $promoText = trim($ten . ' ' . $giaTri);
            } else {
                $promoText = (string) $first;
            }
        } elseif (is_string($thongTinThem) && !empty($thongTinThem)) {
            $promoText = $thongTinThem;
        }

        $productsData[] = [
            'id' => (string) $prod->ma_san_pham,
            'name' => (string) $prod->ten_san_pham,
            'category' => (string) $categoryName,
            'mo_ta_ngan' => (string) ($prod->mo_ta_ngan ?? 'Chưa có mô tả ngắn cho sản phẩm này.'),
            'price' => (int) $prod->gia_thap_nhat,
            'image' => (string) ($prod->link_anh_dai_dien ?: asset('images/no-image.png')),
            'promoText' => $promoText,
            'rating' => $prod->so_sao_trung_binh ?? 0,
            'reviewsCount' => $prod->so_luot_danh_gia ?? 0,
            'tong_da_ban' => (int) ($prod->tong_luot_ban ?? 0),
            'created_at' => $prod->created_at,
        ];
    }

    $filteredProducts = collect($productsData)->filter(function ($product) use ($selectedCategories, $allowedCategories, $searchTerm, $selectedPriceRanges) {
        if (!empty($selectedCategories) && !in_array($product['category'], $allowedCategories, true)) {
            return false;
        }

        if ($searchTerm !== '') {
            $term = mb_strtolower($searchTerm);
            $name = mb_strtolower($product['name']);
            $category = mb_strtolower($product['category']);
            if (!str_contains($name, $term) && !str_contains($category, $term)) {
                return false;
            }
        }

        if (!empty($selectedPriceRanges)) {
            $price = $product['price'];
            $priceMatched = false;
            if (in_array('under2m', $selectedPriceRanges, true) && $price < 2000000) $priceMatched = true;
            if (in_array('from2to5m', $selectedPriceRanges, true) && $price >= 2000000 && $price <= 5000000) $priceMatched = true;
            if (in_array('from5to10m', $selectedPriceRanges, true) && $price >= 5000000 && $price <= 10000000) $priceMatched = true;
            if (in_array('above10m', $selectedPriceRanges, true) && $price > 10000000) $priceMatched = true;
            if (!$priceMatched) return false;
        }

        return true;
    });

    $processedProducts = match ($sortOption) {
        'price_asc' => $filteredProducts->sortBy('price'),
        'price_desc' => $filteredProducts->sortByDesc('price'),
        'newest' => $filteredProducts->sortByDesc('created_at'),
        'popular' => $filteredProducts->sortByDesc('tong_da_ban'),
        default => $filteredProducts->sortByDesc('tong_da_ban'),
    };

    $processedProducts = $processedProducts->values();
    $totalProducts = $processedProducts->count();
    $itemsPerPage = 10;
    $totalPages = max(1, (int) ceil($totalProducts / $itemsPerPage));
    $currentPage = min(max((int) request('page', 1), 1), $totalPages);
    $paginatedProducts = $processedProducts->slice(($currentPage - 1) * $itemsPerPage, $itemsPerPage)->values();

    $pageUrl = fn ($page) => request()->fullUrlWithQuery(['page' => $page]);
    $resetUrl = url()->current();
@endphp

<style>
    .filter-check-box .filter-check-icon {
        opacity: 0;
    }

    .filter-check-box {
        border-color: rgb(209 213 219);
    }

    .filter-check-label-blue {
        color: rgb(64 64 64);
    }

    .filter-check-label-muted {
        color: rgb(115 115 115);
    }

    .filter-check-input:checked + .filter-check-box {
        background-color: #0058bc;
        border-color: #0058bc;
    }

    .filter-check-input:checked + .filter-check-box .filter-check-icon {
        opacity: 1;
    }

    .filter-check-input:checked + .filter-check-box + .filter-check-label {
        color: #0058bc;
    }

    .filter-check-input.filter-check-orange:checked + .filter-check-box {
        background-color: #ff5c00;
        border-color: #ff5c00;
    }

    .filter-check-input.filter-check-orange:checked + .filter-check-box + .filter-check-label {
        color: #ff5c00;
        font-weight: 700;
    }
</style>

<div id="product-list-container" class="min-h-screen bg-[#FAF8F2] font-sans text-slate-800 selection:bg-brand-500/20 selection:text-brand-500 flex flex-col relative">
    <main class="max-w-[1600px] mx-auto px-6 md:px-12 py-8 flex-1 w-full">
        <nav id="primary-breadcrumbs-nav" class="flex items-center gap-2 mb-6 font-semibold text-xs tracking-wider uppercase text-slate-400">
            <a href="{{ route('home.index') }}" class="hover:text-accent-500 transition-colors cursor-pointer">
                Trang chủ
            </a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
            <span class="text-slate-600">Sản phẩm</span>
            @if(!empty($selectedCategories))
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
                <span class="text-brand-500 font-bold">{{ implode(', ', $selectedCategories) }}</span>
            @endif
        </nav>

        <form method="GET" action="{{ url()->current() }}" class="flex flex-col lg:flex-row gap-8">
            <aside class="w-full lg:w-64 bg-white border border-slate-100 rounded-2xl p-6 shadow-[0_8px_30px_rgba(0,0,0,0.015)] h-fit shrink-0">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100 bg-slate-50/50 -mx-6 -mt-6 p-6 rounded-t-2xl">
                    <i data-lucide="filter" class="text-accent-500 w-5 h-5"></i>
                    <h3 class="font-bold uppercase tracking-[0.2em] text-sm text-accent-500">Bộ Lọc Dữ Liệu</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-3 tracking-[0.2em]">Tìm kiếm nhanh</h4>
                        <div class="relative flex items-center bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200 focus-within:border-accent-500 transition-all">
                            <i data-lucide="search" class="text-slate-400 w-4 h-4 mr-2 shrink-0"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ $searchTerm }}"
                                placeholder="Tìm sản phẩm nhanh..."
                                class="bg-transparent border-none focus:ring-0 text-xs w-full outline-none text-slate-800 placeholder-slate-400"
                            />
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-3 tracking-[0.2em]">Danh mục</h4>
                        <ul class="space-y-1 text-left">
                            <li>
                                <a href="{{ $resetUrl }}" class="flex items-center gap-2.5 py-1.5 cursor-pointer group no-underline">
                                    <span class="w-4 h-4 rounded border flex items-center justify-center transition-all {{ empty($selectedCategories) ? 'bg-accent-500 border-accent-500' : 'border-slate-300 group-hover:border-accent-500' }}">
                                        @if(empty($selectedCategories))
                                            <i data-lucide="check" class="w-2.5 h-2.5 text-white"></i>
                                        @endif
                                    </span>
                                    <span class="text-xs font-black uppercase tracking-wider transition-colors {{ empty($selectedCategories) ? 'text-accent-600' : 'text-slate-600 group-hover:text-accent-600' }}">
                                        Tất cả danh mục
                                    </span>
                                </a>
                            </li>

                            @foreach($categoryTree as $node)
                                @php $parentChecked = in_array($node['ten_danh_muc'], $selectedCategories, true); @endphp
                                <li class="space-y-1">
                                    <label class="flex items-center gap-2.5 py-1.5 cursor-pointer group">
                                        <input
                                            type="checkbox"
                                            name="categories[]"
                                            value="{{ $node['ten_danh_muc'] }}"
                                            class="filter-check-input category-parent hidden"
                                            data-category-id="{{ $node['ma_danh_muc'] }}"
                                            @checked($parentChecked)
                                        >
                                        <span class="filter-check-box w-4 h-4 rounded border flex items-center justify-center transition-all group-hover:border-accent-500">
                                            <i data-lucide="check" class="filter-check-icon w-2.5 h-2.5 text-white transition-opacity"></i>
                                        </span>
                                        <span class="filter-check-label filter-check-label-blue text-xs font-extrabold uppercase tracking-tight transition-colors group-hover:text-accent-600">
                                            {{ $node['ten_danh_muc'] }}
                                        </span>
                                    </label>

                                    @if(count($node['children']) > 0)
                                        <ul class="pl-4 border-l border-slate-100 ml-2 space-y-1.5 py-1">
                                            @foreach($node['children'] as $child)
                                                @php $childChecked = in_array($child['ten_danh_muc'], $selectedCategories, true); @endphp
                                                <li>
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input
                                                            type="checkbox"
                                                            name="categories[]"
                                                            value="{{ $child['ten_danh_muc'] }}"
                                                            class="filter-check-input filter-check-orange category-child hidden"
                                                            data-parent-id="{{ $node['ma_danh_muc'] }}"
                                                            @checked($childChecked)
                                                        >
                                                        <span class="filter-check-box w-3.5 h-3.5 rounded border flex items-center justify-center transition-all group-hover:border-brand-500">
                                                            <i data-lucide="check" class="filter-check-icon w-2 h-2 text-white transition-opacity"></i>
                                                        </span>
                                                        <span class="filter-check-label filter-check-label-muted text-[11px] font-semibold transition-colors group-hover:text-slate-700">
                                                            {{ $child['ten_danh_muc'] }}
                                                        </span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-[11px] font-bold uppercase text-slate-500 mb-3 tracking-[0.2em]">Khoảng giá</h4>
                        <div class="space-y-3">
                            @foreach([
                                'under2m' => 'Dưới 2 triệu',
                                'from2to5m' => 'Từ 2 đến 5 triệu',
                                'from5to10m' => 'Từ 5 đến 10 triệu',
                                'above10m' => 'Trên 10 triệu',
                            ] as $priceKey => $priceLabel)
                                @php $priceChecked = in_array($priceKey, $selectedPriceRanges, true); @endphp
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="price_ranges[]" value="{{ $priceKey }}" class="filter-check-input hidden" @checked($priceChecked)>
                                    <span class="filter-check-box w-4 h-4 rounded border flex items-center justify-center transition-all group-hover:border-accent-500">
                                        <i data-lucide="check" class="filter-check-icon w-2.5 h-2.5 text-white transition-opacity"></i>
                                    </span>
                                    <span class="filter-check-label filter-check-label-muted text-xs font-bold uppercase tracking-tight group-hover:text-accent-500">
                                        {{ $priceLabel }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-accent-600 to-accent-700 hover:from-accent-500 hover:to-accent-600 hover:shadow-md hover:shadow-accent-500/10 text-white text-xs font-bold uppercase tracking-widest transition-all flex items-center justify-center gap-2 rounded-xl">
                            <i data-lucide="search" class="w-4 h-4"></i> Áp dụng bộ lọc
                        </button>
                        <a href="{{ $resetUrl }}" class="w-full py-3.5 border border-brand-500 text-brand-500 text-xs font-bold uppercase tracking-widest hover:bg-brand-500/5 transition-all flex items-center justify-center gap-2 rounded-xl no-underline">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Đưa Về Mặc Định
                        </a>
                    </div>
                </div>
            </aside>

            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <div>
                        <h1 class="font-['Space_Grotesk'] text-2xl font-black text-slate-800 flex flex-wrap items-center gap-2">
                            <span>Tất cả sản phẩm</span>
                            @if(!empty($selectedCategories))
                                <span class="text-xs font-bold font-sans bg-accent-50 text-accent-600 px-2.5 py-1 rounded-md">
                                    {{ implode(', ', $selectedCategories) }}
                                </span>
                            @endif
                        </h1>
                        <p class="text-xs text-slate-500 font-semibold mt-1">
                            Tìm thấy <strong class="text-brand-500">{{ $totalProducts }}</strong> sản phẩm
                        </p>
                    </div>

                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl shadow-xs">
                        <span class="text-xs font-semibold text-slate-500">Sắp xếp:</span>
                        <select name="sort" class="bg-transparent border-none font-bold text-xs text-slate-800 cursor-pointer outline-none p-0 pr-6 focus:ring-0 focus:outline-none">
                            @foreach($sortLabels as $value => $label)
                                <option value="{{ $value }}" @selected($sortOption === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="text-xs font-bold text-accent-500 hover:text-brand-500 transition-colors">
                            Lọc
                        </button>
                    </div>
                </div>

                @if($totalProducts === 0)
                    <div class="bg-white border border-slate-100 p-12 text-center rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.015)] space-y-4 max-w-xl mx-auto my-12">
                        <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center mx-auto text-sm font-bold">!</div>
                        <h3 class="font-['Space_Grotesk'] font-bold text-slate-800 text-lg">Không tìm thấy sản phẩm phù hợp</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Chúng tôi không tìm thấy kết quả nào tương thích với điều kiện bộ lọc của bạn. Hãy thử thiết lập lại từ khóa tìm kiếm hoặc bỏ tick các ô giá.
                        </p>
                        <a href="{{ $resetUrl }}" class="inline-flex py-2.5 px-5 bg-gradient-to-r from-brand-500 to-brand-600 text-white text-xs font-semibold rounded-lg hover:from-brand-600 hover:to-brand-700 transition-all cursor-pointer no-underline">
                            Xóa Bộ Lọc & Tìm Lại từ đầu
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($paginatedProducts as $product)
                            <a
                                id="product-card-{{ $product['id'] }}"
                                href="{{ route('home.product_detail', ['ma_san_pham' => $product['id']]) }}"
                                class="bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-[0_20px_40px_rgba(0,0,0,0.04)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col cursor-pointer group no-underline"
                            >
                                <div class="relative bg-slate-50/50 aspect-square overflow-hidden flex items-center justify-center p-4 border-b border-slate-100/80">
                                    <img
                                        src="{{ $product['image'] }}"
                                        alt="{{ $product['name'] }}"
                                        class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
                                        referrerPolicy="no-referrer"
                                    />

                                    <div class="absolute top-3 left-3 flex gap-2 z-10">
                                        @if(!empty($product['promoText']))
                                        <span class="bg-amber-500 text-white px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider shadow-sm">
                                            {{ $product['promoText'] }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-4 flex flex-col flex-1">
                                    <span class="font-sans text-xs text-neutral-400 font-medium tracking-wide mb-1 uppercase">{{ $product['category'] }}</span>
                                    <h3 class="font-['Space_Grotesk'] text-base font-semibold text-slate-800 line-clamp-2 group-hover:text-accent-500 transition-colors min-h-[48px]">{{ $product['name'] }}</h3>
                                    <p class="text-[11px] text-neutral-400 line-clamp-2 leading-relaxed">{{ $product['mo_ta_ngan'] }}</p>

                                    <div class="flex items-center gap-1.5 mb-3">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @php
                                                    $starFill = max(0, min(100, (((float) $product['rating']) - ($i - 1)) * 100));
                                                @endphp
                                                <span class="relative inline-block w-3.5 h-3.5 overflow-hidden text-sm leading-none text-gray-200">
                                                    ★
                                                    <span class="absolute left-0 top-0 h-full overflow-hidden text-amber-500" style="--fill: {{ $starFill }}%; width: var(--fill);">★</span>
                                                </span>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-neutral-400 font-sans">({{ $product['reviewsCount'] }})</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-auto w-full">
                                        <div class="text-left">
                                            <span class="text-[11px] text-slate-700 uppercase tracking-wider block font-extrabold leading-none mb-1.5">Đã bán: {{ $product['tong_da_ban'] }}</span>
                                            <span class="text-[10px] text-neutral-400 uppercase tracking-widest block font-bold leading-none mb-1">Chỉ từ</span>
                                            <span class="font-['Space_Grotesk'] text-[15px] font-bold text-accent-600 tracking-tight block leading-none">{{ number_format($product['price'], 0, ',', '.') }}₫</span>
                                        </div>
                                        <span class="w-10 h-10 bg-brand-500 group-hover:bg-brand-600 hover:shadow-[0_4px_10px_rgba(255,79,0,0.3)] text-white rounded-lg flex items-center justify-center transition-all duration-300" title="Xem chi tiết">
                                            <i data-lucide="eye" class="w-5 h-5 text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if($totalPages > 1)
                        <div class="mt-12 flex justify-center items-center gap-2">
                            @if($currentPage > 1)
                                <a href="{{ $pageUrl($currentPage - 1) }}" class="w-10 h-10 rounded-xl border border-slate-200 hover:border-accent-500 flex items-center justify-center hover:bg-slate-50 text-slate-700 cursor-pointer transition-colors no-underline">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-300 opacity-40">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @endif

                            @for($page = 1; $page <= $totalPages; $page++)
                                <a
                                    href="{{ $pageUrl($page) }}"
                                    class="w-10 h-10 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center no-underline {{ $page === $currentPage ? 'bg-accent-500 text-white shadow-sm' : 'border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-accent-500' }}"
                                >
                                    {{ $page }}
                                </a>
                            @endfor

                            @if($currentPage < $totalPages)
                                <a href="{{ $pageUrl($currentPage + 1) }}" class="w-10 h-10 rounded-xl border border-slate-200 hover:border-accent-500 flex items-center justify-center hover:bg-slate-50 text-slate-700 cursor-pointer transition-colors no-underline">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-300 opacity-40">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </form>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.category-parent').forEach((parentInput) => {
            parentInput.addEventListener('change', () => {
                document.querySelectorAll(`.category-child[data-parent-id="${parentInput.dataset.categoryId}"]`).forEach((childInput) => {
                    childInput.checked = parentInput.checked;
                });
            });
        });

        document.querySelectorAll('.category-child').forEach((childInput) => {
            childInput.addEventListener('change', () => {
                const parentInput = document.querySelector(`.category-parent[data-category-id="${childInput.dataset.parentId}"]`);
                if (parentInput) {
                    parentInput.checked = false;
                }
            });
        });
    });
</script>
@endsection
