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
                
                <div x-data="{ 
                        expanded: {{ ($o->ma_don_hang === $currentMaDonHang) ? 'true' : 'false' }},
                        reviewModalOpen: false
                     }"
                     data-order-card
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
                            @php
                                $variant = $product->variant;
                                $productDetailUrl = $variant?->ma_san_pham ? route('viewProductDetail', $variant->ma_san_pham) : null;
                            @endphp
                            <div class="flex gap-5 py-4 items-center first:pt-0 last:pb-0">
                                <a href="{{ $productDetailUrl ?? '#' }}"
                                   class="w-16 h-16 bg-slate-50 border border-slate-150 p-1.5 rounded-xl flex-shrink-0 flex items-center justify-center transition-all {{ $productDetailUrl ? 'hover:border-brand-500 hover:bg-orange-50/30' : 'pointer-events-none' }}">
                                    <img src="{{ $variant->link_anh_bien_the ?? 'https://via.placeholder.com/150' }}" 
                                         alt="{{ $variant->ten_bien_the ?? $product->ten_san_pham }}" 
                                         class="w-full h-full object-contain">
                                </a>
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
                            @if(($o->review_action ?? 'none') !== 'none')
                                <button type="button"
                                        @click="reviewModalOpen = true; $nextTick(() => window.loadOrderReviews('{{ $o->ma_don_hang }}', $el.closest('[data-order-card]')))"
                                        class="inline-flex items-center justify-center gap-1.5 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-xl font-black text-xs tracking-wider uppercase hover:shadow-[0_4px_12px_rgba(255,79,0,0.2)] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    <i data-lucide="star" class="w-4 h-4"></i>
                                    {{ ($o->review_action ?? 'none') === 'view' ? 'Xem đánh giá' : 'Đánh giá sản phẩm' }}
                                </button>
                            @endif

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
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                              <!-- Recipient Card -->
                              <div class="bg-slate-50/50 p-6 border border-slate-200/60 rounded-3xl text-xs flex flex-col gap-4">
                                  <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5 leading-none">
                                      <i data-lucide="map-pin" class="text-brand-500 w-4 h-4"></i> Thông tin nhận hàng
                                  </h5>
                                  <div class="space-y-3 text-slate-500 leading-relaxed">
                                      <p class="text-slate-800 font-extrabold text-sm flex items-center gap-2">
                                          {{ $o->ho_ten_nguoi_nhan }}
                                      </p>
                                      <div class="grid grid-cols-1 gap-2.5 pt-2 border-t border-slate-200/50">
                                          <p class="font-medium text-xs">Số điện thoại: <span class="font-bold text-slate-700">{{ $o->so_dien_thoai_nhan }}</span></p>
                                          <p class="font-medium text-xs leading-normal">Địa chỉ giao hàng: <span class="text-slate-700 font-semibold">{{ $o->dia_chi_giao_hang }}</span></p>
                                      </div>
                                  </div>
                              </div>

                              <!-- Billing Details Card -->
                              <div class="bg-slate-50/50 p-6 border border-slate-200/60 rounded-3xl text-xs flex flex-col gap-4">
                                  <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5 leading-none">
                                      <i data-lucide="receipt-text" class="text-brand-500 w-4 h-4"></i> Chi tiết thanh toán
                                  </h5>
                                  <div class="space-y-3">
                                      <div class="flex justify-between text-slate-500 font-medium">
                                          <span class="uppercase tracking-wider">TỔNG TIỀN HÀNG</span>
                                          <span class="font-black text-slate-700">{{ number_format((float) ($o->tong_tien_hang ?? 0), 0, ',', '.') }}₫</span>
                                      </div>
                                      <div class="flex justify-between text-slate-500 font-medium">
                                          <span class="uppercase tracking-wider">PHÍ VẬN CHUYỂN</span>
                                          <span class="font-black text-slate-700">{{ number_format((float) ($o->phi_van_chuyen ?? 0), 0, ',', '.') }}₫</span>
                                      </div>
                                      @if($o->gia_tri_giam_voucher > 0)
                                      <div class="flex justify-between text-slate-500 font-medium">
                                          <span class="uppercase tracking-wider">GIẢM VOUCHER</span>
                                          <span class="text-rose-500 font-black">-{{ number_format((float) ($o->gia_tri_giam_voucher ?? 0), 0, ',', '.') }}₫</span>
                                      </div>
                                      @endif
                                      <div class="pt-3 mt-1 border-t border-slate-200 flex justify-between items-center">
                                          <span class="font-black text-slate-800 text-xs uppercase tracking-wider">TỔNG THANH TOÁN</span>
                                          <span class="text-base font-black text-brand-500">{{ number_format((float) ($o->tong_thanh_toan ?? 0), 0, ',', '.') }}₫</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                    </div>

                    {{-- Modal review --}}
                    @if(($o->review_action ?? 'none') !== 'none')
                        <div x-show="reviewModalOpen"
                             x-transition.opacity
                             x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8 bg-slate-950/50 backdrop-blur-sm"
                             @keydown.escape.window="reviewModalOpen = false"
                        >
                            <div class="absolute inset-0" @click="reviewModalOpen = false"></div>
                            <div class="relative w-full max-w-4xl max-h-[90vh] overflow-hidden bg-white rounded-3xl shadow-2xl border border-slate-200">
                                <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100">
                                    <div>
                                        <h3 class="text-lg font-black text-slate-850 uppercase tracking-tight">Đánh giá sản phẩm</h3>
                                        <p class="text-xs text-slate-400 font-bold mt-1">Đơn hàng #{{ $o->ma_don_hang }}</p>
                                    </div>
                                    <button type="button"
                                            @click="reviewModalOpen = false"
                                            class="w-9 h-9 rounded-xl border border-slate-200 text-slate-400 hover:text-slate-800 hover:bg-slate-50 transition-all flex items-center justify-center cursor-pointer">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <div class="flex flex-col max-h-[calc(90vh-82px)]">
                                    <div class="overflow-y-auto px-6 py-5 space-y-4">
                                        @if ($errors->has('review'))
                                            <div class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-600">
                                                {{ $errors->first('review') }}
                                            </div>
                                        @endif

                                        @foreach($items as $product)
                                            @php
                                                $variant = $product->variant;
                                                $productDetailUrl = $variant?->ma_san_pham ? route('viewProductDetail', $variant->ma_san_pham) : null;
                                            @endphp
                                            <form method="POST"
                                                  action="{{ route('reviews.store') }}"
                                                  enctype="multipart/form-data"
                                                  data-review-form
                                                  data-order-item-id="{{ $product->ma_chi_tiet_don_hang }}"
                                                  data-store-action="{{ route('reviews.store') }}"
                                                  data-review-expired="{{ ($o->review_is_expired ?? false) ? '1' : '0' }}"
                                                  class="rounded-2xl border border-slate-200 bg-slate-50/40 p-4">
                                                @csrf
                                                <input type="hidden" name="_method" value="PUT" data-review-method disabled>
                                                <input type="hidden" name="ma_don_hang" value="{{ $o->ma_don_hang }}">
                                                <input type="hidden" name="ma_chi_tiet_don_hang" value="{{ $product->ma_chi_tiet_don_hang }}">

                                                <div class="flex gap-4">
                                                    <a href="{{ $productDetailUrl ?? '#' }}"
                                                       class="w-16 h-16 bg-white border border-slate-200 p-1.5 rounded-xl shrink-0 flex items-center justify-center transition-all {{ $productDetailUrl ? 'hover:border-brand-500 hover:bg-orange-50/30' : 'pointer-events-none' }}">
                                                        <img src="{{ $variant->link_anh_bien_the ?? 'https://via.placeholder.com/150' }}"
                                                             alt="{{ $variant->ten_bien_the ?? $product->ten_san_pham }}"
                                                             class="w-full h-full object-contain">
                                                    </a>
                                                    <div class="min-w-0 flex-1">
                                                        <h4 class="text-sm font-black text-slate-850 uppercase truncate">
                                                            {{ $variant->ten_bien_the ?? $product->ten_san_pham }}
                                                        </h4>
                                                        <p class="text-xs text-slate-400 font-bold mt-1">
                                                            Số lượng: <span class="text-brand-500">{{ $product->so_luong }}</span>
                                                        </p>

                                                        <div class="mt-4 grid grid-cols-1 lg:grid-cols-[180px_1fr] gap-4">
                                                            <div>
                                                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Số sao</label>
                                                                <select name="so_sao"
                                                                        class="w-full h-11 bg-white border border-slate-200 rounded-xl px-3 text-sm font-bold text-slate-700 focus:outline-none focus:border-brand-500">
                                                                    @for($star = 5; $star >= 1; $star--)
                                                                        <option value="{{ $star }}" {{ $star === 5 ? 'selected' : '' }}>{{ $star }} sao</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Nội dung đánh giá</label>
                                                                <textarea name="noi_dung"
                                                                          rows="3"
                                                                          class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:border-brand-500 resize-none"
                                                                          placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Ảnh đánh giá</label>
                                                                <input type="file"
                                                                       name="danh_sach_hinh_anh[]"
                                                                       data-review-image-input
                                                                       multiple
                                                                       accept="image/*"
                                                                       class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900 file:px-3 file:py-2 file:text-xs file:font-bold file:uppercase file:text-white hover:file:bg-slate-700">
                                                                <div data-review-images class="hidden mt-3 flex flex-wrap gap-2"></div>
                                                                <p data-review-image-error class="hidden mt-2 text-xs font-bold text-rose-500"></p>
                                                            </div>
                                                            <div>
                                                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Video đánh giá</label>
                                                                <input type="file"
                                                                       name="video_danh_gia"
                                                                       data-review-video-input
                                                                       accept="video/*"
                                                                       class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900 file:px-3 file:py-2 file:text-xs file:font-bold file:uppercase file:text-white hover:file:bg-slate-700">
                                                                <div data-review-video class="hidden mt-3"></div>
                                                                <p data-review-video-error class="hidden mt-2 text-xs font-bold text-rose-500"></p>
                                                            </div>
                                                        </div>

                                                        <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 cursor-pointer select-none">
                                                                <input type="checkbox"
                                                                       name="is_anonymous"
                                                                       value="1"
                                                                       class="w-4 h-4 rounded border-slate-300 text-brand-500 focus:ring-brand-500">
                                                                <span>Đánh giá ẩn danh</span>
                                                            </label>
                                                            <button type="submit"
                                                                    data-review-submit
                                                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-black text-xs uppercase tracking-wider transition-all cursor-pointer">
                                                                <i data-lucide="send" class="w-4 h-4"></i>
                                                                <span data-review-submit-text>Xác nhận đánh giá</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @endforeach
                                    </div>

                                    <div class="flex flex-col sm:flex-row justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-white">
                                        <button type="button"
                                                @click="reviewModalOpen = false"
                                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 font-black text-xs uppercase tracking-wider transition-all cursor-pointer">
                                            Hủy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @endforeach
            @endisset
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    const MAX_REVIEW_IMAGES = 5;
    const MEDIA_TILE_CLASS = 'relative w-16 h-16 overflow-hidden rounded-lg border border-emerald-100 bg-white';
    const ACTIVE_SUBMIT_CLASS = ['bg-brand-500', 'hover:bg-brand-600', 'cursor-pointer'];
    const DISABLED_SUBMIT_CLASS = ['opacity-60', 'cursor-not-allowed', 'bg-slate-400', 'hover:bg-slate-400'];
    const selectedReviewImageFiles = new WeakMap();
    const selectedReviewVideoFiles = new WeakMap();

    window.loadOrderReviews = async function(maDonHang, orderRoot) {
        if (!orderRoot) {
            return;
        }

        const forms = orderRoot.querySelectorAll('[data-review-form]');
        forms.forEach((form) => setReviewFormLoading(form, true));

        try {
            const response = await fetch(`/orders/${encodeURIComponent(maDonHang)}/reviews`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Không tải được dữ liệu đánh giá');
            }

            const reviews = await response.json();

            forms.forEach((form) => {
                const review = reviews[form.dataset.orderItemId] || null;
                fillReviewForm(form, review);
            });

        } catch (error) {
            console.error(error);
            forms.forEach((form) => setReviewFormLoading(form, false));
        } finally {
            refreshIcons();
        }
    };

    function setReviewFormLoading(form, isLoading) {
        const isExpired = form.dataset.reviewExpired === '1';

        setSubmitState(form, {
            disabled: isLoading,
            hidden: isExpired,
            text: isLoading ? 'Đang tải đánh giá' : 'Xác nhận đánh giá',
        });
    }

    function fillReviewForm(form, review) {
        const imagesBox = form.querySelector('[data-review-images]');
        const videoBox = form.querySelector('[data-review-video]');
        const isExpired = form.dataset.reviewExpired === '1';

        resetReviewDeleteInputs(form);
        resetSelectedReviewMedia(form);
        resetReviewMedia(imagesBox, videoBox);

        if (!review) {
            resetReviewFormAction(form);
            setReviewFormEditable(form, !isExpired);
            setSubmitState(form, {
                disabled: isExpired,
                hidden: isExpired,
                text: 'Xác nhận đánh giá',
            });
            return;
        }

        fillReviewFields(form, review);

        renderReviewImages(imagesBox, review.danh_sach_anh || []);
        renderReviewVideo(videoBox, review.video || null);

        if (!isExpired && canEditReview(review)) {
            setReviewFormUpdateAction(form, review);
            setReviewFormEditable(form, true);
            setSubmitState(form, { disabled: false, hidden: false, text: 'Sửa đánh giá' });
            return;
        }

        resetReviewFormAction(form);
        setReviewFormEditable(form, false);
        setSubmitState(form, {
            disabled: true,
            hidden: isExpired,
            text: 'Đã đánh giá',
        });
    }

    function fillReviewFields(form, review) {
        const rating = form.querySelector('[name="so_sao"]');
        const content = form.querySelector('[name="noi_dung"]');
        const anonymous = form.querySelector('[name="is_anonymous"]');

        if (rating) rating.value = review.so_sao || 5;
        if (content) content.value = review.noi_dung || '';
        if (anonymous) anonymous.checked = Boolean(review.is_anonymous);
    }

    function setSubmitState(form, { disabled, hidden = false, text }) {
        const submit = form.querySelector('[data-review-submit]');
        const submitText = form.querySelector('[data-review-submit-text]');

        if (!submit) return;

        submit.disabled = disabled;
        submit.classList.toggle('hidden', hidden);
        toggleClasses(submit, DISABLED_SUBMIT_CLASS, disabled);
        toggleClasses(submit, ACTIVE_SUBMIT_CLASS, !disabled && !hidden);

        if (submitText) submitText.textContent = text;
    }

    function toggleClasses(element, classes, enabled) {
        classes.forEach((className) => element.classList.toggle(className, enabled));
    }

    function setReviewFormEditable(form, editable) {
        form.querySelectorAll('input, textarea, select').forEach((field) => {
            if (field.matches('[name="_token"], [name="ma_don_hang"], [name="ma_chi_tiet_don_hang"], [data-review-method]')) {
                return;
            }

            field.disabled = !editable;
        });

        form.querySelectorAll('[data-remove-media]').forEach((button) => {
            button.disabled = !editable;
            button.classList.toggle('hidden', !editable);
        });
    }

    function canEditReview(review) {
        return Boolean(review.can_update);
    }

    function setReviewFormUpdateAction(form, review) {
        const methodInput = form.querySelector('[data-review-method]');
        const reviewId = review.id || review.ma_danh_gia;

        if (reviewId) {
            form.action = `/reviews/${encodeURIComponent(reviewId)}`;
        }

        if (methodInput) {
            methodInput.disabled = false;
        }
    }

    function resetReviewFormAction(form) {
        const methodInput = form.querySelector('[data-review-method]');

        form.action = form.dataset.storeAction;

        if (methodInput) {
            methodInput.disabled = true;
        }
    }

    function resetReviewMedia(imagesBox, videoBox) {
        if (imagesBox) {
            imagesBox.innerHTML = '';
            imagesBox.classList.add('hidden');
        }

        if (videoBox) {
            videoBox.innerHTML = '';
            videoBox.classList.add('hidden');
        }
    }

    function resetReviewDeleteInputs(form) {
        form.querySelectorAll('input[name="xoa_hinh_anh_public_ids[]"], input[name="xoa_video_public_id"]').forEach((input) => {
            input.remove();
        });
    }

    function resetSelectedReviewMedia(form) {
        const imageInput = form.querySelector('[data-review-image-input]');
        const videoInput = form.querySelector('[data-review-video-input]');

        if (imageInput) {
            selectedReviewImageFiles.delete(imageInput);
            imageInput.value = '';
        }

        if (videoInput) {
            selectedReviewVideoFiles.delete(videoInput);
            videoInput.value = '';
        }
    }

    function renderReviewImages(container, images) {
        if (!container || images.length === 0) return;

        container.innerHTML = '';

        images.forEach((image) => {
            if (!image.url) return;

            const item = createImagePreviewItem(image.url, 'Ảnh đánh giá');
            item.querySelector('[data-remove-media]').addEventListener('click', () => {
                if (image.public_id) {
                    appendHiddenInput(item.closest('form'), 'xoa_hinh_anh_public_ids[]', image.public_id);
                }

                item.remove();
                if (container.children.length === 0) {
                    container.classList.add('hidden');
                }

                const imageInput = container.parentElement?.querySelector('[data-review-image-input]');
                if (imageInput) {
                    hideMediaInputError(imageInput, 'image');
                }
            });

            container.appendChild(item);
        });

        container.classList.remove('hidden');
    }

    function renderReviewVideo(container, video) {
        if (!container || !video?.url) return;

        const thumbnailUrl = getCloudinaryVideoThumbnailUrl(video.url);
        container.innerHTML = '';

        const item = createVideoPreviewItem(video.url, thumbnailUrl);

        item.querySelector('[data-remove-media]').addEventListener('click', () => {
            appendHiddenInput(item.closest('form'), 'xoa_video_public_id', video.public_id || '');

            item.remove();
            container.classList.add('hidden');

            const videoInput = container.parentElement?.querySelector('[data-review-video-input]');
            if (videoInput) {
                hideMediaInputError(videoInput, 'video');
            }
        });

        container.appendChild(item);
        container.classList.remove('hidden');
    }

    function createMediaPreviewItem() {
        const item = document.createElement('div');
        item.className = MEDIA_TILE_CLASS;

        const button = document.createElement('button');
        button.type = 'button';
        button.dataset.removeMedia = 'true';
        button.className = 'absolute right-1 top-1 z-10 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-white shadow-sm hover:bg-rose-600';
        button.innerHTML = '<i data-lucide="x" class="w-3 h-3"></i>';

        item.appendChild(button);
        return item;
    }

    function createMediaLink(url, className = 'block w-full h-full') {
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        link.className = className;

        return link;
    }

    function createImagePreviewItem(url, alt) {
        const item = createMediaPreviewItem();
        const link = createMediaLink(url);
        const img = document.createElement('img');
        img.src = url;
        img.alt = alt;
        img.className = 'w-full h-full object-cover';

        link.appendChild(img);
        item.appendChild(link);

        return item;
    }

    function createVideoPreviewItem(url, thumbnailUrl) {
        const item = createMediaPreviewItem();
        const link = createMediaLink(url, 'relative block w-full h-full');
        const img = document.createElement('img');
        img.src = thumbnailUrl;
        img.alt = 'Video đánh giá';
        img.className = 'w-full h-full object-cover';

        link.appendChild(img);
        link.appendChild(createPlayOverlay());
        item.appendChild(link);

        return item;
    }

    function createPlayOverlay() {
        const overlay = document.createElement('span');
        overlay.className = 'absolute inset-0 flex items-center justify-center bg-black/25 text-white';
        overlay.innerHTML = '<i data-lucide="play" class="w-5 h-5 fill-current"></i>';

        return overlay;
    }

    function renderSelectedImages(input) {
        const container = input.closest('div')?.querySelector('[data-review-images]');
        if (!container) return;

        container.querySelectorAll('[data-new-media]').forEach((item) => item.remove());
        const files = selectedReviewImageFiles.get(input) || Array.from(input.files || []);

        if (files.length === 0 && container.children.length === 0) {
            container.classList.add('hidden');
            return;
        }

        files.forEach((file, index) => {
            const objectUrl = URL.createObjectURL(file);
            const item = createImagePreviewItem(objectUrl, file.name);
            item.dataset.newMedia = 'true';

            item.querySelector('[data-remove-media]').addEventListener('click', () => {
                removeSelectedImageFile(input, index);
                renderSelectedImages(input);
            });

            container.appendChild(item);
        });

        container.classList.remove('hidden');
        refreshIcons();
    }

    function appendSelectedImageFiles(input) {
        const currentFiles = selectedReviewImageFiles.get(input) || [];
        const pickedFiles = Array.from(input.files || []);
        const existingImageCount = getExistingReviewImageCount(input);
        const availableSlots = Math.max(0, MAX_REVIEW_IMAGES - existingImageCount - currentFiles.length);

        if (pickedFiles.length > availableSlots) {
            showMediaInputError(input, 'image', `Mỗi đánh giá tối đa ${MAX_REVIEW_IMAGES} hình.`);
        } else {
            hideMediaInputError(input, 'image');
        }

        const mergedFiles = [...currentFiles, ...pickedFiles.slice(0, availableSlots)];

        selectedReviewImageFiles.set(input, mergedFiles);
        syncInputFiles(input, mergedFiles);
    }

    function removeSelectedImageFile(input, removeIndex) {
        const files = selectedReviewImageFiles.get(input) || Array.from(input.files || []);
        const keptFiles = files.filter((_, index) => index !== removeIndex);

        selectedReviewImageFiles.set(input, keptFiles);
        syncInputFiles(input, keptFiles);
        hideMediaInputError(input, 'image');
    }

    function syncInputFiles(input, files) {
        const dataTransfer = new DataTransfer();

        files.forEach((file) => {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    }

    function getExistingReviewImageCount(input) {
        const container = input.closest('div')?.querySelector('[data-review-images]');

        if (!container) return 0;

        return container.querySelectorAll(':scope > div:not([data-new-media])').length;
    }

    function renderSelectedVideo(input) {
        const container = input.closest('div')?.querySelector('[data-review-video]');
        if (!container) return;

        container.innerHTML = '';
        const file = selectedReviewVideoFiles.get(input) || input.files?.[0];

        if (!file) {
            container.classList.add('hidden');
            return;
        }

        const item = createMediaPreviewItem();
        item.dataset.newMedia = 'true';
        const objectUrl = URL.createObjectURL(file);

        const video = document.createElement('video');
        video.src = objectUrl;
        video.muted = true;
        video.preload = 'metadata';
        video.className = 'w-full h-full object-cover bg-slate-900';

        const link = createMediaLink(objectUrl, 'relative block w-full h-full');
        link.appendChild(video);
        link.appendChild(createPlayOverlay());
        item.appendChild(link);

        item.querySelector('[data-remove-media]').addEventListener('click', () => {
            selectedReviewVideoFiles.delete(input);
            input.value = '';
            hideMediaInputError(input, 'video');
            renderSelectedVideo(input);
        });

        container.appendChild(item);
        container.classList.remove('hidden');
        refreshIcons();
    }

    function setSelectedVideoFile(input) {
        const pickedFile = input.files?.[0];
        if (!pickedFile) {
            selectedReviewVideoFiles.delete(input);
            hideMediaInputError(input, 'video');
            return;
        }

        if (selectedReviewVideoFiles.has(input)) {
            syncInputFiles(input, [selectedReviewVideoFiles.get(input)]);
            showMediaInputError(input, 'video', 'Mỗi đánh giá tối đa 1 video.');
            return;
        }

        if (hasExistingReviewVideo(input)) {
            input.value = '';
            showMediaInputError(input, 'video', 'Mỗi đánh giá tối đa 1 video.');
            return;
        }

        selectedReviewVideoFiles.set(input, pickedFile);
        syncInputFiles(input, [pickedFile]);
        hideMediaInputError(input, 'video');
    }

    function hasExistingReviewVideo(input) {
        const existingVideo = input.closest('div')?.querySelector('[data-review-video]');

        return Boolean(existingVideo?.querySelector(':scope > div:not([data-new-media])'));
    }

    function appendHiddenInput(form, name, value) {
        if (!form) return;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    function showMediaInputError(input, type, message) {
        const error = getMediaInputError(input, type);

        if (!error) return;

        error.textContent = message;
        error.classList.remove('hidden');
    }

    function hideMediaInputError(input, type) {
        const error = getMediaInputError(input, type);

        if (!error) return;

        error.textContent = '';
        error.classList.add('hidden');
    }

    function getMediaInputError(input, type) {
        const selector = type === 'image' ? '[data-review-image-error]' : '[data-review-video-error]';

        return input.closest('div')?.querySelector(selector);
    }

    function getCloudinaryVideoThumbnailUrl(videoUrl) {
        return String(videoUrl)
            .replace('/video/upload/', '/video/upload/so_0,w_160,h_160,c_fill/')
            .replace(/\.[^/.?]+(\?.*)?$/, '.jpg$1');
    }

    function refreshIcons() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function showReviewSubmitLoading(form) {
        const submit = form.querySelector('[data-review-submit]');

        if (submit) {
            submit.disabled = true;
        }

        if (typeof Swal === 'undefined') {
            return;
        }

        Swal.fire({
            title: 'Đang lưu đánh giá',
            text: 'Vui lòng chờ trong giây lát.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            background: '#ffffff',
            color: '#334155',
            didOpen: () => {
                Swal.showLoading();
            },
        });
    }

    async function confirmReviewSubmit(form) {
        const isUpdate = !form.querySelector('[data-review-method]')?.disabled;
        const title = isUpdate ? 'Xác nhận sửa đánh giá' : 'Xác nhận đánh giá';
        const text = isUpdate
            ? 'Bạn chỉ được sửa đánh giá 1 lần. Sau khi xác nhận, đánh giá này sẽ không thể chỉnh sửa tiếp.'
            : 'Bạn chắc chắn muốn gửi đánh giá này?';

        if (typeof Swal === 'undefined') {
            return window.confirm(text);
        }

        const result = await Swal.fire({
            title,
            text,
            icon: isUpdate ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#ff4f00',
            cancelButtonColor: '#64748b',
            background: '#ffffff',
            color: '#334155',
        });

        return result.isConfirmed;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-review-form]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                if (form.dataset.confirmedSubmit === '1') {
                    delete form.dataset.confirmedSubmit;
                    showReviewSubmitLoading(form);
                    return;
                }

                event.preventDefault();

                if (!form.reportValidity()) {
                    return;
                }

                const confirmed = await confirmReviewSubmit(form);

                if (!confirmed) {
                    return;
                }

                form.dataset.confirmedSubmit = '1';
                form.requestSubmit();
            });
        });

        document.querySelectorAll('[data-review-image-input]').forEach((input) => {
            input.addEventListener('change', () => {
                appendSelectedImageFiles(input);
                renderSelectedImages(input);
            });
        });

        document.querySelectorAll('[data-review-video-input]').forEach((input) => {
            input.addEventListener('change', () => {
                setSelectedVideoFile(input);
                renderSelectedVideo(input);
            });
        });

        refreshIcons();
    });
</script>
@endsection
