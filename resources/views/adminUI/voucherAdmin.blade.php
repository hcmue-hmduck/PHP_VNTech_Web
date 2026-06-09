@extends('layouts.admin')

@section('title', 'Quản lý Voucher - VNTech')

@section('content')
@php
    $vouchers = $voucher;
    $totalVouchers = $vouchers->count();
    $activeVouchers = $vouchers->where('trang_thai', 'active')->filter(fn($v) => !$v->ket_thuc || $v->ket_thuc >= now())->count();
    $totalUsed = $vouchers->sum('da_dung');
    $expiredVouchers = $vouchers->filter(fn($v) => $v->trang_thai === 'inactive' || ($v->ket_thuc && $v->ket_thuc < now()))->count();
@endphp

<div class="w-full">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)]">
                QUẢN LÝ VOUCHER
            </h1>
            <p class="text-[10px] font-mono text-gray-500 uppercase tracking-widest mt-2">
                Hệ thống mã giảm giá & chiến dịch tri ân khách hàng
            </p>
        </div>
        <a href="{{ route('admin.voucher.create') }}" class="group flex items-center gap-3 bg-transparent border-2 border-neon-green text-neon-green px-6 py-3 font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300 font-mono">
            <i data-lucide="plus" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
            <span>TẠO MỚI VOUCHER</span>
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Total Vouchers -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Tổng Voucher</p>
                <i data-lucide="ticket" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalVouchers }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Mã đã cấu hình</p>
            </div>
        </div>

        <!-- Active Vouchers -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Đang hiệu lực</p>
                <i data-lucide="check-circle" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $activeVouchers }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Khả dụng cho khách hàng</p>
            </div>
        </div>

        <!-- Expired/Used Vouchers -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Đã Hết hạn / Vô hiệu</p>
                <i data-lucide="x-circle" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $expiredVouchers }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Hết hạn hoặc tắt thủ công</p>
            </div>
        </div>

        <!-- Total Discount Used -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Tổng lượt đã dùng</p>
                <i data-lucide="percent" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalUsed }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Lượt áp dụng đơn hàng</p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <form method="GET" action="{{ route('admin.voucher.view') }}" class="glass-panel p-6 border-l-4 border-l-neon-green mb-12 grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
        <div class="md:col-span-4 space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Tìm kiếm Voucher</label>
            <div class="relative">
                <input 
                    name="search"
                    type="text" 
                    value="{{ request('search') }}"
                    placeholder="MÃ VOUCHER HOẶC TÊN VOUCHER..." 
                    class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none transition-all rounded-lg text-white"
                />
            </div>
        </div>
        
        <div class="md:col-span-3 space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Trạng thái</label>
            <div class="relative">
                <select name="status" class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer rounded-lg text-gray-300">
                    <option value="all" {{ request('status') === 'all' || !request()->has('status') ? 'selected' : '' }}>TẤT CẢ TRẠNG THÁI</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>ĐANG HOẠT ĐỘNG</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>VÔ HIỆU HÓA</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>QUÁ HẠN</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 text-xs">▼</div>
            </div>
        </div>

        <div class="md:col-span-3 space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Hình thức giảm</label>
            <div class="relative">
                <select name="type" class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer rounded-lg text-gray-300">
                    <option value="all" {{ request('type') === 'all' || !request()->has('type') ? 'selected' : '' }}>TẤT CẢ HÌNH THỨC</option>
                    <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>PHẦN TRĂM (%)</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>SỐ TIỀN CỐ ĐỊNH (đ)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 text-xs">▼</div>
            </div>
        </div>

        <div class="md:col-span-2 grid grid-cols-2 gap-3">
            <button type="submit" class="h-11 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-1.5 rounded-lg font-mono">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>Áp dụng</span>
            </button>
            <a href="{{ route('admin.voucher.view') }}" class="h-11 bg-red-500/10 border border-red-500/20 hover:bg-red-500/25 text-red-400 hover:text-red-300 flex items-center justify-center text-[10px] font-bold uppercase tracking-[0.2em] transition-all gap-1.5 rounded-lg font-mono">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Xóa lọc</span>
            </a>
        </div>
    </form>

    <!-- Voucher Table -->
    <div class="glass-panel overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-high/80 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Voucher</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Mô tả</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Mức giảm</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Điều kiện</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Giới hạn sử dụng</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Thời gian hiệu lực</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Trạng thái</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="voucherTableBody" class="divide-y divide-white/5">
                    @forelse($vouchers as $voucher)
                    @php
                        $isExpired = $voucher->ket_thuc && $voucher->ket_thuc < now();
                        $status = strtolower($voucher->trang_thai ?? '');
                        $isActive = ($status === 'active' && !$isExpired);
                    @endphp
                    <tr class="group hover:bg-white/[0.02] transition-colors"
                        data-code="{{ $voucher->ma_voucher }}"
                        data-status="{{ $voucher->trang_thai }}"
                        data-type="{{ $voucher->hinh_thuc_giam }}">
                        
                        <!-- Voucher -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-white uppercase">{{ $voucher->ten_voucher ?? 'Không có tên' }}</div>
                            <span class="mt-1.5 px-2 py-0.5 inline-block bg-neon-green/10 border border-neon-green/30 text-neon-green font-mono text-[10px] font-bold rounded">
                                {{ $voucher->ma_voucher }}
                            </span>
                        </td>
                        
                        <!-- Mô tả -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-200 line-clamp-1 max-w-[200px]">{{ $voucher->mo_ta ?? 'Không có mô tả' }}</div>
                        </td>

                        <!-- Mức giảm -->
                        <td class="px-6 py-4">
                            @if($voucher->hinh_thuc_giam === 'percent')
                                <div class="text-sm font-mono font-bold text-white">{{ number_format((float)$voucher->gia_tri_giam) }}%</div>
                                <div class="text-[9px] text-gray-500 font-mono">Tối đa: {{ number_format((float)($voucher->muc_giam_toi_da ?? 0), 0, ',', '.') }}đ</div>
                            @else
                                <div class="text-sm font-mono font-bold text-white">{{ number_format((float)$voucher->gia_tri_giam, 0, ',', '.') }}đ</div>
                                <div class="text-[9px] text-gray-500 font-mono">Trừ thẳng vào giá</div>
                            @endif
                        </td>

                        <!-- Điều kiện -->
                        <td class="px-6 py-4 text-xs font-mono text-gray-300">
                            Đơn tối thiểu:<br>
                            <span class="text-white font-bold">{{ number_format((float)($voucher->don_hang_toi_thieu ?? 0), 0, ',', '.') }}đ</span>
                        </td>

                        <!-- Giới hạn sử dụng -->
                        <td class="px-6 py-4 w-40">
                            @php
                                $totalLimit = (int)$voucher->tong_luot_dung;
                                $used = (int)($voucher->da_dung ?? 0);
                                $percentUsed = $totalLimit > 0 ? min(100, ($used / $totalLimit) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="flex-grow h-1.5 bg-dark-bg/60 rounded-full overflow-hidden border border-white/5">
                                    <div class="h-full bg-neon-green shadow-[0_0_8px_rgba(0,229,91,0.5)] transition-all duration-500" 
                                         @style(['width' => $percentUsed . '%'])></div>
                                </div>
                                <span class="text-[10px] font-mono text-gray-300 whitespace-nowrap">{{ $used }}/{{ $totalLimit }}</span>
                            </div>
                        </td>

                        <!-- Thời gian hiệu lực -->
                        <td class="px-6 py-4 text-xs font-mono text-gray-400">
                            Từ: {{ $voucher->bat_dau ? $voucher->bat_dau->format('d/m H:i') : 'N/A' }}<br>
                            Đến: {{ $voucher->ket_thuc ? $voucher->ket_thuc->format('d/m H:i') : 'N/A' }}
                        </td>

                        <!-- Trạng thái -->
                        <td class="px-6 py-4">
                            @if($isActive)
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 text-[9px] font-bold uppercase text-neon-green border border-neon-green/50 bg-neon-green/5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                                    hoạt động
                                </div>
                            @elseif($isExpired)
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 text-[9px] font-bold uppercase text-amber-500 border border-amber-500/50 bg-amber-500/5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    quá hạn
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 text-[9px] font-bold uppercase text-gray-500 border border-gray-700 bg-white/5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                    vô hiệu
                                </div>
                            @endif
                        </td>

                        <!-- Thao tác -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.voucher.edit', $voucher->ma_voucher) }}" 
                                   class="p-2 hover:text-blue-400 hover:bg-blue-400/10 transition-colors border border-transparent hover:border-white/10 rounded-lg inline-block"
                                   title="Chỉnh sửa">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                
                                <form action="{{ route('admin.voucher.delete', $voucher->ma_voucher) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này không?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="p-2 hover:text-rose-500 hover:bg-rose-500/10 transition-colors border border-transparent hover:border-white/10 rounded-lg inline-block" title="Xóa">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 uppercase tracking-widest font-mono">
                            Không tìm thấy voucher nào trong hệ thống
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Redraw Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Lucide Icons redraw only
    });
</script>
@endpush
