@extends('layouts.app')

@php
    use App\OrderStatus;

    // Logic tự động lấy dữ liệu còn thiếu nếu không được truyền từ Controller
    if (!isset($orders) && isset($order)) {
        $orders = \App\Models\Order::where('ma_nguoi_dung', $order->ma_nguoi_dung)->latest()->get();
    }
    
    $currentMaDonHang = $order->ma_don_hang ?? null;

    $tabsMap = [
        OrderStatus::PENDING_PAYMENT->value    => 'CHỜ THANH TOÁN',
        OrderStatus::PENDING_CONFIRMATION->value => 'CHỜ XÁC NHẬN',
        OrderStatus::WAITING_PICKUP->value     => 'CHỜ LẤY HÀNG',
        OrderStatus::WAITING_DELIVERY->value   => 'CHỜ GIAO HÀNG',
        OrderStatus::DELIVERED->value          => 'ĐÃ GIAO',
    ];

    $statusBadgeClasses = [
        OrderStatus::PENDING_PAYMENT->value    => 'bg-yellow-500/10 border-yellow-500/30 text-yellow-400',
        OrderStatus::PENDING_CONFIRMATION->value => 'bg-blue-500/10 border-blue-500/30 text-blue-400',
        OrderStatus::WAITING_PICKUP->value     => 'bg-purple-500/10 border-purple-500/30 text-purple-400',
        OrderStatus::WAITING_DELIVERY->value   => 'bg-cyan-500/10 border-cyan-500/30 text-cyan-400',
        OrderStatus::DELIVERED->value          => 'bg-neon-green/10 border-neon-green/30 text-neon-green',
    ];

    $resolveOrderStatus = function ($order) {
        return $order->trang_thai ?? '';
    };

    $defaultStatusText = 'CHỜ XÁC NHẬN';
@endphp

@section('title', isset($order) ? 'Chi tiết đơn hàng #' . $order->ma_don_hang : 'Lịch sử đơn hàng')

