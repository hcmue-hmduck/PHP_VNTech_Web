@extends('layouts.app')

@section('title', $productDetail->ten_san_pham)

@section('content')
@php
    $selectedVariant = $variants->firstWhere('ma_bien_the', request('ma_bien_the')) ?? $variants->first();
    $flashSaleInfo = $selectedVariant->flash_sale_info;
    $flashSaleCampaign = $selectedVariant->flash_sale_campaign;
    $isFlashSaleActive = $flashSaleInfo && $flashSaleCampaign;
@endphp
<!-- Custom Styles for Product Detail -->
<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .bloom-effect {
        text-shadow: 0 0 15px rgba(163, 230, 53, 0.5);
    }
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

    <main class="flex-1 pt-32 pb-24 px-12 max-w-[1440px] mx-auto w-full uppercase">
    
    <!-- Breadcrumbs -->
    <nav class="mb-8 flex items-center gap-2 text-gray-500 font-bold text-[10px] tracking-[0.2em]">
        <a href="/" class="hover:text-lime-400 transition-colors">TRANG CHỦ</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-lime-400">{{ $productDetail->ten_san_pham }}</span>
    </nav>

    <section class="grid grid-cols-1 lg:grid-cols-12 gap-20">
        <!-- Left: Gallery -->
        <div class="lg:col-span-7 space-y-6">
            <div class="glass-panel aspect-video rounded-none overflow-hidden relative group">
                <img id="main-product-image" src="{{ $selectedVariant->link_anh_bien_the ?: $productDetail->link_anh_dai_dien }}" alt="{{ $productDetail->ten_san_pham }}" class="w-full h-full object-cover">
                <div class="absolute top-4 right-4 bg-lime-400 text-black px-3 py-1 font-black text-[10px] tracking-[0.2em] uppercase">
                    THẾ HỆ MỚI
                </div>

                <!-- Navigation Buttons -->
                <div class="absolute inset-x-4 top-1/2 -translate-y-1/2 flex justify-between opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button type="button" onclick="prevImage()" class="w-12 h-12 flex items-center justify-center bg-black/40 backdrop-blur-md border border-white/10 text-white hover:bg-lime-400 hover:text-black transition-all">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>
                    <button type="button" onclick="nextImage()" class="w-12 h-12 flex items-center justify-center bg-black/40 backdrop-blur-md border border-white/10 text-white hover:bg-lime-400 hover:text-black transition-all">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Single Row Thumbnail Slider -->
            <div class="relative group/thumbs">
                <!-- Arrow Left -->
                <button type="button" onclick="document.getElementById('thumbTrack').scrollBy({left: -200, behavior: 'smooth'})" 
                        class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 flex items-center justify-center bg-black/60 backdrop-blur-md border border-white/10 text-white hover:bg-lime-400 hover:text-black transition-all rounded-full opacity-0 group-hover/thumbs:opacity-100">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>

                <!-- Track -->
                <div id="thumbTrack" class="flex gap-4 overflow-x-auto scrollbar-hide scroll-smooth pb-2">
                    @php
                        $galleryImages = array_merge([$selectedVariant->link_anh_bien_the ?: $productDetail->link_anh_dai_dien], $productDetail->hinh_anh ?? []);
                    @endphp
                    @foreach($galleryImages as $idx => $img)
                        <div data-index="{{ $idx }}" 
                             class="thumb-container glass-panel basis-[calc(25%-12px)] flex-shrink-0 aspect-square flex items-center justify-center cursor-pointer overflow-hidden border border-white/5 hover:border-lime-400 transition-all relative">
                            <img src="{{ $img }}" class="w-full h-full object-cover transition-opacity {{ $idx === 0 ? 'opacity-100' : 'opacity-60' }}">
                            <div class="thumb-border absolute inset-0 border-2 border-lime-400 transition-opacity pointer-events-none {{ $idx === 0 ? 'opacity-100' : 'opacity-0' }}"></div>
                        </div>
                    @endforeach
                </div>

                <!-- Arrow Right -->
                <button type="button" onclick="document.getElementById('thumbTrack').scrollBy({left: 200, behavior: 'smooth'})" 
                        class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 flex items-center justify-center bg-black/60 backdrop-blur-md border border-white/10 text-white hover:bg-lime-400 hover:text-black transition-all rounded-full opacity-0 group-hover/thumbs:opacity-100">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Right: Info -->
        <div class="lg:col-span-5 flex flex-col pt-4">
            <h1 class="font-display text-5xl font-black mb-2 tracking-tighter text-white">
                {{ $productDetail->ten_san_pham }}
            </h1>
            <p class="text-gray-400 font-bold tracking-[0.2em] text-sm mb-10">
                {{ $productDetail->mo_ta_ngan }}
            </p>

            <!-- Khối Giá / Flash Sale -->
            <div class="mb-12">
                @if($isFlashSaleActive)
                    <!-- Khối Flash Sale (Hiển thị nếu có Flash Sale) -->
                    <div class="space-y-4 w-full">
                        <!-- Flash Sale Banner nổi bật -->
                        <div class="bg-gradient-to-r from-lime-950/80 to-slate-900/80 border border-lime-500/35 rounded-xl p-4 flex flex-col md:flex-row justify-between items-center gap-4 shadow-[0_0_15px_rgba(0,255,102,0.15)]">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-lime-500/20 flex items-center justify-center animate-pulse border border-lime-400/30">
                                    <i data-lucide="zap" class="w-5 h-5 text-lime-400 fill-lime-400"></i>
                                </div>
                                <div>
                                    <div class="text-lime-400 font-black text-sm tracking-widest uppercase italic flex items-center gap-2">
                                        FLASHSALE
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Countdown Timer -->
                            @php
                                $endTimeStr = is_string($flashSaleCampaign->ket_thuc) 
                                    ? $flashSaleCampaign->ket_thuc 
                                    : ($flashSaleCampaign->ket_thuc instanceof \Carbon\Carbon 
                                        ? $flashSaleCampaign->ket_thuc->toIso8601String() 
                                        : (string)$flashSaleCampaign->ket_thuc);
                            @endphp
                            <div id="flash-sale-countdown" data-endtime="{{ $endTimeStr }}" class="flex items-center gap-2 bg-black/40 px-4 py-2 rounded-lg border border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">KẾT THÚC SAU:</span>
                                <div class="flex items-center gap-1.5 font-mono text-sm font-black text-lime-400">
                                    <span id="countdown-days" class="bg-lime-950/80 px-2 py-1 rounded border border-lime-400/20">00</span>
                                    <span>:</span>
                                    <span id="countdown-hours" class="bg-lime-950/80 px-2 py-1 rounded border border-lime-400/20">00</span>
                                    <span>:</span>
                                    <span id="countdown-minutes" class="bg-lime-950/80 px-2 py-1 rounded border border-lime-400/20">00</span>
                                    <span>:</span>
                                    <span id="countdown-seconds" class="bg-lime-950/80 px-2 py-1 rounded border border-lime-400/20 text-white animate-pulse">00</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Khối giá Flash Sale -->
                        <div class="flex items-end gap-6">
                            <div>
                                <div class="text-[10px] text-lime-400 font-black tracking-widest uppercase mb-1">GIÁ FLASHSALE</div>
                                <div class="text-lime-400 font-display text-5xl font-black bloom-effect tracking-tighter">
                                    {{ number_format($flashSaleInfo->gia_flash_sale, 0, ',', '.') }}₫
                                </div>
                            </div>
                            
                            <div class="flex flex-col pb-1">
                                <span class="text-[10px] text-slate-500 font-bold tracking-widest uppercase mb-0.5">GIÁ GỐC</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500 line-through text-lg font-bold opacity-60">
                                        {{ number_format($selectedVariant->gia_ban, 0, ',', '.') }}₫
                                    </span>
                                    <span class="bg-lime-500 text-black px-2 py-0.5 font-black text-[11px] tracking-wider rounded">
                                        -{{ round(100 - ($flashSaleInfo->gia_flash_sale / $selectedVariant->gia_ban * 100)) }}%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tiến độ bán hàng -->
                        @php
                            $daBan = $flashSaleInfo->so_luong_da_ban ?? 0;
                            $gioiHan = $flashSaleInfo->so_luong_gioi_han ?? 1;
                            $percent = round(($daBan / $gioiHan) * 100);
                        @endphp
                        <div class="mt-4 max-w-md">
                            <div class="flex justify-between items-center text-xs font-bold text-slate-400 mb-1.5 uppercase tracking-widest">
                                <span>Đã bán: <span class="text-lime-400 font-black">{{ $daBan }}</span> / <span>{{ $gioiHan }}</span> sản phẩm</span>
                                <span>{{ $percent }}%</span>
                            </div>
                            <div class="h-3 w-full bg-slate-950 rounded-full overflow-hidden border border-white/5 relative">
                                <div id="flash-sale-progress-bar" class="h-full bg-gradient-to-r from-emerald-600 to-lime-400 rounded-full transition-all duration-500" 
                                     data-width="{{ min(100, $percent) }}%"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Khối giá thường (Hiển thị nếu KHÔNG có Flash Sale) -->
                    <div class="flex items-end gap-6">
                        <div class="text-lime-400 font-display text-4xl font-bold bloom-effect tracking-tighter">
                            {{ number_format($selectedVariant->gia_ban, 0, ',', '.') }}₫
                        </div>
                        
                        @if($selectedVariant->gia_niem_yet > $selectedVariant->gia_ban)
                            <div class="flex items-end gap-4 pb-1">
                                <div class="text-gray-500 line-through text-xl font-medium opacity-60">
                                    {{ number_format($selectedVariant->gia_niem_yet, 0, ',', '.') }}₫
                                </div>
                                <div class="bg-red-600 text-white px-2 py-0.5 font-black text-[12px] tracking-wider">
                                    -{{ round(100 - ($selectedVariant->gia_ban / $selectedVariant->gia_niem_yet * 100)) }}%
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Chọn biến thể -->
            <div class="space-y-4 mb-16">
                <header class="text-[10px] font-black tracking-[0.2em] text-gray-400">CHỌN CẤU HÌNH</header>
                <div class="flex flex-wrap gap-3">
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
                       class="py-3 px-4 glass-panel text-[11px] font-black tracking-wider transition-all duration-300 flex items-center gap-2 border {{ $isActive ? 'border-lime-400 text-lime-400 shadow-[0_0_10px_rgba(163,230,53,0.2)]' : 'border-white/5 text-gray-400 hover:border-white/20' }}">
                        <span>{{ $thong_tin_bien_the }}</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-600/40"></span>
                        @if($variant->flash_sale_info)
                            <span class="text-lime-400 font-extrabold flex items-center gap-0.5">
                                <i data-lucide="zap" class="w-3.5 h-3.5 fill-lime-400 text-lime-400 inline"></i>
                                {{ number_format($variant->flash_sale_info->gia_flash_sale, 0, ',', '.') }}₫
                            </span>
                        @else
                            <span>{{ number_format($variant->gia_ban, 0, ',', '.') }}₫</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-4">
                <a href="{{ route('cart.addItem') }}?ma_bien_the={{ $selectedVariant->ma_bien_the }}" 
                   class="w-full py-6 bg-lime-400 text-black hover:bg-lime-300 font-black text-sm tracking-[0.3em] flex items-center justify-center gap-3 active:scale-95 transition-all text-center shadow-[0_0_15px_rgba(0,255,102,0.15)]">
                    <i data-lucide="shopping-cart" class="w-5 h-5 fill-black"></i>
                    THÊM VÀO GIỎ HÀNG
                </a>

                <a href="{{ route('payment.view', $selectedVariant->ma_bien_the) }}"
                   class="w-full py-6 border border-lime-400 text-lime-400 hover:bg-lime-400/10 font-black text-sm tracking-[0.3em] transition-all flex items-center justify-center active:scale-95 text-center">
                    MUA NGAY
                </a>
            </div>
        </div>
    </section>

    <!-- Technical Specs -->
    <section class="mt-40">
        <h2 class="font-display text-4xl font-black mb-12 flex items-center gap-6 text-white">
            <span class="w-1.5 h-10 bg-lime-400 block"></span>
            THÔNG SỐ KỸ THUẬT
        </h2>
        <div class="glass-panel overflow-hidden">
            <table class="w-full text-left">
                <tbody class="divide-y divide-white/5">
                    @foreach($productDetail->thong_so_ky_thuat_chung ?? [] as $row)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="p-8 font-black text-[11px] tracking-[0.2em] text-gray-400 w-1/3 group-hover:text-lime-400 transition-colors uppercase">
                            {{ $row['ten'] ?? '' }}
                        </td>
                        <td class="p-8 text-white font-medium text-sm normal-case">
                            {{ $row['gia_tri'] ?? '' }}
                        </td>
                    </tr>
                    @endforeach

                    <!-- Thông số kỹ thuật riêng của biến thể đang chọn -->
                    @if(isset($selectedVariant->thong_so_ky_thuat_rieng) && is_array($selectedVariant->thong_so_ky_thuat_rieng))
                        @foreach($selectedVariant->thong_so_ky_thuat_rieng as $spec)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="p-8 font-black text-[11px] tracking-[0.2em] text-gray-400 w-1/3 group-hover:text-lime-400 transition-colors uppercase">
                                {{ $spec['ten'] ?? '' }}
                            </td>
                            <td class="p-8 text-white font-medium text-sm normal-case">
                                {{ $spec['gia_tri'] ?? '' }}
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </section>

    <!-- Technical Specs -->
    <section class="mt-40">
        <h2 class="font-display text-4xl font-black mb-12 flex items-center gap-6 text-white">
            <span class="w-1.5 h-10 bg-lime-400 block"></span>
            THÔNG TIN THÊM
        </h2>
        <div class="glass-panel overflow-hidden">
            <table class="w-full text-left">
                <tbody class="divide-y divide-white/5">
                    @php
                        $specs = $productDetail->thong_tin_them;
                    @endphp
                    @foreach($specs as $row)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="p-8 font-black text-[11px] tracking-[0.2em] text-gray-400 w-1/3 group-hover:text-lime-400 transition-colors uppercase">
                            {{ $row['ten'] ?? '' }}
                        </td>
                        <td class="p-8 text-white font-medium text-sm normal-case">
                            {{ $row['gia_tri'] ?? '' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- Bento Product Description -->
    @if ($productDetail->mo_ta_chi_tiet)
        <section class="mt-40">
            <!-- Tiêu đề Section -->
            <h2 class="font-display text-4xl font-black mb-12 flex items-center gap-6 text-white">
                <span class="w-1.5 h-10 bg-lime-400 block"></span>
                MÔ TẢ SẢN PHẨM
            </h2>
            
            <div class="space-y-8">
                <!-- Khung chứa nội dung từ Editor -->
                <div class="glass-panel p-8 md:p-12 overflow-hidden">
                    <div class="prose prose-lg prose-invert max-w-none 
                                prose-headings:font-display prose-headings:text-lime-400 prose-headings:font-black 
                                prose-p:text-gray-300 prose-p:leading-relaxed 
                                prose-img:rounded-2xl prose-img:shadow-2xl prose-img:w-full prose-img:object-cover prose-img:my-8
                                prose-a:text-lime-400 hover:prose-a:text-lime-300 transition-colors
                                prose-strong:text-white">
                        {!! $productDetail->mo_ta_chi_tiet !!}
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Reviews -->
    <section class="mt-40">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-8">
            <div>
                <h2 class="font-display text-4xl font-black mb-2 flex items-center gap-6 text-white">
                    <span class="w-1.5 h-10 bg-lime-400 block"></span>
                    PHẢN HỒI TỪ OPERATOR
                </h2>
                <p class="text-gray-500 text-[10px] font-black tracking-[0.3em] ml-8 uppercase">
                    DỮ LIỆU HIỆU NĂNG ĐÃ ĐƯỢC XÁC THỰC
                </p>
            </div>
            <div class="text-right">
                <div class="flex items-center gap-3 text-lime-400 mb-2">
                    <i data-lucide="star" class="w-8 h-8 fill-lime-400"></i>
                    <span class="text-5xl font-display font-black italic">4.8</span>
                    <span class="text-gray-500 text-2xl opacity-50">/ 5.0</span>
                </div>
                <p class="text-gray-500 text-[10px] font-black tracking-widest uppercase">
                    DỰA TRÊN 1.248 BÁO CÁO TRIỂN KHAI
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @php
                $reviews = [
                    [
                        'name' => 'Alex_Vortex',
                        'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDLREUz_-iIyRNpbNGQ71fZWgQQkbeLSyWrMgYxwibJTaVC97a3yxKmSZBazf8DxEOpOQxA-K1fUshf5BQyAa4ynB05JGOWr6fvlH8uJ6i1EqdrY-TFTFbZTGpwN4MfYQaL26EPE3TKgybQoJaxFOHc7r_ZyttpS2KvhK_vIhQUfF0jB1sTdHCQHmETdNa_aKZj-GeDbjOzhOMJcXcrEGLJ04qHKpITJkU5x1SxIapvS3MKIuAyC4fixtXYwBpX_Xmu12DsQQjeSjbJ',
                        'rating' => 5,
                        'text' => 'Khả năng quản lý nhiệt trên chiếc máy này thật kinh ngạc. Tôi đã chạy Cyberpunk ở mức thiết lập tối đa trong 4 giờ và CPU chưa bao giờ bị hạ xung. VNTech thực sự đã làm rất tốt phần tản nhiệt.'
                    ],
                    [
                        'name' => 'Neon_Rebel',
                        'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBabewNTVTEQP_9W0ijnA8kFDxBPe4BDpAAj1ldgpwpxFkKMvldf7jNc_CdQjykz5AYFXDp71-8Hk9GNww14YNIM39wYSpgT7bvrXAlbAfaPt9mTYECLQPwMWNHQuty4alnBGDkpDj54sxQCbhzYbvSRT3nUhX9Vx2QAK8jSH45GgsId6Vq8IKfIxOmRVvZlF97lKbOM93O4YuLjDrb8oz5py8yXNLt9I5m0veq4eToJGpdoazlGiom2qfE3Y_TMgJylzwo4hQhTGDd',
                        'rating' => 4,
                        'text' => 'Màn hình 240Hz là điểm sáng nhất. Độ chính xác màu sắc hoàn hảo cho công việc chỉnh sửa của tôi, nhưng tốc độ trong các game FPS mới là nơi nó thực sự tỏa sáng. Khoản đầu tư tốt nhất năm nay.'
                    ]
                ];
            @endphp
            @foreach($reviews as $review)
            <div class="glass-panel p-10 space-y-8 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 border-2 border-lime-400 rounded-none overflow-hidden p-1">
                        <img src="{{ $review['avatar'] }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="font-black tracking-tighter text-white text-lg">{{ $review['name'] }}</div>
                        <div class="flex text-lime-400 mt-1">
                            @for($i = 0; $i < 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i < $review['rating'] ? 'fill-lime-400' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-gray-400 italic font-medium normal-case text-lg leading-relaxed border-l border-white/10 pl-6">
                    "{{ $review['text'] }}"
                </p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Feature Grid Extras -->
    <section class="mt-40 grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $features = [
                ['icon' => 'cpu', 'label' => 'POWERED BY INTEL CORE i9'],
                ['icon' => 'zap', 'label' => '330W GAN CHARGER'],
                ['icon' => 'monitor', 'label' => '240HZ 2K IPS DISPLAY'],
                ['icon' => 'hard-drive', 'label' => 'GEN 4 NVMe SSD'],
                ['icon' => 'shield-check', 'label' => '3 YEAR WARRANTY'],
                ['icon' => 'headset', 'label' => '24/7 ELITE SUPPORT']
            ];
        @endphp
        @foreach($features as $f)
        <div class="glass-panel p-8 flex flex-col items-center justify-center text-center gap-4 group hover:bg-lime-400/5 transition-all">
            <div class="text-lime-400 group-hover:scale-110 transition-transform">
                <i data-lucide="{{ $f['icon'] }}" class="w-6 h-6"></i>
            </div>
            <span class="text-[9px] font-black tracking-[0.2em] text-gray-500 group-hover:text-lime-400 transition-colors">{{ $f['label'] }}</span>
        </div>
        @endforeach
    </section>
</main>
@endsection

<script id="gallery-json" type="application/json">
    {!! json_encode(array_merge([$selectedVariant->link_anh_bien_the ?: $productDetail->link_anh_dai_dien], $productDetail->hinh_anh ?? [])) !!}
</script>

@section('scripts')
<script>
    // Initialize Lucide icons and progress bar
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Set progress bar width dynamically to avoid inline style lint issues
        const progressBar = document.getElementById('flash-sale-progress-bar');
        if (progressBar) {
            progressBar.style.width = progressBar.getAttribute('data-width');
        }

        // Bind click events to thumbnail elements
        const thumbs = document.querySelectorAll('.thumb-container');
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                const index = parseInt(thumb.getAttribute('data-index'), 10);
                changeMainImage(index);
            });
        });

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
            const border = thumb.querySelector('.thumb-border');
            const img = thumb.querySelector('img');
            if (idx === index) {
                if (border) {
                    border.classList.remove('opacity-0');
                    border.classList.add('opacity-100');
                }
                if (img) {
                    img.classList.remove('opacity-60');
                    img.classList.add('opacity-100');
                }
            } else {
                if (border) {
                    border.classList.remove('opacity-100');
                    border.classList.add('opacity-0');
                }
                if (img) {
                    img.classList.remove('opacity-100');
                    img.classList.add('opacity-60');
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
</script>
@endsection
