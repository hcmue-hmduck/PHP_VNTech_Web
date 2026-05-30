@extends('layouts.admin')

@section('title', isset($voucher) ? 'Cập nhật Voucher - VNTech' : 'Tạo mới Voucher - VNTech')

@section('content')
@php
    $isEdit = isset($voucher) && $voucher->exists;
@endphp

<div class="w-full">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <a href="{{ route('admin.voucher.view') }}"
               class="group inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-neon-green transition-colors mb-3 no-underline">
                <i data-lucide="arrow-left" class="size-3 group-hover:-translate-x-0.5 transition-transform"></i>
                <span>Trở lại danh sách</span>
            </a>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)] uppercase leading-none">
                {{ $isEdit ? 'CẬP NHẬT VOUCHER' : 'TẠO MỚI VOUCHER' }}
            </h1>
            @if($isEdit)
            <div class="mt-3 flex items-center gap-2 text-[10px] font-mono text-gray-400 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-neon-green animate-pulse"></span>
                <span>Mã Voucher:</span>
                <span class="text-neon-green font-bold">{{ $voucher->ma_voucher }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
    <div class="glass-panel p-4 mb-8 border-l-4 border-l-rose-500">
        <div class="flex items-start gap-3 text-rose-400">
            <i data-lucide="alert-triangle" class="size-4 shrink-0 mt-0.5"></i>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em]">Lỗi chuẩn hóa dữ liệu</p>
                <ul class="text-xs mt-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('admin.voucher.update', $voucher->id ?? $voucher->ma_voucher) : route('admin.voucher.store') }}" class="space-y-6 pb-28">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Cột chính: Thông tin cơ bản & Mức giảm -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                <!-- Thông tin cơ bản -->
                <div class="glass-panel p-6 lg:p-8 border-l-4 border-l-neon-green relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-neon-green/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    <h2 class="text-lg font-display font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="text-neon-green size-5"></i>
                        THÔNG TIN VOUCHER
                    </h2>
                    
                    <div class="space-y-5">
                        <!-- Tên voucher (Để người dùng nhập mã voucher) -->
                        <div class="space-y-1.5">
                            <label for="ten_voucher" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Mã Voucher <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="tag" class="size-4 text-gray-500"></i>
                                </div>
                                <input
                                    id="ten_voucher" name="ten_voucher" type="text"
                                    value="{{ old('ten_voucher', $voucher->ten_voucher ?? '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg uppercase"
                                    placeholder="Vd: CHAOMUNG2026..."
                                    required
                                />
                            </div>
                        </div>

                        <!-- Mã voucher (Trường ẩn làm ID) -->
                        <input type="hidden" id="ma_voucher" name="ma_voucher" value="{{ old('ma_voucher', $voucher->ma_voucher ?? '') }}" />

                        <!-- Mô tả -->
                        <div class="space-y-1.5">
                            <label for="mo_ta" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Mô tả nội dung</label>
                            <textarea
                                id="mo_ta" name="mo_ta"
                                class="w-full min-h-[120px] bg-dark-bg/50 border border-white/10 p-4 text-sm font-mono text-gray-300 focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg resize-none"
                                placeholder="Nhập mô tả hoặc điều khoản sử dụng voucher..."
                            >{{ old('mo_ta', $voucher->mo_ta ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Cấu hình giảm giá & Điều kiện -->
                <div class="glass-panel p-6 lg:p-8 border-l-4 border-l-blue-400 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    <h2 class="text-lg font-display font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                        <i data-lucide="percent" class="text-blue-400 size-5"></i>
                        MỨC GIẢM & ĐIỀU KIỆN
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Hình thức giảm -->
                        <div class="space-y-1.5">
                            <label for="hinh_thuc_giam" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Hình thức giảm</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="settings" class="size-4 text-gray-500"></i>
                                </div>
                                <select
                                    id="hinh_thuc_giam" name="hinh_thuc_giam"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-10 text-sm font-mono text-white focus:border-blue-400 focus:bg-blue-400/5 outline-none appearance-none cursor-pointer rounded-lg transition-all"
                                >
                                    <option value="percent" {{ old('hinh_thuc_giam', $voucher->hinh_thuc_giam ?? '') === 'percent' ? 'selected' : '' }}>PHẦN TRĂM (%)</option>
                                    <option value="fixed" {{ old('hinh_thuc_giam', $voucher->hinh_thuc_giam ?? '') === 'fixed' ? 'selected' : '' }}>SỐ TIỀN CỐ ĐỊNH (đ)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="size-4 text-gray-500"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Giá trị giảm -->
                        <div class="space-y-1.5">
                            <label for="gia_tri_giam" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Giá trị giảm <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="coins" class="size-4 text-gray-500"></i>
                                </div>
                                <input
                                    id="gia_tri_giam" name="gia_tri_giam" type="number" min="1"
                                    value="{{ old('gia_tri_giam', isset($voucher) ? (float)$voucher->gia_tri_giam : '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-blue-400 focus:bg-blue-400/5 outline-none transition-all rounded-lg"
                                    placeholder="Vd: 10 hoặc 50000"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Mức giảm tối đa -->
                        <div class="space-y-1.5">
                            <label for="muc_giam_toi_da" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Mức giảm tối đa (đ)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="arrow-down-to-line" class="size-4 text-gray-500"></i>
                                </div>
                                <input
                                    id="muc_giam_toi_da" name="muc_giam_toi_da" type="number" min="0"
                                    value="{{ old('muc_giam_toi_da', isset($voucher) ? (float)$voucher->muc_giam_toi_da : '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-blue-400 focus:bg-blue-400/5 outline-none transition-all rounded-lg"
                                    placeholder="0 hoặc bỏ trống để không giới hạn"
                                />
                            </div>
                        </div>

                        <!-- Đơn hàng tối thiểu -->
                        <div class="space-y-1.5">
                            <label for="don_hang_toi_thieu" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Đơn tối thiểu (đ)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="shopping-cart" class="size-4 text-gray-500"></i>
                                </div>
                                <input
                                    id="don_hang_toi_thieu" name="don_hang_toi_thieu" type="number" min="0"
                                    value="{{ old('don_hang_toi_thieu', isset($voucher) ? (float)$voucher->don_hang_toi_thieu : '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-blue-400 focus:bg-blue-400/5 outline-none transition-all rounded-lg"
                                    placeholder="0 hoặc bỏ trống để áp dụng mọi đơn"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phụ: Giới hạn & Lịch trình -->
            <div class="lg:col-span-1">
                <div class="glass-panel p-6 lg:p-8 relative overflow-hidden flex flex-col justify-between h-full border-l-4 border-l-yellow-400">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    
                    <div class="space-y-5">
                        <h2 class="text-lg font-display font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                            <i data-lucide="calendar-clock" class="text-yellow-400 size-5"></i>
                            LỊCH TRÌNH & GIỚI HẠN
                        </h2>

                        <!-- Thời gian bắt đầu -->
                        <div class="space-y-1.5">
                            <label for="bat_dau" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Thời điểm bắt đầu <span class="text-rose-500">*</span></label>
                            <input
                                id="bat_dau" name="bat_dau" type="datetime-local"
                                value="{{ old('bat_dau', isset($voucher->bat_dau) ? \Carbon\Carbon::parse($voucher->bat_dau)->format('Y-m-d\TH:i') : '') }}"
                                class="w-full h-12 bg-dark-bg/50 border border-white/10 px-4 text-sm font-mono text-white focus:border-yellow-400/50 focus:bg-yellow-400/5 outline-none transition-all rounded-lg cursor-pointer"
                                required
                            />
                        </div>

                        <!-- Thời gian kết thúc -->
                        <div class="space-y-1.5">
                            <label for="ket_thuc" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Thời điểm kết thúc <span class="text-rose-500">*</span></label>
                            <input
                                id="ket_thuc" name="ket_thuc" type="datetime-local"
                                value="{{ old('ket_thuc', isset($voucher->ket_thuc) ? \Carbon\Carbon::parse($voucher->ket_thuc)->format('Y-m-d\TH:i') : '') }}"
                                class="w-full h-12 bg-dark-bg/50 border border-white/10 px-4 text-sm font-mono text-white focus:border-yellow-400/50 focus:bg-yellow-400/5 outline-none transition-all rounded-lg cursor-pointer"
                                required
                            />
                        </div>

                        <!-- Tổng lượt dùng -->
                        <div class="space-y-1.5">
                            <label for="tong_luot_dung" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Tổng số lượt phát hành <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="ticket" class="size-4 text-gray-500"></i>
                                </div>
                                <input
                                    id="tong_luot_dung" name="tong_luot_dung" type="number" min="1"
                                    value="{{ old('tong_luot_dung', $voucher->tong_luot_dung ?? '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-yellow-400/50 focus:bg-yellow-400/5 outline-none transition-all rounded-lg"
                                    placeholder="Vd: 100"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Đã dùng -->
                        <div class="space-y-1.5">
                            <label for="da_dung" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Lượt đã dùng</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="user-check" class="size-4 text-gray-500"></i>
                                </div>
                                <input
                                    id="da_dung" name="da_dung" type="number" min="0"
                                    value="{{ old('da_dung', $voucher->da_dung ?? 0) }}"
                                    {{ $isEdit ? '' : 'readonly' }}
                                    class="w-full h-12 border pl-11 pr-4 text-sm font-mono rounded-lg transition-all {{ $isEdit ? 'bg-dark-bg/50 border-white/10 text-white focus:border-yellow-400/50 focus:bg-yellow-400/5 outline-none' : 'bg-dark-bg/30 border-white/5 text-gray-500 cursor-not-allowed' }}"
                                />
                            </div>
                        </div>

                        <!-- Trạng thái -->
                        <div class="space-y-1.5">
                            <label for="trang_thai" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Trạng thái phát hành</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="activity" class="size-4 text-gray-500"></i>
                                </div>
                                <select
                                    id="trang_thai" name="trang_thai"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-10 text-sm font-mono text-white focus:border-neon-green focus:bg-neon-green/5 outline-none appearance-none cursor-pointer rounded-lg transition-all"
                                >
                                    @php
                                        $currentStatus = strtolower(old('trang_thai', $voucher->trang_thai ?? 'active'));
                                    @endphp
                                    <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>ĐANG HOẠT ĐỘNG</option>
                                    <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>VÔ HIỆU HÓA</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="size-4 text-gray-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Action Footer -->
        <div class="fixed bottom-0 left-0 lg:left-72 right-0 px-12 py-4 bg-dark-bg/95 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 z-40 shadow-[0_-8px_30px_rgb(0,0,0,0.6)] backdrop-blur-md">
            <div class="text-[10px] text-gray-500 uppercase tracking-widest font-mono hidden md:block">
                * Vui lòng kiểm tra kỹ cấu hình trước khi xác nhận.
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('admin.voucher.view') }}" class="w-full md:w-auto flex justify-center items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 px-6 py-3 text-xs font-bold uppercase tracking-widest transition-all rounded-lg no-underline font-mono">
                    <span>Hủy bỏ</span>
                </a>
                <button type="submit" class="w-full md:w-auto group flex justify-center items-center gap-2 bg-neon-green text-black border border-neon-green px-6 py-3 text-xs font-bold uppercase tracking-widest hover:brightness-110 transition-all rounded-lg shadow-[0_0_15px_rgba(0,229,91,0.2)] font-mono">
                    <i data-lucide="{{ $isEdit ? 'save' : 'plus' }}" class="w-4 h-4 group-hover:scale-110 transition-all"></i>
                    <span>{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Redraw Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Auto-generate Code from Voucher Name (only when creating)
        const tenInput = document.getElementById('ten_voucher');
        const maInput = document.getElementById('ma_voucher');
        const isEdit = maInput && maInput.value !== '';

        if (!isEdit) {
            function convertToVoucherCode(str) {
                return str.normalize('NFD')
                          .replace(/[\u0300-\u036f]/g, '')
                          .replace(/đ/g, 'd').replace(/Đ/g, 'D')
                          .toUpperCase()
                          .replace(/[^A-Z0-9]/g, '') // Keep only letters and numbers
                          .substring(0, 20);
            }

            if (tenInput && maInput) {
                tenInput.addEventListener('input', function() {
                    maInput.value = convertToVoucherCode(tenInput.value);
                });
            }
        }
    });
</script>
@endpush
