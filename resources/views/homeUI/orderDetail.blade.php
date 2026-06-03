@extends('layouts.app')

@php
    use App\OrderStatus;

    // Retrieve missing order history if not passed directly
    if (!isset($orders) && auth()->check()) {
        $orders = \App\Models\Order::where('ma_nguoi_dung', auth()->id())->latest()->get();
    } elseif (!isset($orders)) {
        $orders = collect();
    }
    
    $currentMaDonHang = $order->ma_don_hang ?? (request('ma_don_hang') ?? null);

    $tabsMap = [
        OrderStatus::PENDING_PAYMENT->value    => 'CHỜ THANH TOÁN',
        OrderStatus::PENDING_CONFIRMATION->value => 'CHỜ XÁC NHẬN',
        OrderStatus::WAITING_PICKUP->value     => 'CHỜ LẤY HÀNG',
        OrderStatus::WAITING_DELIVERY->value   => 'CHỜ GIAO HÀNG',
        OrderStatus::DELIVERED->value          => 'ĐÃ GIAO',
    ];

    $statusBadgeClasses = [
        OrderStatus::PENDING_PAYMENT->value    => 'bg-amber-50 border-amber-200 text-amber-700 text-xs',
        OrderStatus::PENDING_CONFIRMATION->value => 'bg-blue-50 border-blue-200 text-blue-700 text-xs',
        OrderStatus::WAITING_PICKUP->value     => 'bg-indigo-50 border-indigo-200 text-indigo-700 text-xs',
        OrderStatus::WAITING_DELIVERY->value   => 'bg-sky-50 border-sky-200 text-sky-700 text-xs',
        OrderStatus::DELIVERED->value          => 'bg-emerald-50 border-emerald-200 text-emerald-700 text-xs',
    ];

    $resolveOrderStatus = function ($order) {
        return $order->trang_thai ?? '';
    };

    $defaultStatusText = 'CHỜ XÁC NHẬN';

    $ordersData = [];
    foreach($orders as $o) {
        $ordersData[] = [
            'ma_don_hang' => $o->ma_don_hang,
            'status' => $tabsMap[$resolveOrderStatus($o)] ?? $defaultStatusText
        ];
    }
@endphp

