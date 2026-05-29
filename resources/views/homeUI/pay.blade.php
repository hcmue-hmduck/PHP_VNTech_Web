@extends('layouts.app')

@section('title', 'Thanh toán | VNTech Protocol')

@push('styles')
<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .neo-label {
        display: block;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: rgba(255, 255, 255, 0.4);
        margin-bottom: 0.5rem;
    }

    .neo-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem;
        color: white;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .neo-input:focus {
        border-color: #a3e635; /* lime-400 */
        background: rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 20px rgba(163, 230, 53, 0.15);
    }

    .primary-glow {
        text-shadow: 0 0 15px rgba(163, 230, 53, 0.5);
    }
</style>
@endpush

@section('content')

@php
    $tongTien = collect($cartItems ?? [])->sum(fn ($item) => ($item['gia_ban'] ?? 0) * ($item['so_luong'] ?? 0));
    $tamTinh = $tongTien;
    $ma_voucher = request('ma_voucher');
    $giam_gia = 0;
    $MaVoucher = '';
    if ($ma_voucher) {
        $check_voucher = $voucher->firstWhere('ten_voucher', $ma_voucher);
        if ($check_voucher) {
            $MaVoucher = $check_voucher->ma_voucher;
            if ($check_voucher->hinh_thuc_giam === 'percent') {
                $giam_gia = $tongTien * ($check_voucher->gia_tri_giam / 100);
            }
            else {
                $giam_gia = $check_voucher->gia_tri_giam;
            }
            if ($check_voucher->muc_giam_toi_da > 0 && $giam_gia > $check_voucher->muc_giam_toi_da) {
                $giam_gia = $check_voucher->muc_giam_toi_da;
            }
            $tongTien = max(0, $tongTien - $giam_gia);
        }
    }
@endphp

@if ($errors->any())
    <div class="mb-8 rounded-2xl border border-red-400/30 bg-red-500/10 p-5 text-red-100 shadow-[0_0_30px_rgba(248,113,113,0.12)]">
        <p class="mb-3 font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.2em] text-red-300">
            Không thể tạo đơn hàng
        </p>
        <ul class="space-y-1 text-sm text-red-100/90">
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $formatAddress = function ($detail, $ward, $district, $province) {
        return collect([$detail, $ward, $district, $province])
            ->filter(fn ($part) => filled($part))
            ->implode(', ');
    };
@endphp