@section('content')
<style>
    .grid-bg {
        background-image: radial-gradient(rgba(0, 255, 102, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .text-neon-green { color: #00FF66; }
    .bg-neon-green { background-color: #00FF66; }
    .border-neon-green { border-color: #00FF66; }
    .active-order-glow {
        box-shadow: 0 0 20px rgba(0, 255, 102, 0.2);
        border-color: #00FF66 !important;
    }
    .glow-sm:hover {
        box-shadow: 0 0 15px rgba(0, 255, 102, 0.4);
    }
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #00FF66; border-radius: 10px; }
</style>

@php
    $ordersData = [];
    if (isset($orders)) {
        foreach($orders as $o) {
            $ordersData[] = [
                'ma_don_hang' => $o->ma_don_hang,
                'status' => $tabsMap[$resolveOrderStatus($o)] ?? $defaultStatusText
            ];
        }
    }
@endphp

<div class="grid-bg min-h-screen" 
     x-data="{ 
        activeTab: 'TẤT CẢ', 
        orders: {{ json_encode($ordersData) }},
        get hasVisibleOrders() {
            if (this.activeTab === 'TẤT CẢ') return this.orders.length > 0;
            return this.orders.some(o => o.status === this.activeTab);
        }
     }">
    <main class="pt-12 pb-20 px-6 max-w-7xl mx-auto">
        <!-- Nút quay lại tách biệt, nằm trên và đẩy header xuống dưới -->
        <div class="mb-6 flex justify-start animate-fadeInUp">
            <a href="{{ url('/') }}" 
               class="flex items-center gap-2 px-4 py-2 border border-white/5 bg-white/[0.02] hover:bg-neon-green/5 hover:border-neon-green/40 rounded-lg text-gray-400 hover:text-neon-green text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group shadow-sm">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1"></i>
                <span>Trang chủ</span>
            </a>
        </div>

        <!-- Tiêu đề chính căn giữa tự nhiên, không sợ bị đè chữ -->
        <header class="mb-12 text-center animate-fadeInUp">
            <h1 class="font-space text-5xl font-bold text-gray-100 uppercase tracking-tight">
                Đơn hàng của tôi
            </h1>
            <p class="text-gray-400 mt-2 uppercase tracking-wide text-sm">
                Quản lý và theo dõi lịch sử đơn đặt hàng của bạn.
            </p>
        </header>

        <!-- Tab Selector -->
        <div class="mb-12 border-b border-white/5 overflow-x-auto custom-scrollbar">
            <div class="flex justify-center gap-10 pb-4 min-w-max">
                @foreach(['TẤT CẢ', 'CHỜ THANH TOÁN', 'CHỜ XÁC NHẬN', 'CHỜ LẤY HÀNG', 'CHỜ GIAO HÀNG', 'ĐÃ GIAO'] as $tab)
                <button
                    @click="activeTab = '{{ $tab }}'"
                    class="whitespace-nowrap text-sm font-bold tracking-widest transition-all duration-300 uppercase relative"
                    :class="activeTab === '{{ $tab }}' ? 'text-neon-green' : 'text-gray-500 hover:text-gray-300'"
                >
                    {{ $tab }}
                    <span x-show="activeTab === '{{ $tab }}'" class="absolute -bottom-[18px] left-0 w-full h-[2px] bg-neon-green shadow-[0_0_10px_#00FF66]"></span>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Order List Container -->
        <div class="mb-8 relative">
            <!-- Empty State -->
            <div x-show="!hasVisibleOrders" 
                 x-transition:enter="transition opacity duration-300"
                 class="py-12 flex flex-col items-center justify-center border border-white/5 bg-white/[0.02] rounded-xl">
                <i data-lucide="package-open" class="w-12 h-12 text-gray-700 mb-4"></i>
                <p class="text-gray-500 italic text-sm tracking-widest uppercase">Không có đơn hàng nào trong danh mục này</p>
            </div>

            <!-- Horizontal List -->
            <div x-show="hasVisibleOrders" class="overflow-x-auto custom-scrollbar pb-4">
                <div class="flex gap-6 min-w-max px-2">
                    @isset($orders)
                        @foreach($orders as $o)
                        @php
                            $resolvedStatus = $resolveOrderStatus($o);
                            $statusText = $tabsMap[$resolvedStatus] ?? $defaultStatusText;
                            $badgeClass = $statusBadgeClasses[$resolvedStatus] ?? $statusBadgeClasses[OrderStatus::PENDING_CONFIRMATION->value];
                            $isActive = $currentMaDonHang == $o->ma_don_hang;
                        @endphp
                        <a
                            x-show="activeTab === 'TẤT CẢ' || activeTab === '{{ $statusText }}'"
                            href="{{ route('order_detail.view', ['ma_don_hang' => $o->ma_don_hang]) }}"
                            class="order-card w-[280px] p-6 rounded-lg cursor-pointer transition-all duration-300 border block {{ $isActive ? 'bg-[#282a2b] border-neon-green active-order-glow' : 'bg-[#1a1c1c] border-white/5 hover:border-neon-green/50' }}"
                        >
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-bold uppercase {{ $isActive ? 'text-neon-green' : 'text-gray-100' }}">
                                    #...{{ substr($o->ma_don_hang, -8) }}
                                </h3>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $badgeClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-400">{{ $o->created_at->format('d/m/Y') }}</p>
                            <p class="mt-4 font-bold text-gray-100">{{ number_format($o->tong_thanh_toan, 0, ',', '.') }} VNĐ</p>
                        </a>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>

        <!-- Detail Content -->
        @if(isset($order))
        <section 
            x-show="activeTab === 'TẤT CẢ' || activeTab === '{{ $tabsMap[$resolveOrderStatus($order)] ?? $defaultStatusText }}'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="w-full bg-[#1a1c1c]/50 border border-white/5 backdrop-blur-md rounded-xl overflow-hidden animate-fadeInUp"
        >
            @php $isMomoOrder = strtolower($order->phuong_thuc_thanh_toan ?? '') === 'momo'; @endphp
            <!-- Detail Header -->
            <div class="p-8 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-2xl font-bold text-gray-100 uppercase">Chi tiết #{{ $order->ma_don_hang }}</h2>
                        <span class="px-3 py-1 bg-neon-green/10 border border-neon-green/30 text-neon-green text-[10px] font-bold rounded uppercase tracking-wider">
                            {{ ($tabsMap[$resolveOrderStatus($order)] ?? $defaultStatusText) }}
                        </span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Ngày đặt hàng: <span class="text-gray-100 font-medium">{{ $order->created_at->format('d \T\h\á\n\g m, Y') }}</span>
                    </p>
                </div>
                @if($isMomoOrder && $order->trang_thai === OrderStatus::PENDING_PAYMENT->value)
                <a href="{{ route('momo.create', ['ma_don_hang' => $order->ma_don_hang]) }}"
                   class="flex items-center gap-2 bg-neon-green text-black px-8 py-3 font-bold text-sm tracking-widest uppercase hover:opacity-90 transition-all duration-300 shadow-[0_0_20px_rgba(0,255,102,0.3)]">
                    <i data-lucide="wallet-cards" class="w-4 h-4"></i>
                    Thanh toán MoMo
                </a>
                @else
                <button class="bg-neon-green text-black px-8 py-3 font-bold text-sm tracking-widest uppercase hover:glow-sm transition-all duration-300">
                    Theo dõi đơn hàng
                </button>
                @endif
            </div>

            <div class="p-8 space-y-16">
                <!-- Shipping Timeline -->
                @php
                    $currentStatus = $resolveOrderStatus($order);

                    // 4 bước cố định, map đúng với giá trị DB của Admin
                    $steps = [
                        ['id' => OrderStatus::PENDING_CONFIRMATION->value, 'status' => 'CHỜ XÁC NHẬN',  'icon' => 'clipboard-check', 'note' => 'Đơn hàng đang chờ shop xác nhận'],
                        ['id' => OrderStatus::WAITING_PICKUP->value,     'status' => 'CHỜ LẤY HÀNG',  'icon' => 'package-check',   'note' => 'Shop đang chuẩn bị, chờ đơn vị vận chuyển lấy hàng'],
                        ['id' => OrderStatus::WAITING_DELIVERY->value,   'status' => 'CHỜ GIAO HÀNG', 'icon' => 'truck',            'note' => 'Đơn hàng đang trên đường giao đến bạn'],
                        ['id' => OrderStatus::DELIVERED->value,          'status' => 'ĐÃ GIAO',        'icon' => 'badge-check',      'note' => 'Đơn hàng đã giao thành công'],
                    ];
                    $statusOrder = array_column($steps, 'id');
                    // Nếu trạng thái không khớp bất kỳ bước nào (vd: cho_thanh_toan) → không tô
                    $currentIndex = array_search($currentStatus, $statusOrder);
                    if ($currentIndex === false) $currentIndex = -1;
                    $progressWidth = ($currentIndex >= 0) ? ($currentIndex / (count($steps) - 1)) * 100 : 0;
                @endphp

                <div class="space-y-10">
                    <h3 class="text-xl mb-8 flex items-center gap-3 uppercase text-gray-100">
                        <i data-lucide="truck" class="text-neon-green"></i> Lộ trình vận chuyển
                    </h3>
                    
                    <div class="relative">
                        <div class="absolute top-6 left-12 right-12 h-[2px] bg-white/5 hidden md:block"></div>
                        <div id="order-progress-line" class="absolute top-6 left-12 h-[2px] bg-neon-green shadow-[0_0_15px_#00FF66] hidden md:block" :style="{ width: '{{ $progressWidth }}%' }"></div>

                        <div class="relative flex flex-col md:flex-row justify-between items-start gap-8 md:gap-4">
                            @foreach($steps as $idx => $step)
                                @php
                                    $isCompleted = $idx <= $currentIndex;
                                    $isActive = $idx == $currentIndex;
                                @endphp
                                <div class="flex flex-row md:flex-col items-center md:items-center gap-4 md:gap-3 flex-1 {{ !$isCompleted && !$isActive ? 'opacity-40' : '' }}">
                                    <div class="relative">
                                        @if($isActive)
                                            <div class="absolute inset-0 rounded-full bg-neon-green/20 animate-ping"></div>
                                        @endif
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center z-10 relative border-2 {{
                                            $isCompleted ? 'bg-neon-green border-neon-green text-black shadow-[0_0_15px_rgba(0,255,102,0.4)]' :
                                            ($isActive ? 'bg-[#333535] border-neon-green text-neon-green shadow-[0_0_20px_rgba(0,255,102,0.3)]' :
                                            'bg-[#1a1c1c] border-white/10 text-gray-400')
                                        }}">
                                            @if($isCompleted) <i data-lucide="check" class="w-5 h-5"></i> @else <i data-lucide="{{ $step['icon'] }}" class="w-5 h-5"></i> @endif
                                        </div>
                                    </div>
                                    <div class="text-left md:text-center">
                                        <p class="text-xs font-bold uppercase tracking-tight {{ $isActive || $isCompleted ? 'text-neon-green' : 'text-gray-400' }}">
                                            {{ $step['status'] }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">VNTech Protocol</p>
                                        <p class="text-[10px] italic mt-0.5 {{ $isActive ? 'text-neon-green/80 font-bold' : 'text-gray-500' }}">
                                            {{ $step['note'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Selected Products -->
                <div class="space-y-6">
                    <h3 class="text-xl flex items-center gap-3 uppercase text-gray-100">
                        <i data-lucide="shopping-bag" class="text-neon-green"></i> Sản phẩm đã chọn
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        @if(isset($orderItems))
                        @foreach($orderItems as $product)
                        <div class="flex gap-6 bg-[#282a2b]/50 p-6 border border-white/5 rounded-lg group hover:border-neon-green/30 transition-all duration-300">
                            @php
                                $variant = $product->variant;
                            @endphp
                            <div class="w-24 h-24 flex-shrink-0 bg-[#333535] rounded overflow-hidden border border-white/5">
                                <img src="{{ $variant->link_anh_bien_the ?? 'https://via.placeholder.com/150' }}" 
                                     alt="{{ $variant->ten_bien_the }}" 
                                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-lg text-gray-100 group-hover:text-neon-green transition-colors uppercase font-bold tracking-tight">
                                        {{ $variant->ten_bien_the ?? $product->ten_san_pham }}
                                    </h4>
                                    <span class="text-lg text-neon-green font-bold">
                                        {{ number_format($product->gia_ban, 0, ',', '.') }} VNĐ
                                    </span>
                                </div>
                                <div class="mt-4 flex items-center gap-4 border-t border-white/5 pt-4">
                                    <span class="text-xs text-gray-500 uppercase font-bold">Số lượng: <span class="text-neon-green">{{ $product->so_luong }}</span></span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                <!-- Shipping & Payment Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-white/10">
                    <div class="space-y-6">
                        <h3 class="text-lg flex items-center gap-3 uppercase text-gray-100 leading-none">
                            <i data-lucide="map-pin" class="text-neon-green"></i> Thông tin nhận hàng
                        </h3>
                        <div class="space-y-2 text-sm text-gray-400">
                            <p class="text-gray-100 font-bold text-base">{{ $order->ho_ten_nguoi_nhan }}</p>
                            <p>{{ $order->so_dien_thoai_nhan }}</p>
                            <p>{{ $order->dia_chi_giao_hang }}</p>
                        </div>
                    </div>

                    <div class="bg-[#333535]/30 p-8 border border-white/5 rounded-xl">
                        <h3 class="text-lg mb-6 uppercase text-gray-100">Tổng kết thanh toán</h3>
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between text-gray-400">
                                <span class="uppercase">Tạm tính</span>
                                <span>{{ number_format($order->tong_tien_hang, 0, ',', '.') }} VNĐ</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span class="uppercase">Phí vận chuyển</span>
                                <span>{{ number_format($order->phi_van_chuyen ?? 0, 0, ',', '.') }} VNĐ</span>
                            </div>
                            @if($order->gia_tri_giam_voucher > 0)
                            <div class="flex justify-between text-gray-400">
                                <span class="uppercase">Giảm giá (Voucher)</span>
                                <span class="text-neon-green">- {{ number_format($order->gia_tri_giam_voucher, 0, ',', '.') }} VNĐ</span>
                            </div>
                            @endif
                            <div class="pt-4 mt-4 border-t border-white/10 flex justify-between items-center">
                                <span class="font-bold text-gray-100 text-base uppercase">Tổng cộng</span>
                                <span class="text-2xl text-neon-green">{{ number_format($order->tong_thanh_toan, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @else
        <!-- Placeholder khi chưa chọn đơn hàng -->
        <section class="w-full bg-[#1a1c1c]/30 border border-white/5 border-dashed rounded-xl overflow-hidden py-32 flex flex-col items-center justify-center animate-fadeInUp">
            <div class="relative mb-6">
                <div class="absolute inset-0 bg-neon-green/20 blur-xl rounded-full"></div>
                <i data-lucide="mouse-pointer-click" class="w-16 h-16 text-neon-green relative z-10 animate-bounce"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-100 uppercase tracking-widest mb-2">Chưa chọn đơn hàng</h2>
            <p class="text-gray-500 text-sm tracking-wider uppercase">Hãy chọn một đơn hàng từ danh sách bên trên để xem chi tiết</p>
        </section>
        @endif
    </main>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
