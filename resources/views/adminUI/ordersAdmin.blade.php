@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng - VNTech')

@section('content')
@php
    $orders = $orders ?? collect();
    $totalOrders = method_exists($orders, 'count') ? $orders->count() : count($orders);
    $completedOrders = method_exists($orders, 'filter')
        ? $orders->filter(fn ($order) => in_array(($order->trang_thai ?? ''), ['hoan_tat', 'completed', 'paid'], true))->count()
        : 0;
    $pendingOrders = method_exists($orders, 'filter')
        ? $orders->filter(fn ($order) => in_array(($order->trang_thai ?? ''), ['cho_xac_nhan', 'dang_xu_ly', 'pending'], true))->count()
        : 0;
    $totalRevenue = method_exists($orders, 'sum')
        ? $orders->sum(fn ($order) => (float) ($order->tong_thanh_toan ?? $order->tong_tien_hang ?? 0))
        : 0;
@endphp

<div class="w-full">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <p class="text-neon-green font-mono text-[10px] tracking-[0.3em] mb-2 uppercase">
                SYSTEM_MODULE // ORDERS_V5.0
            </p>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)]">
                QUẢN LÝ ĐƠN HÀNG
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.order.index') }}" class="group flex items-center gap-3 bg-transparent border-2 border-neon-green text-neon-green px-6 py-3 font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300">
                <i data-lucide="refresh-cw" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
                <span>TẢI LẠI</span>
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng đơn</p>
                <i data-lucide="shopping-cart" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalOrders }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Active operations</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Đã hoàn tất</p>
                <i data-lucide="check-circle-2" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $completedOrders }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Success rate</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Chờ duyệt</p>
                <i data-lucide="clock" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $pendingOrders }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Needs review</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Doanh thu</p>
                <i data-lucide="trending-up" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Tổng thanh toán</p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="glass-panel p-6 border-l-4 border-l-neon-green mb-12 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Search Orders</label>
            <div class="relative">
                <input
                    type="text"
                    placeholder="MÃ ĐƠN HOẶC TÊN KHÁCH..."
                    class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none transition-colors"
                />
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Status</label>
            <select class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer">
                <option>TẤT CẢ TRẠNG THÁI</option>
                <option>CHỜ DUYỆT</option>
                <option>ĐANG XỬ LÝ</option>
                <option>HOÀN TẤT</option>
            </select>
        </div>

        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Payment</label>
            <select class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer">
                <option>TẤT CẢ PHƯƠNG THỨC</option>
                <option>QR</option>
                <option>COD</option>
            </select>
        </div>

        <button class="h-11 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-[0.2em] transition-all">
            Apply Filter
        </button>
    </div>

    <!-- Order Table -->
    <div class="glass-panel overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-high/80 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Mã đơn</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Khách hàng</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Thanh toán</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Giá trị</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Trạng thái</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($orders as $order)
                        @php
                            $status = strtolower($order->trang_thai ?? 'cho_xac_nhan');
                            $payment = strtolower($order->phuong_thuc_thanh_toan ?? 'qr');
                            $amount = $order->tong_thanh_toan ?? $order->tong_tien_hang ?? 0;
                        @endphp
                        <tr class="group hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4 text-neon-green font-mono text-sm">{{ $order->ma_don_hang ?? '#-' }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold group-hover:text-neon-green transition-colors line-clamp-1">{{ $order->ho_ten_nguoi_nhan ?? 'N/A' }}</div>
                                <div class="text-[9px] font-mono text-gray-500 mt-1 uppercase tracking-wider">SĐT: {{ $order->so_dien_thoai_nhan ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase border bg-white/5 text-gray-400 border-gray-700">
                                    {{ $payment === 'cod' ? 'COD' : 'QR' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-mono font-bold text-neon-green">{{ number_format((float) $amount, 0, ',', '.') }}₫</div>
                            </td>
                            <td class="px-6 py-4">
                                @if(in_array($status, ['hoan_tat', 'completed', 'paid'], true))
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase text-neon-green border border-neon-green/50 bg-neon-green/5">
                                        <div class="w-1 h-1 rounded-full bg-neon-green animate-pulse"></div>
                                        Hoàn tất
                                    </div>
                                @elseif(in_array($status, ['dang_xu_ly', 'processing'], true))
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase text-blue-400 border border-blue-500/20 bg-blue-500/10">
                                        <div class="w-1 h-1 rounded-full bg-blue-400 animate-pulse"></div>
                                        Đang xử lý
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase text-gray-400 border border-gray-700 bg-white/5">
                                        <div class="w-1 h-1 rounded-full bg-gray-500"></div>
                                        Chờ duyệt
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.order.index', $order->ma_don_hang) }}" class="p-2 hover:text-neon-green hover:bg-neon-green/10 transition-colors border border-transparent hover:border-white/10 rounded-lg inline-block">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.order.view', $order->ma_don_hang) }}" 
                                    class="p-2 hover:text-blue-400 hover:bg-blue-400/10 transition-colors border border-transparent hover:border-white/10 rounded-lg inline-block">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <button class="p-2 hover:text-red-500 hover:bg-red-500/10 transition-colors border border-transparent hover:border-white/10 rounded-lg">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 uppercase tracking-widest">Chưa có đơn hàng nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-6 py-4">
        <div class="text-[10px] font-mono text-gray-500 uppercase tracking-widest">
            Displaying <span class="text-neon-green font-bold">{{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }}</span> of <span class="text-gray-300">{{ $orders->total() ?? 0 }}</span> Records Identified
        </div>

        <div class="flex items-center gap-1">
            {{ $orders->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
