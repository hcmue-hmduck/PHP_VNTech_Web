@extends('layouts.app')
@section('title', 'Giỏ hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
@php
    $itemsForJs = $cartItems->map(function($item) {
        $variant = $item->variant;
        $ma_flash_sales = $variant->flash_sale_info?->ma_flash_sales;
        $price = $ma_flash_sales ? $variant->flash_sale_info->gia_flash_sale : $variant->gia_ban;
        return [
            'id' => $item->id,
            'ma_bien_the' => $item->ma_bien_the,
            'name' => $variant->ten_bien_the,
            'price' => $price,
            'quantity' => $item->so_luong,
            'image' => $variant->link_anh_bien_the,
            'checked' => true,
            'ma_flash_sales' => $ma_flash_sales,
            'original_price' => $variant->gia_ban
        ];
    })->toArray();
@endphp

<form method="POST" action="{{ route('payment.prepare') }}" class="min-h-screen bg-[#121414] font-sans selection:bg-lime-400 selection:text-black pt-32 pb-24 px-6 max-w-7xl mx-auto"
        x-data='cartComponent(@json($itemsForJs), {
          updateUrl: "{{ route("cart.updateQuantity") }}",
          removeUrl: "{{ route("cart.removeItem") }}",
          csrfToken: "{{ csrf_token() }}"
      })'>
     @csrf
      <input type="hidden" name="cart_json" x-bind:value="JSON.stringify(cartItems.filter(i => i.checked).map(item => ({
          ma_san_pham: item.id,
          ma_bien_the: item.ma_bien_the,
          ma_flash_sales: item.ma_flash_sales,
          ten_bien_the: item.name,
          gia_ban: item.price,
          so_luong: item.quantity,
          link_anh_dai_dien: item.image
      })))">
    
    <!-- Tiêu đề căn giữa, nằm ngoài grid -->
    <h1 class="font-['Space_Grotesk'] text-4xl md:text-5xl font-bold mb-10 text-white tracking-tight uppercase text-center">
        GIỎ HÀNG CỦA TÔI
    </h1>

    <!-- Grid 2 cột: danh sách + tóm tắt -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Cart List -->
        <div class="lg:col-span-7 w-full">
            <div class="flex flex-col gap-4">
                <template x-for="(item, index) in cartItems" :key="item.id">
                    <div class="bg-white/5 border border-white/10 p-4 md:p-6 rounded-xl flex flex-col md:flex-row items-center gap-6 group hover:border-lime-400/20 transition-all duration-300">
                        <div class="flex-shrink-0 flex items-center justify-center">
                            <input type="checkbox" x-model="item.checked" class="w-5 h-5 text-lime-400 rounded" />
                        </div>
                        <div class="w-full md:w-32 h-48 md:h-32 rounded-lg overflow-hidden bg-gray-900 border border-white/5">
                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        
                        <div class="flex-grow text-center md:text-left">
                            <h3 class="font-['Space_Grotesk'] text-lg md:text-xl font-bold text-white mb-1" x-text="item.name"></h3>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                <p class="text-lime-400 font-['Space_Grotesk'] font-bold text-lg drop-shadow-[0_0_8px_rgba(0,255,102,0.4)] flex items-center gap-1">
                                    <template x-if="item.ma_flash_sales">
                                        <span class="inline-flex items-center gap-0.5 text-lime-400 animate-pulse font-black text-xs uppercase tracking-wider mr-1 bg-lime-950/80 px-1.5 py-0.5 rounded border border-lime-500/20">
                                            <svg class="w-3 h-3 text-lime-400 fill-lime-400" viewBox="0 0 24 24" fill="currentColor">
                                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                            </svg>
                                            FLASH SALE
                                        </span>
                                    </template>
                                    <span x-text="formatCurrency(item.price)"></span>
                                </p>
                                <template x-if="item.ma_flash_sales">
                                    <span class="text-gray-500 line-through text-sm font-semibold opacity-60" x-text="formatCurrency(item.original_price)"></span>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <div class="flex items-center bg-black/40 border border-white/10 rounded-full h-11 px-1 overflow-hidden">
                                <button type="button" @click="updateQuantity(item.id, -1)" class="p-2 text-gray-400 hover:text-white hover:bg-white/5 rounded-full transition-all">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <span class="w-10 text-center font-bold text-white" x-text="item.quantity"></span>
                                <button type="button" @click="updateQuantity(item.id, 1)" class="p-2 text-gray-400 hover:text-white hover:bg-white/5 rounded-full transition-all">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                            
                            <button type="button" @click="removeItem(item.id)" class="p-3 text-gray-500 hover:text-red-400 transition-colors bg-white/5 hover:bg-red-400/10 rounded-full">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="cartItems.length === 0" class="flex flex-col items-center justify-center py-20 text-gray-500">
                    <i data-lucide="shopping-cart" class="w-16 h-16 mb-4 opacity-20"></i>
                    <p class="text-lg uppercase tracking-widest font-bold">Giỏ hàng của bạn đang trống</p>
                </div>
            </div>

            <a href="/" class="mt-10 inline-flex items-center gap-2 text-lime-400 font-medium hover:gap-3 transition-all uppercase tracking-widest text-xs">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Tiếp tục mua sắm
            </a>
        </div>

        <!-- Sidebar Summary -->
        <aside class="lg:col-span-5 w-full space-y-4">
            <div class="bg-white/5 border border-white/10 p-4 md:p-6 rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                <h2 class="font-['Space_Grotesk'] text-2xl font-bold text-white mb-6 tracking-tight uppercase">Tóm tắt đơn hàng</h2>
                
                <div class="space-y-4 mb-8">
                    <template x-for="item in cartItems.filter(i => i.checked)" :key="'summary-' + item.id">
                        <div class="flex justify-between items-center text-sm font-light">
                            <span class="text-gray-400 uppercase tracking-wider line-clamp-1" x-text="item.name"></span>
                            <span class="text-white whitespace-nowrap" x-text="formatCurrency(item.price * item.quantity)"></span>
                        </div>
                    </template>
                    <div class="pt-4 border-t border-white/5 flex justify-between items-center text-sm">
                        <span class="text-gray-400 uppercase tracking-widest font-bold">Phí vận chuyển</span>
                        <span class="text-lime-400 font-bold uppercase tracking-widest">Miễn phí</span>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 mb-8">
                    <div class="flex justify-between items-end">
                        <span class="text-white font-['Space_Grotesk'] font-medium uppercase tracking-wider">Tổng cộng</span>
                        <div class="text-right">
                            <span class="block text-3xl font-['Space_Grotesk'] font-bold text-lime-400 drop-shadow-[0_0_15px_rgba(0,255,102,0.6)]" 
                                  x-text="formatCurrency(cartItems.filter(i => i.checked).reduce((s, i) => s + (i.price * i.quantity), 0))"></span>
                        </div>
                    </div>
                </div>
                <button type="submit" :disabled="cartItems.filter(i => i.checked).length === 0" class="w-full h-16 bg-lime-400 text-black rounded-full font-['Space_Grotesk'] font-bold text-lg flex items-center justify-center gap-3 shadow-[0_0_30px_rgba(0,255,102,0.3)] hover:shadow-[0_0_50px_rgba(0,255,102,0.5)] hover:scale-[1.02] active:scale-[0.98] transition-all uppercase disabled:opacity-40 disabled:cursor-not-allowed">
                    <i data-lucide="lock" class="w-5 h-5"></i>
                    THANH TOÁN NGAY
                </button>
            </div>

            <div class="mt-6 bg-white/5 border border-white/10 p-4 rounded-xl flex items-center gap-4">
                <div class="w-12 h-12 bg-lime-400/10 rounded-full flex items-center justify-center text-lime-400">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest">Bảo hành chính hãng</h4>
                    <p class="text-[10px] text-gray-500 uppercase tracking-tighter">Hỗ trợ kỹ thuật 24/7 từ VNTECH Lab</p>
                </div>
            </div>
        </aside>
    </div>
</div>

<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>
@endsection

@section('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script>
    // Khởi tạo icons cho các phần tử Alpine.js render sau
    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
    });
</script>
@endsection
