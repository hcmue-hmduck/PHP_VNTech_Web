@extends('layouts.app')

@section('title', 'Thanh toán | VNTech Protocol')

@section('content')

@php
    $tongTien = collect($cartItems ?? [])->sum(fn ($item) => ($item['gia_ban'] ?? 0) * ($item['so_luong'] ?? 0));
    $tamTinh = $tongTien;
    $ma_voucher = request('ma_voucher');
    $giam_gia = 0;
    $MaVoucher = '';
    $voucher_error = '';
    $voucher_success = '';

    if ($ma_voucher) {
        $check_voucher = $voucher->firstWhere('ten_voucher', $ma_voucher);
        if (!$check_voucher) {
            $voucher_error = 'Mã giảm giá không tồn tại hoặc đã hết hạn!';
        } elseif (!$check_voucher->isAvailable()) {
            $voucher_error = 'Mã giảm giá này đã hết lượt sử dụng!';
        } elseif ($tamTinh < ($check_voucher->don_hang_toi_thieu ?? 0)) {
            $voucher_error = 'Đơn hàng chưa đạt giá trị tối thiểu (' . number_format($check_voucher->don_hang_toi_thieu, 0, ',', '.') . '₫)!';
        } else {
            $MaVoucher = $check_voucher->ma_voucher;
            $voucher_success = 'Áp dụng mã giảm giá thành công!';
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
    <div class="mx-auto mb-8 max-w-[1440px] rounded-2xl border border-red-200 bg-red-50 p-5 text-red-750 shadow-sm">
        <p class="mb-2 font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.2em] text-red-600">
            Không thể tạo đơn hàng
        </p>
        <ul class="space-y-1 text-xs text-red-700 font-semibold">
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

<div class="bg-[#FAF8F2] min-h-screen pt-10 pb-24 px-4 sm:px-8 font-sans text-slate-800">
    <div class="max-w-7xl mx-auto">
        <main>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Page Header (Spans full width) -->
                <div class="lg:col-span-12 mb-4">
                    <div class="relative rounded-3xl border border-slate-200/60 bg-white p-6 sm:p-8 shadow-[0_12px_40px_rgba(0,0,0,0.02)]">
                        <a href="{{ url('/') }}" 
                        class="absolute right-4 top-4 sm:right-6 sm:top-6 flex items-center gap-2 px-4 py-2 border border-slate-200 bg-white hover:bg-brand-50 hover:border-brand-500/40 rounded-xl text-slate-500 hover:text-brand-500 text-[11px] font-bold uppercase tracking-wider transition-all duration-300 group shadow-xs no-underline">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1"></i>
                            <span>Trang chủ</span>
                        </a>
                        <div class="flex max-w-3xl flex-col gap-1 pr-28 sm:pr-40 text-left">
                            <p class="text-[10px] font-black uppercase tracking-wider text-brand-500">VNTech checkout</p>
                            <h1 class="font-space text-3xl font-black tracking-tight text-slate-800">
                                Thanh toán đơn hàng
                            </h1>
                            <p class="text-xs font-semibold text-slate-400">Kiểm tra địa chỉ giao nhận, phương thức thanh toán và xác nhận đơn hàng của bạn.</p>
                        </div>
                    </div>
                </div>

                <!-- ================= LEFT COLUMN (8/12) ================= -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- PHẦN 1: QUẢN LÝ ĐỊA CHỈ GIAO HÀNG -->
                    <section class="bg-white border border-slate-200/60 rounded-3xl p-6 sm:p-8 shadow-[0_12px_40px_rgba(0,0,0,0.02)] relative overflow-hidden group"
                             x-data="{
                                 showList: false,
                                 showAddForm: false,
                                 openEdit: false,
                                 editingAddress: { ho_ten: '', so_dien_thoai: '', dia_chi_chi_tiet: '', is_default: false, _id: '' },
                                 provinces: [],
                                 editProvince: '',
                                 editWard: '',
                                 editWards: [],
                                 async init() {
                                     try {
                                         const res = await fetch('https://provinces.open-api.vn/api/v2/p/');
                                         this.provinces = await res.json();
                                     } catch (e) { console.error('Lỗi tải tỉnh/thành', e); }
                                 },
                                 async openEditModal(address) {
                                     this.editingAddress = {
                                         ho_ten: address.ho_ten,
                                         so_dien_thoai: address.so_dien_thoai,
                                         dia_chi_chi_tiet: address.dia_chi_chi_tiet,
                                         is_default: !!address.is_default,
                                         _id: address._id || address.ma_dia_chi
                                     };
                                     this.openEdit = true;
                                     this.showList = false;
                                     this.showAddForm = false;
                                     const matching = this.provinces.find(p => p.name === address.tinh_thanh);
                                     if (matching) {
                                         this.editProvince = matching.code;
                                         await this.fetchEditWards();
                                         const ward = this.editWards.find(w => w.name === address.phuong_xa);
                                         this.editWard = ward ? ward.code : '';
                                     } else {
                                         this.editProvince = '';
                                         this.editWards = [];
                                         this.editWard = '';
                                     }
                                     this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                                 },
                                 async fetchEditWards() {
                                     this.editWards = [];
                                     this.editWard = '';
                                     if (!this.editProvince) return;
                                     try {
                                         const res = await fetch(`https://provinces.open-api.vn/api/v2/p/${this.editProvince}?depth=2`);
                                         const data = await res.json();
                                         this.editWards = data.wards || [];
                                     } catch (e) { console.error('Lỗi tải phường/xã', e); }
                                 },
                                 toggleAddForm() {
                                     this.showAddForm = !this.showAddForm;
                                     if (this.showAddForm) {
                                         this.openEdit = false;
                                         this.showList = false;
                                     }
                                 },
                                 toggleList() {
                                     this.showList = !this.showList;
                                     if (this.showList) {
                                         this.showAddForm = false;
                                         this.openEdit = false;
                                     }
                                 }
                             }">
                        <div class="absolute top-0 left-0 w-1 h-full bg-brand-500"></div>
                        
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div class="text-left">
                                <h2 class="font-space text-lg font-black text-slate-800 uppercase tracking-wide leading-none">Thông Tin Giao Hàng</h2>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mt-1">Chọn hoặc thêm địa chỉ nhận hàng</p>
                            </div>
                        </div>

                        @php
                            $selectedAddressId = session('selected_address_id');
                            $defaultAddress = null;
                            if ($selectedAddressId) {
                                $defaultAddress = $user_address->firstWhere('ma_dia_chi', $selectedAddressId);
                            }
                            if (!$defaultAddress) {
                                $defaultAddress = $user_address->firstWhere('is_default', true) ?? $user_address->first();
                            }
                            $hasAddress = $user_address->isNotEmpty();
                            $fullAddress = $defaultAddress
                                ? $formatAddress($defaultAddress->dia_chi_chi_tiet, $defaultAddress->phuong_xa, $defaultAddress->quan_huyen, $defaultAddress->tinh_thanh)
                                : '';
                        @endphp

                        @if($hasAddress && $defaultAddress)
                        <div class="text-left">
                            {{-- Card địa chỉ đang chọn --}}
                            <div class="space-y-4 bg-slate-50/50 p-5 rounded-2xl border border-slate-200/60 mb-4">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span id="disp_ho_ten" class="text-slate-800 font-black text-sm uppercase tracking-tight">{{ $defaultAddress->ho_ten }}</span>
                                            <span class="text-[9px] bg-brand-50 border border-brand-100 text-brand-500 px-2 py-0.5 rounded-lg font-black uppercase tracking-wider">Đang chọn</span>
                                        </div>
                                        <p id="disp_sdt" class="text-slate-500 text-xs font-mono mb-1">{{ $defaultAddress->so_dien_thoai }}</p>
                                        <p id="disp_dc" class="text-slate-600 text-xs leading-relaxed font-medium">{{ $fullAddress }}</p>
                                    </div>
                                    <button type="button" @click="toggleList()"
                                        class="flex items-center gap-1.5 text-[9px] uppercase tracking-wider font-black text-slate-500 hover:text-brand-500 bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-xl transition-all duration-300 flex-shrink-0 cursor-pointer">
                                        <i data-lucide="shuffle" class="w-3 h-3"></i>
                                        <span x-text="showList ? 'Đóng' : 'Đổi địa chỉ'"></span>
                                     </button>
                                </div>
                            </div>

                            {{-- Danh sách địa chỉ để đổi --}}
                            <div x-show="showList" x-transition class="mb-4 space-y-2 p-4 bg-slate-50/30 rounded-2xl border border-slate-150" x-cloak>
                                <p class="text-[9px] text-brand-500 font-black uppercase tracking-wider mb-2">Chọn địa chỉ giao hàng</p>
                                @foreach($user_address as $addr)
                                    @php
                                        $addrFull = $formatAddress($addr->dia_chi_chi_tiet, $addr->phuong_xa, $addr->quan_huyen, $addr->tinh_thanh);
                                    @endphp
                                <div class="relative flex items-center justify-between gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:border-brand-500/50 hover:bg-slate-50/50 cursor-pointer group transition-all">
                                    {{-- Link select phủ toàn bộ card --}}
                                    <a href="{{ route('user-address.select', $addr->ma_dia_chi) }}" class="absolute inset-0 z-0 rounded-xl"></a>

                                    <div class="flex-1 min-w-0 relative z-10 pointer-events-none">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <span class="text-slate-800 text-xs font-bold group-hover:text-brand-500 transition-colors uppercase tracking-tight">{{ $addr->ho_ten }}</span>
                                            @if($addr->is_default)
                                                <span class="text-[8px] bg-slate-100 border border-slate-200 text-slate-500 px-1.5 py-0.5 rounded font-black uppercase">Mặc định</span>
                                            @endif
                                        </div>
                                        <p class="text-slate-400 text-[10px] font-mono">{{ $addr->so_dien_thoai }}</p>
                                        <p class="text-slate-500 text-[10px] truncate leading-none">{{ $addrFull }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 flex-shrink-0 relative z-10" @click.stop>
                                        <button type="button"
                                                @click="openEditModal({{ Js::from($addr) }})"
                                                class="p-1.5 text-slate-400 hover:text-brand-500 hover:bg-slate-100 rounded-lg transition-all inline-flex cursor-pointer animate-none"
                                                title="Chỉnh sửa">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <form action="{{ route('user-address.destroy', $addr->ma_dia_chi) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');"
                                              class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-slate-100 rounded-lg transition-all cursor-pointer"
                                                    title="Xóa">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Form chỉnh sửa inline (Alpine.js) --}}
                            <div x-show="openEdit" x-transition class="mt-4 bg-slate-50/50 border border-brand-500/20 rounded-2xl p-5" x-cloak>
                                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200/60">
                                    <h4 class="text-xs font-black uppercase text-slate-800 tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="edit-3" class="text-brand-500 w-4 h-4"></i> Cập nhật địa chỉ nhận hàng
                                    </h4>
                                    <button type="button" @click="openEdit = false"
                                       class="text-slate-400 hover:text-slate-800 transition-colors cursor-pointer" title="Đóng">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <form :action="'/user-address/' + editingAddress._id + '/edit'" method="POST">
                                    @csrf
                                    @method('PUT')

                                    {{-- Họ tên & SĐT --}}
                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Họ và Tên</label>
                                            <input type="text" name="ho_ten" required
                                                   x-model="editingAddress.ho_ten"
                                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-250 rounded-xl text-slate-800 focus:border-accent-500 focus:outline-none focus:ring-4 focus:ring-accent-500/10 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Số Điện Thoại</label>
                                            <input type="text" name="so_dien_thoai" required
                                                   x-model="editingAddress.so_dien_thoai"
                                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-250 rounded-xl text-slate-800 font-mono focus:border-accent-500 focus:outline-none focus:ring-4 focus:ring-accent-500/10 transition-all">
                                        </div>
                                    </div>

                                    {{-- Tỉnh/TP & Phường/Xã --}}
                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Tỉnh / Thành Phố</label>
                                            <select x-model="editProvince" @change="fetchEditWards()"
                                                    class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-250 rounded-xl text-slate-800 focus:border-accent-500 focus:outline-none appearance-none cursor-pointer">
                                                <option value="" class="bg-white text-slate-400">-- Chọn Tỉnh/TP --</option>
                                                <template x-for="p in provinces" :key="p.code">
                                                    <option :value="p.code" x-text="p.name" class="bg-white text-slate-800"></option>
                                                </template>
                                            </select>
                                            <input type="hidden" name="tinh_thanh" :value="provinces.find(p => String(p.code) === String(editProvince))?.name || ''">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Phường / Xã</label>
                                            <select x-model="editWard" :disabled="!editProvince"
                                                    class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-250 rounded-xl text-slate-800 focus:border-accent-500 focus:outline-none appearance-none cursor-pointer disabled:opacity-40">
                                                <option value="" class="bg-white text-slate-400">-- Chọn Phường/Xã --</option>
                                                <template x-for="w in editWards" :key="w.code">
                                                    <option :value="w.code" x-text="w.name" class="bg-white text-slate-800"></option>
                                                </template>
                                            </select>
                                            <input type="hidden" name="phuong_xa" :value="editWards.find(w => String(w.code) === String(editWard))?.name || ''">
                                        </div>
                                    </div>
                                    <input type="hidden" name="quan_huyen" value="">

                                    {{-- Địa chỉ chi tiết --}}
                                    <div class="mb-3">
                                        <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Địa chỉ chi tiết</label>
                                        <input type="text" name="dia_chi_chi_tiet" required
                                               x-model="editingAddress.dia_chi_chi_tiet"
                                               class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-250 rounded-xl text-slate-800 focus:border-accent-500 focus:outline-none focus:ring-4 focus:ring-accent-500/10 transition-all">
                                    </div>

                                    {{-- Đặt làm mặc định --}}
                                    <div class="flex items-center gap-2 mb-4">
                                        <input type="checkbox" name="is_default" value="1" id="edit_is_default"
                                               x-model="editingAddress.is_default"
                                               class="w-4 h-4 accent-brand-500 cursor-pointer">
                                        <label for="edit_is_default" class="text-xs text-slate-500 font-medium cursor-pointer">Đặt làm địa chỉ mặc định</label>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button" @click="openEdit = false"
                                           class="w-full py-2.5 border border-slate-200 hover:border-slate-350 text-slate-500 hover:text-slate-800 font-bold text-[10px] tracking-wider uppercase rounded-xl transition-all text-center cursor-pointer">
                                            Hủy bỏ
                                        </button>
                                        <button type="submit"
                                                class="w-full py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-black text-[10px] tracking-wider uppercase rounded-xl hover:shadow-[0_4px_15px_rgba(255,79,0,0.25)] transition-all cursor-pointer">
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center p-8 bg-slate-50/50 rounded-2xl border border-slate-200/60 text-center mb-4">
                            <i data-lucide="map-pin-off" class="w-10 h-10 text-slate-300 mb-3"></i>
                            <p class="text-slate-400 text-xs mb-4 uppercase tracking-wider font-bold">Bạn chưa có địa chỉ nhận hàng</p>
                        </div>
                        @endif

                        {{-- Form thêm địa chỉ mới --}}
                        <div class="text-left">
                            <button type="button" @click="toggleAddForm()"
                                class="w-full py-2.5 border border-dashed border-slate-300 hover:border-brand-500/40 text-slate-400 hover:text-brand-500 text-[10px] font-black uppercase tracking-wider rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer bg-white">
                                <i data-lucide="plus" class="w-3.5 h-3.5" :class="showAddForm ? 'rotate-45' : ''" style="transition: transform 0.2s"></i>
                                <span x-text="showAddForm ? 'Hủy bỏ' : 'Thêm địa chỉ giao nhận mới'"></span>
                            </button>

                            <div x-show="showAddForm" x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="mt-3" x-cloak>
                                <form action="{{ route('user-address.store') }}" method="POST" class="bg-slate-50/50 border border-slate-200/60 rounded-2xl p-5">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Họ và Tên</label>
                                            <input type="text" name="ho_ten" required placeholder="Nguyễn Văn A"
                                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 placeholder:text-slate-400 focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 focus:outline-none transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Số Điện Thoại</label>
                                            <input type="text" name="so_dien_thoai" required placeholder="0900 000 000"
                                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 placeholder:text-slate-400 focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 focus:outline-none transition-all">
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
                                                <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Tỉnh / Thành Phố</label>
                                                <select x-model="selectedProvince"
                                                        @change="fetchWards()"
                                                        class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 focus:border-accent-500 focus:outline-none appearance-none cursor-pointer">
                                                    <option value="" class="bg-white text-slate-400">-- Chọn Tỉnh/TP --</option>
                                                    <template x-for="p in provinces" :key="p.code">
                                                        <option :value="p.code" x-text="p.name" class="bg-white text-slate-800"></option>
                                                    </template>
                                                </select>
                                                <input type="hidden" name="tinh_thanh" :value="provinces.find(p => String(p.code) === String(selectedProvince))?.name || ''">
                                            </div>

                                            {{-- Phường / Xã --}}
                                            <div>
                                                <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Phường / Xã</label>
                                                <select x-model="selectedWard"
                                                        :disabled="!selectedProvince"
                                                        class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 focus:border-accent-500 focus:outline-none appearance-none cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                                    <option value="" class="bg-white text-slate-400">-- Chọn Phường/Xã --</option>
                                                    <template x-for="w in wards" :key="w.code">
                                                        <option :value="w.code" x-text="w.name" class="bg-white text-slate-800"></option>
                                                    </template>
                                                </select>
                                                <input type="hidden" name="phuong_xa" :value="wards.find(w => String(w.code) === String(selectedWard))?.name || ''">
                                            </div>
                                        </div>
                                        <input type="hidden" name="quan_huyen" value="">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-[9px] text-slate-400 uppercase tracking-wider mb-1 font-bold">Địa chỉ chi tiết</label>
                                        <input type="text" name="dia_chi_chi_tiet" required placeholder="Số nhà, tên đường..."
                                               class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 placeholder:text-slate-450 focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 focus:outline-none transition-all">
                                    </div>

                                    <!-- Đặt làm mặc định -->
                                    <div class="flex items-center gap-2 mb-4">
                                        <label class="flex items-center gap-2 cursor-pointer select-none text-[11px] text-slate-500 font-medium">
                                            <div class="relative w-4 h-4 border border-slate-350 rounded bg-white flex items-center justify-center transition-all duration-300">
                                                <input type="checkbox" name="is_default" value="1" class="peer absolute inset-0 opacity-0 cursor-pointer z-10">
                                                <div class="absolute inset-0 rounded-[3px] bg-brand-500 text-white flex items-center justify-center scale-0 peer-checked:scale-100 transition-transform duration-200">
                                                    <svg class="w-3 h-3 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <span>Đặt làm địa chỉ mặc định</span>
                                        </label>
                                    </div>

                                    <button type="submit" class="w-full py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-black text-[10px] tracking-wider uppercase rounded-xl hover:shadow-[0_4px_15px_rgba(255,79,0,0.25)] transition-all cursor-pointer">
                                        LƯU ĐỊA CHỈ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </section>

                    <!-- PHẦN 2: THÔNG TIN THANH TOÁN (FORM CHÍNH) -->
                    <form id="checkout-form" method="POST" action="{{ route('order.store') }}" x-data="{ paymentMethod: 'cod', cartItems: {{ json_encode($cartItems ?? []) }} }" class="space-y-8 text-left">
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
                        <section class="bg-white border border-slate-200/60 rounded-3xl p-6 sm:p-8 shadow-[0_12px_40px_rgba(0,0,0,0.02)] relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-brand-500"></div>
                            
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center">
                                    <i data-lucide="wallet-cards" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h2 class="font-space text-lg font-black text-slate-800 uppercase tracking-wide leading-none">Phương Thức Thanh Toán</h2>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mt-1">Chọn cổng thanh toán tiện lợi nhất</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button type="button" @click="paymentMethod = 'cod'"
                                        :class="paymentMethod === 'cod' ? 'border-brand-500 bg-brand-50/30 text-brand-500 shadow-[0_8px_20px_rgba(255,79,0,0.05)] font-extrabold' : 'border-slate-200/80 text-slate-500 hover:border-slate-350 hover:bg-slate-50/50'"
                                        class="flex flex-col items-center gap-3 p-5 border transition-all cursor-pointer rounded-2xl w-full">
                                    <i data-lucide="hand-coins" :class="paymentMethod === 'cod' ? 'scale-105 text-brand-500' : 'text-slate-400'" class="w-8 h-8 transition-transform"></i>
                                    <span class="text-[10px] uppercase font-black tracking-wider mt-1">Thanh toán khi nhận hàng</span>
                                    <span class="text-[8px] text-slate-400 uppercase tracking-widest leading-none font-bold">COD / Tiền mặt khi nhận thiết bị</span>
                                </button>

                                <button type="button" @click="paymentMethod = 'momo'"
                                        :class="paymentMethod === 'momo' ? 'border-brand-500 bg-brand-50/30 text-brand-500 shadow-[0_8px_20px_rgba(255,79,0,0.05)] font-extrabold' : 'border-slate-200/80 text-slate-500 hover:border-slate-350 hover:bg-slate-50/50'"
                                        class="flex flex-col items-center gap-3 p-5 border transition-all cursor-pointer rounded-2xl w-full">
                                    <i data-lucide="wallet-cards" :class="paymentMethod === 'momo' ? 'scale-105 text-[#A50064]' : 'text-slate-400'" class="w-8 h-8 transition-transform"></i>
                                    <span class="text-[10px] uppercase font-black tracking-wider mt-1">Thanh toán MoMo</span>
                                    <span class="text-[8px] text-slate-400 uppercase tracking-widest leading-none font-bold">Ví MoMo / Chuyển khoản trực tuyến</span>
                                </button>
                            </div>
                        </section>

                        {{-- Ghi chú --}}
                        <section class="bg-white border border-slate-200/60 rounded-3xl p-6 sm:p-8 shadow-[0_12px_40px_rgba(0,0,0,0.02)] relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-brand-500"></div>
                            
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-500 flex items-center justify-center">
                                    <i data-lucide="pencil-line" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h2 class="font-space text-lg font-black text-slate-800 uppercase tracking-wide leading-none">Ghi Chú Đơn Hàng</h2>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mt-1">Lưu ý giao nhận đặc biệt (nếu có)</p>
                                </div>
                            </div>

                            <textarea class="w-full rounded-2xl px-4 py-4 text-xs bg-white border border-slate-200 text-slate-800 placeholder:text-slate-400 focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 focus:outline-none transition-all resize-none min-h-[140px]" placeholder="VD: Gọi trước khi giao, giao giờ hành chính, lắp đặt thêm Ram..." rows="5" name="ghi_chu"></textarea>
                        </section>
                    </form>
                </div>

                <!-- ================= RIGHT COLUMN (4/12) ================= -->
                <div class="lg:col-span-4 lg:sticky lg:top-28 text-left">
                    <section class="bg-white border border-slate-200/60 rounded-3xl p-6 sm:p-8 shadow-[0_12px_40px_rgba(0,0,0,0.02)]">
                        <h2 class="font-space text-lg font-black text-slate-800 uppercase tracking-wider mb-6 pb-4 border-b border-slate-100 leading-none">
                            Tóm Tắt Đơn Hàng
                        </h2>
                        
                        <div class="space-y-4 mb-6">
                            @if(isset($cartItems) && count($cartItems) > 0)
                                @foreach($cartItems as $item)
                                <div class="flex gap-4 items-center">
                                    <div class="w-16 h-16 bg-slate-50 border border-slate-200/60 p-1 rounded-xl flex-shrink-0 relative overflow-hidden flex items-center justify-center">
                                        <img src="{{ $item['link_anh_dai_dien'] ?: asset('images/no-image.png') }}" 
                                             alt="Product" 
                                             class="w-full h-full object-contain">
                                    </div>
                                    <div class="flex flex-col justify-between py-1 flex-grow">
                                        <div>
                                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-tight line-clamp-1">
                                                {{ $item['ten_hien_thi'] ?? $item['ten_bien_the'] ?? '' }}
                                            </h3>
                                            <p class="text-[9px] text-slate-400 mt-0.5 uppercase tracking-wider font-bold">
                                                SL: {{ $item['so_luong'] ?? 0 }} • {{ number_format($item['gia_ban'] ?? 0, 0, ',', '.') }}đ
                                            </p>
                                        </div>
                                        <p class="text-brand-500 font-extrabold text-xs mt-1">
                                            {{ number_format((($item['gia_ban'] ?? 0) * ($item['so_luong'] ?? 0)), 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center py-8 text-center">
                                    <i data-lucide="shopping-cart" class="w-10 h-10 text-slate-300 mb-3"></i>
                                    <p class="text-slate-400 text-xs font-bold uppercase">Giỏ hàng rỗng</p>
                                </div>
                            @endif
                        </div>

                        {{-- Voucher Input --}}
                        <div class="py-5 border-t border-slate-100">
                            <label class="block text-[9px] font-black text-slate-450 uppercase tracking-wider mb-2 font-mono">Mã ưu đãi / Voucher</label>
                            <form action="" method="GET">
                                <div class="flex gap-2">
                                    <input type="text" 
                                        name="ma_voucher" 
                                        placeholder="NHẬP VOUCHER..." 
                                        value="{{ request('ma_voucher') }}"
                                        class="flex-1 bg-white border {{ $voucher_error ? 'border-red-400/60' : ($voucher_success ? 'border-emerald-400/65' : 'border-slate-200') }} focus:border-accent-500 rounded-xl px-4 py-3 text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none transition-all uppercase font-mono tracking-widest font-bold" />
                                    <button type="submit" 
                                            class="bg-white hover:bg-slate-50 border border-slate-250 text-slate-600 font-mono font-bold uppercase tracking-wider text-[10px] px-5 py-3 rounded-xl transition-all duration-300 flex items-center justify-center whitespace-nowrap cursor-pointer">
                                        Áp dụng
                                    </button>
                                </div>
                                @if($voucher_error)
                                    <p class="text-[9px] text-red-500 font-mono mt-1.5 font-bold uppercase tracking-wider leading-none">{{ $voucher_error }}</p>
                                @endif
                                @if($voucher_success)
                                    <p class="text-[9px] text-emerald-600 font-mono mt-1.5 font-bold uppercase tracking-wider leading-none">{{ $voucher_success }}</p>
                                @endif
                            </form>
                        </div>

                        <div class="space-y-3 pt-5 border-t border-slate-100 text-xs">
                            <div class="flex justify-between items-center font-semibold text-slate-400">
                                <span class="uppercase tracking-wider">Tạm tính</span>
                                <span class="text-slate-700 font-bold">{{ number_format($tamTinh, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between items-center font-semibold text-slate-400">
                                <span class="uppercase tracking-wider">Mức giảm voucher</span>
                                <span class="text-red-500 font-bold">-{{ number_format($giam_gia, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between items-center font-semibold text-slate-400">
                                <span class="uppercase tracking-wider">Phí giao nhận</span>
                                <span class="text-emerald-600 uppercase font-black text-[10px]">MIỄN PHÍ</span>
                            </div>
                            
                            <div class="flex justify-between items-end pt-5 mt-4 border-t border-slate-200">
                                <span class="text-slate-700 font-black uppercase tracking-wider text-xs leading-none pb-1.5">TỔNG CỘNG</span>
                                <span class="text-3xl font-black text-brand-500 tracking-tight leading-none">
                                    {{ number_format($tongTien, 0, ',', '.') }}đ
                                </span>
                            </div>
                        </div>

                        <!-- Nút Submit liên kết với form bên trái qua thuộc tính form="checkout-form" -->
                        <button type="submit" form="checkout-form" class="w-full mt-8 py-4 bg-brand-500 hover:bg-brand-600 hover:shadow-[0_6px_20px_rgba(255,79,0,0.3)] text-white font-black uppercase tracking-widest text-xs rounded-xl active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer group">
                            Xác nhận thanh toán
                            <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                        </button>
                    </section>
                </div>

            </div>
        </main>
    </div>
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
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endsection
