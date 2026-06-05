@extends('layouts.app')
@section('title', 'Giỏ hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
@php
    $itemsForJs = $cartItems->map(function($item) {
        $variant = $item->variant;
        $flashSaleInfo = $variant->flash_sale_info;
        $flashSaleCampaign = $variant->flash_sale_campaign;
        $hasFlashSale = $flashSaleInfo && $flashSaleCampaign;
        $originalPrice = (int) $variant->gia_ban;
        $flashSalePrice = $hasFlashSale ? (int) $flashSaleInfo->gia_flash_sale : null;
        $price = $hasFlashSale ? $flashSalePrice : $originalPrice;
        $discountAmount = $hasFlashSale ? max(0, $originalPrice - $flashSalePrice) : 0;

        return [
            'id' => $item->id,
            'ma_bien_the' => $item->ma_bien_the,
            'name' => $variant->ten_hien_thi,
            'ten_bien_the' => $variant->ten_bien_the,
            'price' => $price,
            'quantity' => $item->so_luong,
            'image' => $variant->link_anh_bien_the,
            'checked' => true,
            'has_flash_sale' => $hasFlashSale,
            'ma_flash_sales' => $hasFlashSale ? $flashSaleInfo->ma_flash_sales : null,
            'flash_sale_name' => $hasFlashSale ? ($flashSaleCampaign->ten_flash_sales ?? 'Flash Sale') : null,
            'flash_sale_price' => $flashSalePrice,
            'original_price' => $originalPrice,
            'discount_amount' => $discountAmount,
        ];
    })->toArray();

    // Query 4 suggested products from product variants
    $cartBienTheIds = $cartItems->pluck('ma_bien_the')->toArray();
    $suggestedProducts = \App\Models\ProductVariant::with('product')
        ->whereNotIn('_id', $cartBienTheIds)
        ->limit(4)
        ->get();
    
    if ($suggestedProducts->count() < 4) {
        $suggestedProducts = \App\Models\ProductVariant::with('product')
            ->limit(4)
            ->get();
    }
@endphp

<form method="POST" action="{{ route('payment.prepare') }}" 
      class="min-h-screen font-sans selection:bg-[#ff5c00]/20 selection:text-[#ff5c00] pt-20 pb-24 px-4 sm:px-8 max-w-7xl mx-auto animate-[fadeIn_0.5s_ease-out]"
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
          ten_bien_the: item.ten_bien_the,
          ten_hien_thi: item.name,
          gia_ban: item.price,
          so_luong: item.quantity,
          link_anh_dai_dien: item.image
      })))">
    
    <!-- Main Cart Container Card -->
    <div class="bg-white border border-neutral-200/60 p-6 md:p-10 rounded-3xl shadow-sm space-y-6">
      <!-- Page Title -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 select-none pb-4 border-b border-neutral-100">
        <div>
          <h1 class="font-display font-extrabold text-3xl md:text-4xl text-neutral-900 tracking-tight">
            Giỏ hàng
          </h1>
          <p class="text-xs md:text-sm font-medium text-neutral-500 mt-1.5 flex items-center gap-1">
            <span>Đang quản lý</span>
            <strong class="text-[#ff5c00] font-bold" x-text="cartItems.length"></strong>
            <span>sản phẩm trong giỏ hàng</span>
          </p>
        </div>

        <!-- Clear All Trigger -->
        <template x-if="cartItems.length > 0">
          <button
            type="button"
            @click="clearAll()"
            class="text-xs font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 px-3.5 py-2 rounded-xl transition-all border border-red-200 self-start md:self-center shrink-0 flex items-center gap-1.5 shadow-xs bg-white"
          >
            <i data-lucide="trash-2" class="h-4 w-4"></i>
            <span>Xoá toàn bộ</span>
          </button>
        </template>
      </div>

      <!-- Empty State -->
      <div x-show="cartItems.length === 0" class="text-center py-16 md:py-24 p-8 max-w-lg mx-auto space-y-6">
        <div class="mx-auto h-16 w-16 bg-orange-50 text-[#ff5c00] rounded-full flex items-center justify-center border border-orange-100 animate-bounce">
          <i data-lucide="shopping-bag" class="h-8 w-8 text-[#ff5c00]"></i>
        </div>
        
        <div class="space-y-2">
          <h3 class="font-display font-bold text-xl text-neutral-900">Giỏ hàng rỗng!</h3>
          <p class="text-sm font-medium text-neutral-500 max-w-xs mx-auto">
            Hiện tại bạn chưa thêm bất cứ sản phẩm công nghệ tuyệt vời nào vào giỏ hàng.
          </p>
        </div>

        <a href="{{ route('home.index') }}"
           class="bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-medium text-sm px-6 py-3 rounded-xl transition-all shadow-md inline-flex items-center gap-2"
        >
          <i data-lucide="arrow-left" class="h-4 w-4"></i>
          <span>Quay lại cửa hàng</span>
        </a>
      </div>

      <!-- Two Column Layout -->
      <div x-show="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Cart Items -->
        <div class="lg:col-span-8 space-y-6">
        
        <!-- Select All Header -->
        <div class="flex items-center justify-between select-none pb-4 border-b border-neutral-100 font-semibold">
          <label class="flex items-center gap-3 cursor-pointer text-sm font-bold text-neutral-600">
            <input
              type="checkbox"
              :checked="selectAllChecked"
              @change="toggleAll()"
              class="w-5 h-5 rounded border-neutral-300 text-[#ff5c00] focus:ring-[#ff5c00] cursor-pointer accent-[#ff5c00] bg-white"
            />
            <span>Chọn tất cả (<span x-text="cartItems.length"></span> sản phẩm)</span>
          </label>

          <p class="text-xs text-neutral-500 hidden md:block">
            Giá đã chọn: <strong class="text-[#ff5c00] text-sm font-bold ml-1" x-text="formatCurrency(subtotal)"></strong>
          </p>
        </div>

        <!-- Items Loop -->
        <div class="space-y-4">
          <template x-for="(item, index) in cartItems" :key="item.id">
            <div class="relative bg-neutral-50/40 border border-neutral-200/50 rounded-2xl p-4 md:p-5 flex items-center gap-4 transition-all duration-200 hover:bg-neutral-50 hover:shadow-sm">
              <!-- Checkbox -->
              <div class="shrink-0 flex items-center">
                <input
                  type="checkbox"
                  x-model="item.checked"
                  class="w-5 h-5 rounded border-neutral-300 text-[#ff5c00] focus:ring-[#ff5c00] cursor-pointer accent-[#ff5c00] bg-white"
                />
              </div>

              <!-- Product Image -->
              <div class="w-20 h-20 md:w-24 md:h-24 bg-white rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center p-1.5 border border-neutral-200/40">
                <img
                  :alt="item.name"
                  :src="item.image"
                  class="w-full h-full object-contain hover:scale-105 duration-200"
                />
              </div>

              <!-- Product Details -->
              <div class="flex-grow flex flex-col justify-between min-w-0">
                <div class="flex justify-between items-start gap-2">
                  <div class="min-w-0">
                    <h3 class="font-display font-bold text-base md:text-lg text-neutral-900 hover:text-[#ff5c00] transition-colors leading-tight truncate" x-text="item.name"></h3>
                    <template x-if="item.has_flash_sale">
                      <div class="mt-1.5 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-lg bg-orange-50 px-2 py-1 text-[10px] font-black uppercase tracking-wider text-[#ff5c00] ring-1 ring-orange-100">
                          <i data-lucide="zap" class="h-3 w-3 fill-current"></i>
                          <span x-text="item.flash_sale_name || 'Flash Sale'"></span>
                        </span>
                        <span class="text-[11px] font-bold text-red-500" x-text="'Giảm ' + formatCurrency(item.discount_amount)"></span>
                      </div>
                    </template>
                  </div>
                  
                  <button
                    type="button"
                    @click="removeItem(item.id)"
                    class="text-neutral-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all shrink-0"
                    title="Xóa khỏi giỏ hàng"
                  >
                    <i data-lucide="trash-2" class="h-4.5 w-4.5"></i>
                  </button>
                </div>

                <!-- Quantity & Pricing -->
                <div class="flex justify-between items-end mt-4">
                  <div class="flex items-center border border-neutral-200 rounded-xl overflow-hidden bg-white select-none">
                    <button
                      type="button"
                      @click="updateQuantity(item.id, -1)"
                      class="px-3 py-1 text-neutral-600 hover:bg-neutral-100 active:bg-neutral-200 font-bold text-sm transition-colors"
                    >-</button>
                    <span class="px-3 py-1 font-display font-semibold text-sm w-9 text-center bg-white border-x border-neutral-200" x-text="item.quantity"></span>
                    <button
                      type="button"
                      @click="updateQuantity(item.id, 1)"
                      class="px-3 py-1 text-neutral-600 hover:bg-neutral-100 active:bg-neutral-200 font-bold text-sm transition-colors"
                    >+</button>
                  </div>

                  <div class="flex items-baseline justify-end gap-2 text-right">
                    <template x-if="item.has_flash_sale">
                      <p class="text-xs text-neutral-400 line-through font-sans font-medium" x-text="formatCurrency(item.original_price * item.quantity)"></p>
                    </template>
                    <p
                      class="font-display font-bold text-base md:text-lg leading-none shrink-0"
                      :class="item.has_flash_sale ? 'text-[#ff5c00]' : 'text-neutral-900'"
                      x-text="formatCurrency(item.price * item.quantity)"
                    ></p>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>

        <!-- Shipping Shortage Guidance -->
        <template x-if="!qualifiesForFreeShipping && subtotal > 0">
          <div class="bg-orange-50 border border-orange-200 p-4 rounded-2xl flex items-center gap-3.5 select-none">
            <i data-lucide="badge-alert" class="h-5 w-5 text-[#ff5c00] shrink-0"></i>
            <p class="font-sans text-xs md:text-sm text-neutral-800 font-semibold leading-relaxed">
              Mua thêm <strong class="text-[#ff5c00] underline" x-text="formatCurrency(2000000 - subtotal)"></strong> để mở khóa <strong class="font-bold">Miễn phí vận chuyển</strong>!
            </p>
          </div>
        </template>
      </div>

      <!-- Right Column: Sticky Summary -->
      <div class="lg:col-span-4 lg:sticky lg:top-24">
        <div class="bg-neutral-50/60 border border-neutral-200/60 p-6 md:p-8 rounded-3xl shadow-sm space-y-6">
          <h3 class="font-display font-extrabold text-lg text-neutral-900 tracking-tight">Tóm tắt đơn hàng</h3>
          
          <div class="space-y-3.5 pb-4 border-b border-neutral-100">
            <div class="flex justify-between items-center text-sm font-medium text-neutral-500">
              <span>Tạm tính (<span x-text="selectedItems.reduce((acc, item) => acc + item.quantity, 0)"></span> sản phẩm)</span>
              <span class="text-neutral-900 font-bold" x-text="formatCurrency(subtotal)"></span>
            </div>
            
            <div class="flex justify-between items-center text-sm font-medium text-neutral-500">
              <span>Phí vận chuyển</span>
              <span class="text-neutral-900 font-bold" x-text="shippingFee === 0 ? 'Miễn phí' : formatCurrency(shippingFee)"></span>
            </div>
          </div>

          <div class="pt-4 border-t border-neutral-100 flex justify-between items-baseline">
            <span class="text-sm font-bold text-neutral-800">Tổng cộng</span>
            <span class="text-2xl font-display font-black text-[#ff5c00]" x-text="formatCurrency(total)"></span>
          </div>

          <button
            type="submit"
            :disabled="selectedItems.length === 0"
            class="w-full bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-bold text-base py-4 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed"
          >
            <i data-lucide="arrow-right" class="h-5 w-5"></i>
            <span>TIẾN HÀNH ĐẶT HÀNG</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Suggestions (Có thể bạn quan tâm) -->
  <section class="bg-white border border-neutral-200/60 p-6 md:p-10 rounded-3xl shadow-sm mt-12 space-y-8">
    <h2 class="font-display font-extrabold text-2xl md:text-3xl text-neutral-900 select-none">
      Có thể bạn quan tâm
    </h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        @foreach($suggestedProducts as $p)
          @php
              $spec = ($p->mau_sac ? $p->mau_sac : '') . ($p->dung_luong ? ' - ' . $p->dung_luong : '');
          @endphp
          <div class="bg-white border border-neutral-200/60 rounded-2xl p-4 flex flex-col justify-between transition-all hover:shadow-lg hover:-translate-y-1">
            <div>
              <div class="aspect-square bg-neutral-50 rounded-xl mb-4 overflow-hidden flex items-center justify-center p-4 border border-neutral-200/40 relative group">
                <img
                  alt="{{ $p->ten_hien_thi }}"
                  src="{{ $p->link_anh_bien_the }}"
                  class="w-full h-full object-contain group-hover:scale-105 duration-200"
                />
              </div>

              <h4 class="font-display font-bold text-sm text-neutral-900 line-clamp-1 hover:text-[#ff5c00]" title="{{ $p->ten_hien_thi }}">
                {{ $p->ten_hien_thi }}
              </h4>
              <p class="text-xs text-neutral-500 font-medium mt-1">
                {{ $spec ?: 'Chính hãng 100%' }}
              </p>
            </div>

            <div class="mt-4 shrink-0">
              <p class="text-[#ff5c00] font-display font-extrabold text-lg">
                {{ number_format($p->gia_ban, 0, ',', '.') }}₫
              </p>
              
              <a
                href="{{ route('cart.addItem', ['ma_bien_the' => $p->_id]) }}"
                class="mt-3 w-full border border-neutral-200 hover:bg-neutral-50 text-neutral-700 font-display font-bold text-xs py-2.5 rounded-xl transition-all flex items-center justify-center gap-1.5 select-none"
              >
                <i data-lucide="shopping-cart" class="h-3.5 w-3.5"></i>
                <span>Thêm vào giỏ</span>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </section>
</form>
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
