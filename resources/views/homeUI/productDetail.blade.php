@extends('layouts.app')

@section('title', $productDetail->ten_san_pham)

@section('content')
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

    <main class="flex-1 pt-32 pb-24 px-12 max-w-[1440px] mx-auto w-full uppercase" 
      x-data="productDetail({{ $variants->toJson() }}, {{ json_encode($productDetail->hinh_anh ?? []) }})">
    
    <!-- Breadcrumbs -->
    <nav class="mb-8 flex items-center gap-2 text-gray-500 font-bold text-[10px] tracking-[0.2em]">
        <a href="/" class="hover:text-lime-400 transition-colors">TRANG CHỦ</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="#" class="hover:text-lime-400 transition-colors">LAPTOP</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-lime-400">{{ $productDetail->ten_san_pham }}</span>
    </nav>

    <section class="grid grid-cols-1 lg:grid-cols-12 gap-20">
        <!-- Left: Gallery -->
        <div class="lg:col-span-7 space-y-6">
            <div class="glass-panel aspect-video rounded-none overflow-hidden relative group">
                <img :src="gallery[activeImageIndex]" alt="{{ $productDetail->ten_san_pham }}" class="w-full h-full object-cover">
                <div class="absolute top-4 right-4 bg-lime-400 text-black px-3 py-1 font-black text-[10px] tracking-[0.2em] uppercase">
                    THẾ HỆ MỚI
                </div>

                <!-- Navigation Buttons -->
                <div class="absolute inset-x-4 top-1/2 -translate-y-1/2 flex justify-between opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button @click="prevImage" class="w-12 h-12 flex items-center justify-center bg-black/40 backdrop-blur-md border border-white/10 text-white hover:bg-lime-400 hover:text-black transition-all">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>
                    <button @click="nextImage" class="w-12 h-12 flex items-center justify-center bg-black/40 backdrop-blur-md border border-white/10 text-white hover:bg-lime-400 hover:text-black transition-all">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Single Row Thumbnail Slider -->
            <div class="relative group/thumbs">
                <!-- Arrow Left -->
                <button @click="$refs.thumbTrack.scrollBy({left: -$refs.thumbTrack.offsetWidth, behavior: 'smooth'})" 
                        class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 flex items-center justify-center bg-black/60 backdrop-blur-md border border-white/10 text-white hover:bg-lime-400 hover:text-black transition-all rounded-full opacity-0 group-hover/thumbs:opacity-100">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>

                <!-- Track -->
                <div x-ref="thumbTrack" class="flex gap-4 overflow-x-auto scrollbar-hide scroll-smooth pb-2">
                    <template x-for="(img, idx) in gallery" :key="idx">
                        <div @click="activeImageIndex = idx" 
                             class="glass-panel basis-[calc(25%-12px)] flex-shrink-0 aspect-square flex items-center justify-center cursor-pointer overflow-hidden border-white/5 hover:border-lime-400 transition-all relative">
                            <template x-if="img === 'video'">
                                <div class="text-lime-400 flex flex-col items-center justify-center opacity-60 hover:opacity-100">
                                    <i data-lucide="play-circle" class="w-8 h-8"></i>
                                </div>
                            </template>
                            <template x-if="img !== 'video'">
                                <img :src="img" class="w-full h-full object-cover opacity-60 hover:opacity-100 transition-opacity"
                                     :class="{'opacity-100': activeImageIndex === idx}">
                                <div class="absolute inset-0 border-2 border-lime-400 transition-opacity pointer-events-none"
                                     :class="activeImageIndex === idx ? 'opacity-100' : 'opacity-0'"></div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Arrow Right -->
                <button @click="$refs.thumbTrack.scrollBy({left: $refs.thumbTrack.offsetWidth, behavior: 'smooth'})" 
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
                HỆ THỐNG CHIẾN THUẬT HIỆU NĂNG CAO
            </p>

            <div class="flex items-end gap-6 mb-12">
                <!-- Giá Bán -->
                <div class="text-lime-400 font-display text-4xl font-bold bloom-effect tracking-tighter" x-text="formatPrice(currentVariant.gia_ban)">
                    {{ number_format($variants[0]->gia_ban ?? $productDetail->gia_thap_nhat, 0, ',', '.') }}₫
                </div>
                
                <!-- Giá Niêm Yết (Gạch Ngang) -->
                <template x-if="currentVariant.gia_niem_yet > currentVariant.gia_ban">
                    <div class="flex items-end gap-4 pb-1">
                        <div class="text-gray-500 line-through text-xl font-medium opacity-60" x-text="formatPrice(currentVariant.gia_niem_yet)">
                            {{ number_format($variants[0]->gia_niem_yet ?? 0, 0, ',', '.') }}₫
                        </div>
                        <div class="bg-red-600 text-white px-2 py-0.5 font-black text-[12px] tracking-wider">
                            -<span x-text="Math.round(100 - (currentVariant.gia_ban / currentVariant.gia_niem_yet * 100))"></span>%
                        </div>
                    </div>
                </template>
            </div>

            <!-- Chọn biến thể -->
            <div class="space-y-4 mb-16">
                <header class="text-[10px] font-black tracking-[0.2em] text-gray-400">CHỌN CẤU HÌNH</header>
                <div class="flex flex-wrap gap-3">
                    @foreach($variants as $idx => $variant)
                    @php
                        $ram = collect($variant->thuoc_tinh)->firstWhere('ten', 'RAM')['gia_tri'] ?? '';
                        $ssd = collect($variant->thuoc_tinh)->firstWhere('ten', 'Ổ cứng')['gia_tri'] ?? '';
                    @endphp
                    <button @click="selectVariant({{ $idx }})" 
                            :class="selectedIndex === {{ $idx }} ? 'border-lime-400 text-lime-400 shadow-[0_0_10px_rgba(163,230,53,0.2)]' : 'border-white/5 text-gray-400 hover:border-white/20'"
                            class="py-3 px-4 glass-panel text-[11px] font-black tracking-wider transition-all duration-300">
                        {{ $ram }} / {{ $ssd }} — {{ number_format($variant->gia_ban, 0, ',', '.') }}₫
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-4">
                <button class="w-full py-6 bg-lime-400 text-black font-black text-sm tracking-[0.3em] flex items-center justify-center gap-3 active:scale-95 transition-all">
                    <i data-lucide="shopping-cart" class="w-5 h-5 fill-black"></i>
                    THÊM VÀO GIỎ HÀNG
                </button>
                <button class="w-full py-6 border border-lime-400 text-lime-400 font-black text-sm tracking-[0.3em] hover:bg-lime-400/10 transition-all">
                    MUA NGAY
                </button>
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
                    @php
                        $specs = $productDetail->thuoc_tinh_chung ?? [
                            ['ten' => 'Thương hiệu', 'gia_tri' => $productDetail->ma_thuong_hieu ?? 'N/A'],
                            ['ten' => 'Bảo hành', 'gia_tri' => '12 tháng'],
                            ['ten' => 'Tình trạng', 'gia_tri' => 'Mới 100%'],
                        ];
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
    <section class="mt-40">
        <h2 class="font-display text-4xl font-black mb-12 flex items-center gap-6 text-white">
            <span class="w-1.5 h-10 bg-lime-400 block"></span>
            MÔ TẢ SẢN PHẨM
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 text-white">
            <div class="md:col-span-8 glass-panel p-12 flex flex-col justify-center">
                <h3 class="font-display text-4xl text-lime-400 font-black italic tracking-tighter mb-8 italic">
                    THIẾT KẾ ĐỂ THỐNG TRỊ
                </h3>
                <div class="space-y-6 text-gray-400 font-medium normal-case leading-relaxed text-lg">
                    {!! $productDetail->mo_ta_chi_tiet ?? '<p>Thông tin mô tả sản phẩm đang được cập nhật...</p>' !!}
                </div>
            </div>
            
            <div class="md:col-span-4 glass-panel overflow-hidden group">
                <img src="{{ $productDetail->link_anh_dai_dien }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            </div>

            <div class="md:col-span-4 glass-panel overflow-hidden group">
                <img src="{{ $productDetail->link_anh_dai_dien }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            </div>

            <div class="md:col-span-8 glass-panel p-16 flex flex-col items-center justify-center text-center space-y-8 border-l-4 border-lime-400">
                <i data-lucide="snowflake" class="w-16 h-16 text-lime-400 animate-pulse"></i>
                <h3 class="font-display text-4xl font-black tracking-tight uppercase">CÔNG NGHỆ CRYO-FLOW</h3>
                <p class="text-gray-400 text-xl tracking-[0.05em] max-w-2xl font-bold uppercase">
                    TĂNG 35% HIỆU QUẢ TẢN NHIỆT SO VỚI THẾ HỆ G4 SERIES, ĐẢM BẢO DUY TRÌ TỐC ĐỘ XUNG NHỊP TỐI ĐA TRONG THỜI GIAN DÀI.
                </p>
            </div>
        </div>
    </section>[0.05em] max-w-2xl font-bold uppercase">
                    TĂNG 35% HIỆU QUẢ TẢN NHIỆT SO VỚI THẾ HỆ G4 SERIES, ĐẢM BẢO DUY TRÌ TỐC ĐỘ XUNG NHỊP TỐI ĐA TRONG THỜI GIAN DÀI.
                </p>
            </div>
        </div>
    </section>

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

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productDetail', (variantsData, galleryData) => ({
            variants: variantsData,
            // Gộp ảnh đại diện vào đầu mảng gallery
            gallery: ["{{ $productDetail->link_anh_dai_dien }}", ...galleryData],
            activeImageIndex: 0,
            selectedIndex: 0,
            
            get currentVariant() {
                return this.variants[this.selectedIndex] || {};
            },
            
            nextImage() {
                this.activeImageIndex = (this.activeImageIndex + 1) % this.gallery.length;
            },
            
            prevImage() {
                this.activeImageIndex = (this.activeImageIndex - 1 + this.gallery.length) % this.gallery.length;
            },
            
            formatPrice(price) {
                if (!price) return '0₫';
                return new Intl.NumberFormat('vi-VN').format(price) + '₫';
            },
            
            selectVariant(index) {
                this.selectedIndex = index;
                // Nếu biến thể có ảnh riêng, có thể thêm logic cập nhật activeImageIndex ở đây
            }
        }));
    });

    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
    });
</script>
@endsection