<div class="pt-32 pb-24 px-6 max-w-[1440px] mx-auto">
<main>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Page Header (Spans full width) -->
        <div class="lg:col-span-12 mb-4">
            <div class="mb-6 flex justify-start animate-fadeInUp">
                <a href="{{ url('/') }}" 
                class="flex items-center gap-2 px-4 py-2 border border-white/5 bg-white/[0.02] hover:bg-neon-green/5 hover:border-neon-green/40 rounded-lg text-gray-400 hover:text-neon-green text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group shadow-sm">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1"></i>
                    <span>Trang chủ</span>
                </a>
            </div>
            <h1 class="font-['Space_Grotesk'] text-5xl md:text-6xl font-bold uppercase tracking-tight text-white">
                Thanh Toán
            </h1>
        </div>

        <!-- ================= LEFT COLUMN (8/12) ================= -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- PHẦN 1: QUẢN LÝ ĐỊA CHỈ GIAO HÀNG -->
            <section class="glass-panel p-8 relative overflow-hidden group shadow-[0_0_30px_rgba(0,0,0,0.12)]">
                <div class="absolute top-0 left-0 w-1 h-full bg-lime-400"></div>
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-white/5">
                    <div class="w-10 h-10 rounded-full bg-lime-400/10 flex items-center justify-center">
                        <i data-lucide="truck" class="text-lime-400 w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="font-['Space_Grotesk'] text-2xl font-semibold text-white uppercase tracking-wider leading-none">Thông Tin Giao Hàng</h2>
                        <p class="text-[10px] text-white/35 uppercase tracking-[0.25em] mt-2">Chọn hoặc thêm địa chỉ nhận hàng</p>
                    </div>
                </div>

                @php
                    $defaultAddress = $user_address->firstWhere('is_default', true) ?? $user_address->first();
                    $hasAddress = $user_address->isNotEmpty();
                    $fullAddress = $defaultAddress
                        ? $formatAddress($defaultAddress->dia_chi_chi_tiet, $defaultAddress->phuong_xa, $defaultAddress->quan_huyen, $defaultAddress->tinh_thanh)
                        : '';
                @endphp

                @if($hasAddress && $defaultAddress)
                <div x-data="{ showList: false }">
                    {{-- Card địa chỉ đang chọn --}}
                    <div class="space-y-4 bg-white/5 p-6 rounded-xl border border-white/10 mb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span id="disp_ho_ten" class="text-white font-bold text-base">{{ $defaultAddress->ho_ten }}</span>
                                    <span class="text-xs bg-lime-400/10 text-lime-400 px-2 py-0.5 font-bold uppercase tracking-wider">Đang chọn</span>
                                </div>
                                <p id="disp_sdt" class="text-gray-400 text-sm font-mono mb-1">{{ $defaultAddress->so_dien_thoai }}</p>
                                <p id="disp_dc" class="text-gray-300 text-sm">{{ $fullAddress }}</p>
                            </div>
                            <button type="button" @click="showList = !showList"
                                class="flex items-center gap-2 text-[10px] uppercase tracking-widest font-bold text-gray-400 hover:text-lime-400 border border-white/10 hover:border-lime-400/40 px-3 py-2 rounded-lg transition-all flex-shrink-0">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                <span x-text="showList ? 'Đóng' : 'Đổi địa chỉ'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Danh sách địa chỉ để đổi --}}
                    <div x-show="showList" x-transition class="mb-4 space-y-2 p-4 bg-white/[0.03] rounded-xl border border-white/10">
                        <p class="text-[10px] text-lime-400 font-bold uppercase tracking-widest mb-3">Chọn địa chỉ giao hàng</p>
                        @foreach($user_address as $addr)
                            @php
                                $addrFull = $formatAddress($addr->dia_chi_chi_tiet, $addr->phuong_xa, $addr->quan_huyen, $addr->tinh_thanh);
                            @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-white/10 hover:border-lime-400/40 hover:bg-white/5 cursor-pointer group transition-all"
                            @click="document.getElementById('disp_ho_ten').textContent = {{ Js::from($addr->ho_ten) }};
                            document.getElementById('disp_sdt').textContent = {{ Js::from($addr->so_dien_thoai) }};
                            document.getElementById('disp_dc').textContent = {{ Js::from($addrFull) }};
                            
                            let inputHoTen = document.getElementById('sel_ho_ten');
                            let inputSdt = document.getElementById('sel_sdt');
                            let inputDc = document.getElementById('sel_dc');
                            
                            inputHoTen.value = {{ Js::from($addr->ho_ten) }};
                            inputSdt.value = {{ Js::from($addr->so_dien_thoai) }};
                            inputDc.value = {{ Js::from($addrFull) }};
                            
                            // Kích hoạt event giả lập để ép các thư viện nhận diện dữ liệu mới
                            inputHoTen.dispatchEvent(new Event('input'));
                            inputSdt.dispatchEvent(new Event('input'));
                            inputDc.dispatchEvent(new Event('input'));
                            
                            showList = false;">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-white text-sm font-bold group-hover:text-lime-400 transition-colors">{{ $addr->ho_ten }}</span>
                                    @if($addr->is_default)
                                        <span class="text-[9px] bg-lime-400/10 text-lime-400 px-1.5 py-0.5 font-bold uppercase">Mặc định</span>
                                    @endif
                                </div>
                                <p class="text-gray-500 text-xs font-mono">{{ $addr->so_dien_thoai }}</p>
                                <p class="text-gray-400 text-xs truncate">{{ $addrFull }}</p>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-600 group-hover:text-lime-400 flex-shrink-0 transition-colors"></i>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="flex flex-col items-center justify-center p-8 bg-white/5 rounded-xl border border-white/10 text-center mb-4">
                    <i data-lucide="map-pin-off" class="w-10 h-10 text-gray-500 mb-3 opacity-50"></i>
                    <p class="text-white/50 text-xs mb-4 uppercase tracking-wider font-bold">Bạn chưa có địa chỉ nhận hàng</p>
                </div>
                @endif

                {{-- Form thêm địa chỉ mới --}}
                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                        class="w-full py-2.5 border border-dashed border-white/15 hover:border-lime-400/40 text-gray-500 hover:text-lime-400 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-3 h-3" :class="open ? 'rotate-45' : ''" style="transition: transform 0.2s"></i>
                        <span x-text="open ? 'Hủy' : 'Thêm địa chỉ mới'"></span>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="mt-2">
                        <form action="{{ route('user-address.store') }}" method="POST" class="bg-white/[0.03] border border-white/10 rounded-xl p-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Họ và Tên</label>
                                    <input type="text" name="ho_ten" required placeholder="Nguyễn Văn A"
                                           class="w-full px-3 py-2 text-xs bg-white/5 border border-white/10 rounded-lg text-white placeholder:text-white/20 focus:border-lime-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Số Điện Thoại</label>
                                    <input type="text" name="so_dien_thoai" required placeholder="0900 000 000"
                                           class="w-full px-3 py-2 text-xs bg-white/5 border border-white/10 rounded-lg text-white placeholder:text-white/20 focus:border-lime-400 focus:outline-none">
                                </div>
                            </div>
                            <div
                                x-data="{
                                    provinces: [],
                                    wards: [],
                                    selectedProvince: '',
                                    selectedWard: '',
                                    async init() {
                                        try {
                                            const res = await fetch('https://provinces.open-api.vn/api/v2/p/');
                                            this.provinces = await res.json();
                                        } catch (e) {
                                            console.error('Lỗi tải danh mục Tỉnh/Thành', e);
                                        }
                                    },
                                    async fetchWards() {
                                        this.wards = [];
                                        this.selectedWard = '';
                                        if (!this.selectedProvince) return;

                                        try {
                                            const res = await fetch(`https://provinces.open-api.vn/api/v2/p/${this.selectedProvince}?depth=2`);
                                            const data = await res.json();
                                            this.wards = data.wards || [];
                                        } catch (e) {
                                            console.error('Lỗi tải danh mục Phường/Xã', e);
                                        }
                                    }
                                }"
                            >
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    {{-- Tỉnh / Thành Phố --}}
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Tỉnh / Thành Phố</label>
                                        <select x-model="selectedProvince"
                                                @change="fetchWards()"
                                                class="w-full px-3 py-2 text-xs bg-white/5 border border-white/10 rounded-lg text-white focus:border-lime-400 focus:outline-none appearance-none cursor-pointer">
                                            <option value="" class="bg-gray-900 text-gray-400">-- Chọn Tỉnh/TP --</option>
                                            <template x-for="p in provinces" :key="p.code">
                                                <option :value="p.code" x-text="p.name" class="bg-gray-900 text-white"></option>
                                            </template>
                                        </select>
                                        <input type="hidden" name="tinh_thanh" :value="provinces.find(p => String(p.code) === String(selectedProvince))?.name || ''">
                                    </div>

                                    {{-- Phường / Xã --}}
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Phường / Xã</label>
                                        <select x-model="selectedWard"
                                                :disabled="!selectedProvince"
                                                class="w-full px-3 py-2 text-xs bg-white/5 border border-white/10 rounded-lg text-white focus:border-lime-400 focus:outline-none appearance-none cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                            <option value="" class="bg-gray-900 text-gray-400">-- Chọn Phường/Xã --</option>
                                            <template x-for="w in wards" :key="w.code">
                                                <option :value="w.code" x-text="w.name" class="bg-gray-900 text-white"></option>
                                            </template>
                                        </select>
                                        <input type="hidden" name="phuong_xa" :value="wards.find(w => String(w.code) === String(selectedWard))?.name || ''">
                                    </div>
                                </div>
                                <input type="hidden" name="quan_huyen" value="">
                            </div>
                            <div class="mb-3">
                                <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Địa chỉ chi tiết</label>
                                <input type="text" name="dia_chi_chi_tiet" required placeholder="Số nhà, tên đường..."
                                       class="w-full px-3 py-2 text-xs bg-white/5 border border-white/10 rounded-lg text-white placeholder:text-white/20 focus:border-lime-400 focus:outline-none">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-lime-400 hover:bg-lime-300 text-black font-black text-[10px] tracking-widest uppercase rounded-lg active:scale-[0.98] transition-all">
                                LƯU ĐỊA CHỈ
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- PHẦN 2: THÔNG TIN THANH TOÁN (FORM CHÍNH) -->
            <form id="checkout-form" method="POST" action="{{ route('order.store') }}" x-data="{ paymentMethod: 'cod', cartItems: {{ json_encode($cartItems ?? []) }} }" class="space-y-8">
                @csrf
                <input type="hidden" name="ma_don_hang" value="">
                <input type="hidden" name="ma_nguoi_dung" value="{{ auth()->id() ?? 'guest' }}">
                <input type="hidden" name="ho_ten_nguoi_nhan" id="sel_ho_ten" value="{{ $defaultAddress->ho_ten ?? '' }}">
                <input type="hidden" name="so_dien_thoai_nhan" id="sel_sdt" value="{{ $defaultAddress->so_dien_thoai ?? '' }}">
                <input type="hidden" name="dia_chi_giao_hang" id="sel_dc" value="{{ $fullAddress }}">
                <input type="hidden" name="tong_tien_hang" value="{{ $tamTinh }}">
                <input type="hidden" name="phi_van_chuyen" value="0">
                <input type="hidden" name="ma_voucher" value="{{ $MaVoucher }}">
                <input type="hidden" name="gia_tri_giam_voucher" value="{{ $giam_gia }}">
                <input type="hidden" name="tong_thanh_toan" value="{{ $tongTien }}">
                <input type="hidden" name="phuong_thuc_thanh_toan" x-model="paymentMethod">
                <input type="hidden" name="cart_items" x-bind:value="JSON.stringify(cartItems)">

                {{-- Phương thức thanh toán --}}
                <section class="glass-panel p-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-lime-400"></div>
                    <div class="flex items-center gap-3 mb-8">
                        <i data-lucide="scan-line" class="text-lime-400 w-6 h-6"></i>
                        <h2 class="font-['Space_Grotesk'] text-2xl font-semibold text-white uppercase tracking-wider">Phương Thức Thanh Toán</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button type="button" @click="paymentMethod = 'cod'"
                                :class="paymentMethod === 'cod' ? 'border-lime-400 bg-lime-400/5 text-lime-400 shadow-[0_0_20px_rgba(163,230,53,0.12)]' : 'border-white/10 text-white/60 hover:border-white/30'"
                                class="flex flex-col items-center gap-4 p-6 border transition-all cursor-pointer group rounded-xl">
                            <i data-lucide="hand-coins" :class="paymentMethod === 'cod' ? 'animate-pulse' : ''" class="w-8 h-8"></i>
                            <span class="text-[10px] uppercase font-bold tracking-[0.2em]">Thanh toán khi nhận hàng</span>
                            <span class="text-[9px] text-white/35 uppercase tracking-[0.2em]">COD / trả tiền cho shipper</span>
                        </button>

                        <button type="button" @click="paymentMethod = 'momo'"
                                :class="paymentMethod === 'momo' ? 'border-lime-400 bg-lime-400/5 text-lime-400 shadow-[0_0_20px_rgba(163,230,53,0.12)]' : 'border-white/10 text-white/60 hover:border-white/30'"
                                class="flex flex-col items-center gap-4 p-6 border transition-all cursor-pointer group rounded-xl">
                            <i data-lucide="wallet-cards" :class="paymentMethod === 'momo' ? 'animate-pulse' : ''" class="w-8 h-8"></i>
                            <span class="text-[10px] uppercase font-bold tracking-[0.2em]">Thanh toán MoMo</span>
                            <span class="text-[9px] text-white/35 uppercase tracking-[0.2em]">Ví MoMo / quét mã QR MoMo</span>
                        </button>
                    </div>
                </section>

                {{-- Ghi chú --}}
                <section class="glass-panel p-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-lime-400"></div>
                    <div class="flex items-center gap-3 mb-8">
                        <i data-lucide="sticky-note" class="text-lime-400 w-6 h-6"></i>
                        <h2 class="font-['Space_Grotesk'] text-2xl font-semibold text-white uppercase tracking-wider">Ghi Chú Đơn Hàng</h2>
                    </div>

                    <textarea class="neo-input block w-full rounded-xl px-4 py-4 text-sm bg-white/5 border-white/10 text-white placeholder:text-white/25 focus:border-lime-400 focus:ring-0 min-h-[180px] resize-none" placeholder="Yêu cầu cấu hình thêm hoặc lưu ý cho shipper..." rows="6" name="ghi_chu"></textarea>
                </section>
            </form>
        </div>

        <!-- ================= RIGHT COLUMN (4/12) ================= -->
        <div class="lg:col-span-4 lg:sticky lg:top-28">
            <section class="glass-panel p-8 border border-lime-400/20 shadow-[0_0_50px_rgba(0,255,102,0.05)]">
                <h2 class="font-['Space_Grotesk'] text-xl font-bold text-white uppercase tracking-wider mb-8 pb-4 border-b border-white/5">
                    Tóm Tắt Đơn Hàng
                </h2>
                
                <div class="space-y-6 mb-8">
                    @if(isset($cartItems) && count($cartItems) > 0)
                        @foreach($cartItems as $item)
                        <div class="flex gap-4">
                            <div class="w-20 h-20 bg-gray-900 border border-white/10 flex-shrink-0 relative overflow-hidden group">
                                <img src="{{ $item['link_anh_dai_dien'] ?? 'https://via.placeholder.com/200' }}" 
                                     alt="Product" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            </div>
                            <div class="flex flex-col justify-between py-1 flex-grow">
                                <div>
                                    <h3 class="text-[11px] font-bold text-white uppercase tracking-tight line-clamp-1">
                                        {{ $item['ten_bien_the'] ?? '' }}
                                    </h3>
                                    <p class="text-[9px] text-white/40 mt-1 uppercase tracking-widest font-['Space_Grotesk']">
                                        SL: {{ $item['so_luong'] ?? 0 }} • {{ number_format($item['gia_ban'] ?? 0, 0, ',', '.') }}đ
                                    </p>
                                </div>
                                <p class="text-lime-400 font-bold text-xs">
                                    {{ number_format((($item['gia_ban'] ?? 0) * ($item['so_luong'] ?? 0)), 0, ',', '.') }}đ
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="flex flex-col items-center justify-center p-8 text-center">
                            <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-500 mb-3 opacity-50"></i>
                            <p class="text-white/50 text-sm font-bold">Không có sản phẩm trong giỏ hàng</p>
                        </div>
                    @endif
                </div>

                {{-- Voucher Input --}}
                <div class="py-6 border-t border-white/5">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 font-mono">Mã giảm giá / Voucher</label>
                    <form action="" method="GET">
                        <div class="flex gap-2">
                            <input type="text" 
                                name="ma_voucher" 
                                placeholder="NHẬP MÃ VOUCHER..." 
                                value="{{ request('ma_voucher') }}"
                                class="flex-1 bg-white/5 border border-white/10 focus:border-lime-400/50 rounded-lg px-4 py-3 text-xs text-white placeholder:text-white/20 focus:outline-none transition-all uppercase font-mono tracking-widest" />
                            <button type="submit" 
                                    class="bg-transparent hover:bg-lime-400 border border-lime-400/30 hover:border-lime-400 text-lime-400 hover:text-black font-mono font-bold uppercase tracking-widest text-[10px] px-6 py-3 rounded-lg transition-all duration-300 flex items-center justify-center whitespace-nowrap cursor-pointer">
                                Áp dụng
                            </button>
                        </div>
                    </form>
                </div>

                <div class="space-y-3 pt-6 border-t border-white/5">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-white/40 uppercase tracking-widest text-[10px]">Tạm tính</span>
                        <span class="text-white font-['Space_Grotesk']">{{ number_format($tamTinh, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-white/40 uppercase tracking-widest text-[10px]">Giảm giá (Voucher)</span>
                        <span class="text-rose-500 font-['Space_Grotesk']">-{{ number_format($giam_gia, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-white/40 uppercase tracking-widest text-[10px]">Phí vận chuyển</span>
                        <span class="text-lime-400 uppercase font-bold text-[10px]">MIỄN PHÍ</span>
                    </div>
                    <div class="flex justify-between items-end pt-8 mt-4 border-t border-white/10">
                        <span class="text-white font-bold uppercase tracking-widest text-xs">Tổng cộng</span>
                        <span class="text-4xl font-black text-lime-400 font-['Space_Grotesk'] tracking-tighter primary-glow">
                            {{ number_format($tongTien, 0, ',', '.') }}đ
                        </span>
                    </div>
                </div>

                <!-- Nút Submit liên kết với form bên trái qua thuộc tính form="checkout-form" -->
                <button type="submit" form="checkout-form" class="w-full mt-10 py-5 bg-lime-400 text-black font-black uppercase tracking-[0.2em] text-xs shadow-[0_0_25px_rgba(0,255,102,0.4)] hover:shadow-[0_0_40px_rgba(0,255,102,0.6)] hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-3 cursor-pointer group">
                    Xác nhận giao dịch
                    <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                </button>
            </section>
        </div>

    </div>
</main>
</div>
@endsection

@push('scripts')
<script>
    window.selectAddress = function(ho_ten, so_dien_thoai, dia_chi) {
        document.getElementById('disp_ho_ten').textContent = ho_ten;
        document.getElementById('disp_sdt').textContent = so_dien_thoai;
        document.getElementById('disp_dc').textContent = dia_chi;
        
        document.getElementById('sel_ho_ten').value = ho_ten;
        document.getElementById('sel_sdt').value = so_dien_thoai;
        document.getElementById('sel_dc').value = dia_chi;
    };
</script>
@endpush

@section('scripts')
<script>
    lucide.createIcons();
</script>
@endsection