@section('title', 'Đơn hàng của tôi')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #FAF8F2; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<div class="min-h-screen bg-[#FAF8F2] font-sans text-slate-800 flex flex-col relative"
     x-data="{ 
        activeTab: 'TẤT CẢ', 
        orders: {{ json_encode($ordersData) }},
        get hasVisibleOrders() {
            if (this.activeTab === 'TẤT CẢ') return this.orders.length > 0;
            return this.orders.some(o => o.status === this.activeTab);
        }
     }">
    <main class="max-w-7xl mx-auto px-4 sm:px-8 pt-10 pb-16 flex-1 w-full">
        
        <!-- Tiêu đề chính -->
        <header class="mb-10 text-center relative flex flex-col items-center justify-center min-h-[60px]">
            <a href="{{ url('/') }}" 
               class="absolute left-0 top-1/2 -translate-y-1/2 flex items-center gap-1.5 px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 rounded-xl text-slate-500 hover:text-slate-800 text-xs font-black uppercase tracking-wider transition-all duration-300 group shadow-xs no-underline">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5"></i>
                <span class="hidden sm:inline">Về trang chủ</span>
            </a>
            <h1 class="font-space text-3xl font-black text-slate-800 uppercase tracking-tight">
                Đơn hàng của tôi
            </h1>
            <p class="text-slate-400 mt-1 uppercase tracking-wider text-xs font-semibold">
                Quản lý và theo dõi lộ trình các đơn đặt hàng của bạn.
            </p>
        </header>

        <!-- Tab Selector -->
        <div class="mb-8 border-b border-slate-200/80 overflow-x-auto custom-scrollbar">
            <div class="flex justify-center gap-8 pb-3 min-w-max">
                @foreach(['TẤT CẢ', 'CHỜ THANH TOÁN', 'CHỜ XÁC NHẬN', 'CHỜ LẤY HÀNG', 'CHỜ GIAO HÀNG', 'ĐÃ GIAO'] as $tab)
                <button
                    @click="activeTab = '{{ $tab }}'"
                    class="whitespace-nowrap text-sm font-black tracking-wider transition-all duration-300 uppercase relative pb-1 cursor-pointer"
                    :class="activeTab === '{{ $tab }}' ? 'text-brand-500' : 'text-slate-400 hover:text-slate-700'"
                >
                    {{ $tab }}
                    <span x-show="activeTab === '{{ $tab }}'" class="absolute -bottom-[13px] left-0 w-full h-[3px] bg-brand-500 rounded-t-md"></span>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Orders Vertical Container List -->
        <div class="space-y-6 relative">
            <!-- Empty State -->
            <div x-show="!hasVisibleOrders" 
                 x-transition:enter="transition opacity duration-300"
                 class="py-16 flex flex-col items-center justify-center border border-dashed border-slate-200 bg-white rounded-3xl p-8 shadow-xs">
                <div class="p-4 bg-slate-50 text-slate-400 rounded-full mb-4">
                    <i data-lucide="package-open" class="w-10 h-10"></i>
                </div>
                <p class="text-slate-400 font-bold text-sm tracking-wider uppercase">Không tìm thấy đơn hàng nào trong danh mục này</p>
            </div>

            <!-- Shopee Vertical Cards List -->
            @isset($orders)
                @foreach($orders as $o)
                @php
                    $resolvedStatus = $resolveOrderStatus($o);
                    $statusText = $tabsMap[$resolvedStatus] ?? $defaultStatusText;
                    $badgeClass = $statusBadgeClasses[$resolvedStatus] ?? $statusBadgeClasses[OrderStatus::PENDING_CONFIRMATION->value];
                    $items = \App\Models\OrderItem::where('ma_don_hang', $o->ma_don_hang)->with('variant.product')->get();
                @endphp
                
                <div x-data="{ expanded: {{ ($o->ma_don_hang === $currentMaDonHang) ? 'true' : 'false' }} }"
                     x-show="activeTab === 'TẤT CẢ' || activeTab === '{{ $statusText }}'"
                     x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-98"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white border border-slate-200/60 rounded-3xl p-5 sm:p-6 shadow-[0_10px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:border-brand-500/20 flex flex-col gap-4 text-left"
                     :class="expanded ? 'border-brand-500/20 ring-1 ring-brand-500/5 shadow-[0_15px_40px_rgba(255,79,0,0.02)]' : ''"
                >
                    <!-- Header -->
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-sm font-black text-slate-800 tracking-wider">ĐƠN HÀNG: #{{ $o->ma_don_hang }}</span>
                            <span class="text-xs text-slate-400 font-bold font-mono">({{ $o->created_at->format('d/m/Y H:i') }})</span>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-black uppercase border {{ $badgeClass }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    <!-- Products List inside this order -->
                    <div class="divide-y divide-slate-100">
                        @foreach($items as $product)
                            @php $variant = $product->variant; @endphp
                            <div class="flex gap-5 py-4 items-center first:pt-0 last:pb-0">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-150 p-1.5 rounded-xl flex-shrink-0 flex items-center justify-center">
                                    <img src="{{ $variant->link_anh_bien_the ?? 'https://via.placeholder.com/150' }}" 
                                         alt="{{ $variant->ten_bien_the ?? $product->ten_san_pham }}" 
                                         class="w-full h-full object-contain">
                                </div>
                                <div class="flex-grow min-w-0">
                                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate">
                                        {{ $variant->ten_bien_the ?? $product->ten_san_pham }}
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-1 uppercase font-bold">
                                        Số lượng: <span class="text-brand-500 font-black">{{ $product->so_luong }}</span>
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-sm font-black text-slate-850">
                                        {{ number_format((float) ($product->gia_ban ?? 0), 0, ',', '.') }}₫
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Price and Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4 border-t border-slate-100 mt-1">
                        <div class="flex items-baseline gap-2">
                            <span class="text-xs text-slate-400 uppercase tracking-wider font-bold">Tổng thanh toán:</span>
                            <span class="text-xl font-black text-brand-500">{{ number_format((float) ($o->tong_thanh_toan ?? 0), 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto justify-end">
                            <!-- Toggle details button -->
                            <button @click="expanded = !expanded"
                                    class="flex items-center gap-1.5 px-4 py-2 border border-slate-200 hover:border-slate-350 hover:bg-slate-50 text-slate-500 hover:text-slate-850 text-xs font-black uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                                <span x-text="expanded ? 'Ẩn chi tiết' : 'Chi tiết lộ trình'"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                            </button>

                            @php $isMomo = strtolower($o->phuong_thuc_thanh_toan ?? '') === 'momo'; @endphp
                            @if($isMomo && $o->trang_thai === OrderStatus::PENDING_PAYMENT->value)
                                <a href="{{ route('momo.create', ['ma_don_hang' => $o->ma_don_hang]) }}"
                                   class="inline-flex items-center justify-center gap-1.5 bg-[#A50064] text-white px-4 py-2 rounded-xl font-black text-xs tracking-wider uppercase hover:opacity-90 active:scale-95 transition-all shadow-xs no-underline">
                                    <i data-lucide="wallet-cards" class="w-4 h-4"></i>
                                    Thanh toán MoMo
                                </a>
                            @elseif($o->trang_thai === OrderStatus::DELIVERED->value)
                                <a href="{{ route('admin.order.print', ['ma_don_hang' => $o->ma_don_hang]) }}" target="_blank"
                                   class="inline-flex items-center justify-center gap-1.5 bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-xl font-black text-xs tracking-wider uppercase hover:shadow-[0_4px_12px_rgba(12,135,235,0.2)] active:scale-95 transition-all shadow-xs no-underline">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                    In hoá đơn
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Collapsible tracking detail block -->
                    <div x-show="expanded"
                         x-transition:enter="transition ease-out duration-300 transform opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="pt-5 border-t border-slate-100 space-y-6"
                         x-cloak
                    >
                         <!-- TIMELINE STEPPER -->
                         @php
                             $currentStatus = $resolveOrderStatus($o);
                             $steps = [
                                 ['id' => OrderStatus::PENDING_CONFIRMATION->value, 'status' => 'CHỜ XÁC NHẬN',  'icon' => 'clipboard-check', 'note' => 'Đang chờ shop duyệt'],
                                 ['id' => OrderStatus::WAITING_PICKUP->value,     'status' => 'CHỜ LẤY HÀNG',  'icon' => 'package-check',   'note' => 'Shop đang đóng gói'],
                                 ['id' => OrderStatus::WAITING_DELIVERY->value,   'status' => 'CHỜ GIAO HÀNG', 'icon' => 'truck',            'note' => 'Đang giao tới bạn'],
                                 ['id' => OrderStatus::DELIVERED->value,          'status' => 'ĐÃ GIAO',        'icon' => 'badge-check',      'note' => 'Giao thành công'],
                             ];
                             $statusOrder = array_column($steps, 'id');
                             $currentIndex = array_search($currentStatus, $statusOrder);
                             if ($currentIndex === false) $currentIndex = -1;
                             $progressWidth = ($currentIndex >= 0) ? ($currentIndex / (count($steps) - 1)) * 100 : 0;
                         @endphp
                         
                         <div class="space-y-4">
                             <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                                 <i data-lucide="truck" class="text-brand-500 w-4 h-4"></i> Lộ trình vận đơn
                             </h5>
                             
                             <div class="relative pt-4 pb-4">
                                 <!-- Bounded Progress Track -->
                                 <div class="absolute top-10 left-[12.5%] right-[12.5%] h-[3px] bg-slate-100 hidden md:block rounded-full">
                                     <div class="absolute inset-y-0 left-0 bg-brand-500 rounded-full transition-all duration-500" :style="{ width: '{{ $progressWidth }}%' }"></div>
                                 </div>

                                 <div class="relative flex flex-col md:flex-row justify-between items-start gap-6 md:gap-4">
                                     @foreach($steps as $idx => $step)
                                         @php
                                             $isCompleted = $idx <= $currentIndex;
                                             $isActive = $idx == $currentIndex;
                                         @endphp
                                         <div class="flex flex-row md:flex-col items-center gap-3 flex-1 w-full {{ !$isCompleted && !$isActive ? 'opacity-40' : '' }}">
                                             <div class="relative shrink-0">
                                                 @if($isActive)
                                                     <div class="absolute inset-0 rounded-full bg-brand-500/20 animate-ping"></div>
                                                 @endif
                                                 <div class="w-10 h-10 rounded-full flex items-center justify-center z-10 relative border-2 transition-all {{
                                                     $isCompleted ? 'bg-brand-500 border-brand-500 text-white shadow-xs' :
                                                     ($isActive ? 'bg-white border-brand-500 text-brand-500 shadow-sm' :
                                                     'bg-white border-slate-200 text-slate-400')
                                                 }}">
                                                     @if($isCompleted && !$isActive) 
                                                         <i data-lucide="check" class="w-4 h-4"></i> 
                                                     @else 
                                                         <i data-lucide="{{ $step['icon'] }}" class="w-4 h-4"></i> 
                                                     @endif
                                                 </div>
                                             </div>
                                             <div class="text-left md:text-center">
                                                 <p class="text-xs font-black uppercase tracking-wider {{ $isActive || $isCompleted ? 'text-brand-500' : 'text-slate-500' }}">
                                                     {{ $step['status'] }}
                                                 </p>
                                                 <p class="text-[10px] text-slate-400 font-semibold mt-0.5 leading-none">VNTech Logistics</p>
                                                 <p class="text-[10px] mt-1 font-semibold leading-relaxed {{ $isActive ? 'text-brand-500 font-extrabold' : 'text-slate-400' }}">
                                                     {{ $step['note'] }}
                                                 </p>
                                             </div>
                                         </div>
                                     @endforeach
                                 </div>
                             </div>
                         </div>

                         <!-- RECIPIENT & BILLING DETAILS -->
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                             <div class="space-y-3">
                                 <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                                     <i data-lucide="map-pin" class="text-brand-500 w-4 h-4"></i> Thông tin nhận hàng
                                 </h5>
                                 <div class="space-y-2 text-xs text-slate-500 leading-relaxed bg-slate-50/50 p-4.5 rounded-2xl border border-slate-200/60">
                                     <p class="text-slate-800 font-bold text-sm">{{ $o->ho_ten_nguoi_nhan }}</p>
                                     <p class="font-medium text-xs">Số điện thoại: <span class="font-bold text-slate-700">{{ $o->so_dien_thoai_nhan }}</span></p>
                                     <p class="font-medium text-xs">Địa chỉ giao hàng: <span class="text-slate-700 font-semibold">{{ $o->dia_chi_giao_hang }}</span></p>
                                 </div>
                             </div>

                             <div class="bg-slate-50/50 p-4.5 border border-slate-200/60 rounded-2xl text-xs">
                                 <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider mb-3 leading-none">Chi tiết thanh toán</h5>
                                 <div class="space-y-2.5">
                                     <div class="flex justify-between text-slate-400 font-medium">
                                         <span>TỔNG TIỀN HÀNG</span>
                                         <span class="font-bold text-slate-600">{{ number_format((float) ($o->tong_tien_hang ?? 0), 0, ',', '.') }}₫</span>
                                     </div>
                                     <div class="flex justify-between text-slate-400 font-medium">
                                         <span>PHÍ VẬN CHUYỂN</span>
                                         <span class="font-bold text-slate-600">{{ number_format((float) ($o->phi_van_chuyen ?? 0), 0, ',', '.') }}₫</span>
                                     </div>
                                     @if($o->gia_tri_giam_voucher > 0)
                                     <div class="flex justify-between text-slate-400 font-medium">
                                         <span>GIẢM VOUCHER</span>
                                         <span class="text-red-500 font-bold">-{{ number_format((float) ($o->gia_tri_giam_voucher ?? 0), 0, ',', '.') }}₫</span>
                                     </div>
                                     @endif
                                     <div class="pt-2 mt-2 border-t border-slate-200 flex justify-between items-center">
                                         <span class="font-black text-slate-700 text-xs uppercase tracking-wider">TỔNG THANH TOÁN</span>
                                         <span class="text-base font-black text-brand-500">{{ number_format((float) ($o->tong_thanh_toan ?? 0), 0, ',', '.') }}₫</span>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
                @endforeach
            @endisset
        </div>
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
