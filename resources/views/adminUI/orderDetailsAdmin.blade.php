@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng #' . ($order->ma_don_hang ?? ''))

@section('content')
<div class="space-y-8 animate-fadeIn">
    <!-- Session Messages -->
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-neon-green/10 border border-neon-green/20 rounded-xl text-neon-green">
            <i data-lucide="check-circle-2" class="size-5"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500">
            <i data-lucide="alert-circle" class="size-5"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between border-b border-white/5 pb-6">
        <a href="{{ route('admin.order.index') }}" class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-neon-green transition-colors">
            <i data-lucide="arrow-left" class="size-4"></i> Quay lại danh sách
        </a>
    </div>

    <!-- Order Header & Status Selector -->
    <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
        <div>
            <div class="mb-2 flex items-center gap-3">
                <span class="border border-neon-green/30 bg-neon-green/10 px-3 py-1 font-mono text-[10px] tracking-widest text-neon-green">#{{ $order->ma_don_hang }}</span>
                <span class="text-xs text-gray-500">Khởi tạo: {{ $order->created_at->format('d/m/Y - H:i') }}</span>
            </div>
            <h2 class="font-display text-4xl font-bold tracking-tight text-white">Đơn hàng #{{ $order->ma_don_hang }}</h2>
        </div>

        <div class="space-y-1">
            <form action="{{ route('admin.order.updateStatus', $order->ma_don_hang) }}" method="POST" id="status-form">
                @csrf
                <label class="block text-right font-display text-[10px] font-bold uppercase tracking-[0.2em] text-neon-green mb-2">Trạng Thái Đơn Hàng</label>
                <div class="glass-panel border border-white/10 hover:border-neon-green/30 shadow-[0_0_15px_rgba(0,229,91,0.02)] flex items-center gap-4 px-6 py-3 transition-all duration-300">
                    <div class="h-2.5 w-2.5 rounded-full {{ 
                        $order->trang_thai === 'cho_thanh_toan' ? 'bg-yellow-500 shadow-[0_0_10px_#eab308]' :
                        ($order->trang_thai === 'da_nhan_hang' ? 'bg-blue-500 shadow-[0_0_10px_#3b82f6]' : 
                        ($order->trang_thai === 'da_huy' ? 'bg-red-500 shadow-[0_0_10px_#ef4444]' : 
                        'bg-neon-green shadow-[0_0_10px_#00e55b]'))
                    }}"></div>
                    @if($order->trang_thai === 'cho_thanh_toan')
                        <span class="text-yellow-400 font-bold uppercase tracking-widest text-sm">Chờ thanh toán</span>
                    @else
                        <select name="trang_thai" onchange="document.getElementById('status-form').submit()" class="bg-transparent text-white font-display text-sm font-bold uppercase tracking-widest outline-none border-none cursor-pointer focus:ring-0 pr-8">
                            @if($order->phuong_thuc_thanh_toan != 'momo') <option value="cho_xac_nhan" {{ $order->trang_thai === 'cho_xac_nhan' ? 'selected' : '' }} class="bg-surface text-white">Chờ xác nhận</option>@endif
                            <option value="da_xac_nhan" {{ $order->trang_thai === 'da_xac_nhan' ? 'selected' : '' }} class="bg-surface text-white">Đã xác nhận</option>
                            <option value="dang_giao_hang" {{ $order->trang_thai === 'dang_giao_hang' ? 'selected' : '' }} class="bg-surface text-white">Đang vận chuyển</option>
                            <option value="da_nhan_hang" {{ $order->trang_thai === 'da_nhan_hang' ? 'selected' : '' }} class="bg-surface text-white">Đã hoàn thành</option>
                            <option value="da_huy" {{ $order->trang_thai === 'da_huy' ? 'selected' : '' }} class="bg-surface text-white">Đã hủy</option>
                        </select>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Summary & Price Summary -->
    <div class="grid grid-cols-1 gap-0 overflow-hidden rounded-xl border border-white/5 glass-panel lg:grid-cols-2">
        <!-- Customer Info -->
        <div class="border-b border-white/5 p-8 lg:border-b-0 lg:border-r">
            <h4 class="mb-6 font-display text-xs font-bold uppercase tracking-[0.25em] text-neon-green">Thông Tin Khách Hàng</h4>
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-6 sm:gap-16">
                    <div class="flex items-center gap-4">
                      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neon-green/10 text-neon-green">
                        <i data-lucide="user" class="size-5"></i>
                      </div>
                      <div>
                        <p class="font-bold text-white">{{ $order->ho_ten_nguoi_nhan }}</p>
                        <p class="text-xs text-gray-500 mt-1">Mã KH: {{ substr($order->ma_nguoi_dung, -8) }}</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-4">
                      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neon-green/10 text-neon-green">
                        <i data-lucide="smartphone" class="size-5"></i>
                      </div>
                      <p class="text-sm text-gray-300 font-mono">{{ $order->so_dien_thoai_nhan }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                  <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neon-green/10 text-neon-green">
                    <i data-lucide="map-pin" class="size-5"></i>
                  </div>
                  <p class="text-sm text-gray-300">{{ $order->dia_chi_giao_hang }}</p>
                </div>
                @if($order->ghi_chu)
                <div class="flex items-center gap-4">
                  <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neon-green/10 text-neon-green">
                    <i data-lucide="file-text" class="size-5"></i>
                  </div>
                  <p class="text-sm text-gray-400 italic">"{{ $order->ghi_chu }}"</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Price Summary -->
        <div class="flex flex-col items-center justify-center bg-white/[0.01] p-8 text-center">
            <p class="mb-2 text-xs uppercase tracking-widest text-gray-500">Tổng Giá Trị</p>
            <h3 class="font-display text-5xl font-bold tracking-tight text-neon-green neon-text-glow">{{ number_format($order->tong_thanh_toan, 0, ',', '.') }}đ</h3>
            <p class="mt-2 flex items-center gap-2 text-[10px] font-medium text-neon-green/80">
                <i data-lucide="check-circle-2" class="size-3.5"></i> Đã bao gồm thuế GTGT (10%)
            </p>
            
            <button onclick="window.print()" class="mt-8 flex items-center gap-3 bg-neon-green px-10 py-4 font-display text-sm font-bold uppercase tracking-widest text-black hover:brightness-110 active:scale-[0.98] transition-all shadow-[0_0_20px_rgba(0,229,91,0.2)]">
                <i data-lucide="file-text" class="size-4"></i> In Hóa Đơn Đơn Hàng
            </button>
        </div>
    </div>

    <!-- Product List Table -->
    <div class="glass-panel overflow-hidden border border-white/5 rounded-xl">
        <div class="flex items-center justify-between border-b border-white/5 bg-white/[0.02] p-6">
            <h4 class="font-display text-xs font-bold uppercase tracking-[0.25em] text-neon-green">Danh Sách Sản Phẩm</h4>
            <span class="font-mono text-xs text-gray-500">{{ $orderItems->count() }} Sản phẩm</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                  <tr class="border-b border-white/5 text-left font-display text-[10px] uppercase tracking-widest text-gray-500">
                    <th class="px-8 py-4 font-medium">Sản phẩm</th>
                    <th class="px-8 py-4 font-medium">Số lượng</th>
                    <th class="px-8 py-4 font-medium">Đơn giá</th>
                    <th class="px-8 py-4 text-right font-medium">Thành tiền</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($orderItems as $item)
                  @php
                      $variant = $item->variant;
                  @endphp
                  <tr class="border-b border-white/[0.02] hover:bg-white/[0.01] transition-colors">
                    <td class="px-8 py-6">
                      <div class="flex items-center gap-4">
                        <div class="h-16 w-16 overflow-hidden border border-white/10 bg-surface-high flex-shrink-0">
                          <img 
                            src="{{ $variant->link_anh_bien_the ?? $item->link_anh_dai_dien ?? 'https://via.placeholder.com/150' }}" 
                            alt="{{ $variant->ten_bien_the ?? $item->ten_san_pham }}" 
                            class="h-full w-full object-cover" 
                          />
                        </div>
                        <div>
                          <p class="font-bold text-white uppercase text-sm tracking-wide">{{ $variant->ten_bien_the ?? $item->ten_san_pham }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-8 py-6 text-sm font-bold text-white">x{{ $item->so_luong }}</td>
                    <td class="px-8 py-6 text-sm text-gray-300 font-mono">{{ number_format($item->gia_ban, 0, ',', '.') }}đ</td>
                    <td class="px-8 py-6 text-right font-display text-sm font-bold text-neon-green font-mono">
                        {{ number_format($item->gia_ban * $item->so_luong, 0, ',', '.') }}đ
                    </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
