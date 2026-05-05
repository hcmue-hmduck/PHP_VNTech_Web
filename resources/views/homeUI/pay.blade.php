@extends('layouts.app')

@section('title', 'Thanh toán | VNTech Protocol')

@push('styles')
<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .neo-label {
        display: block;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: rgba(255, 255, 255, 0.4);
        margin-bottom: 0.5rem;
    }

    .neo-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem;
        color: white;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .neo-input:focus {
        border-color: #a3e635; /* lime-400 */
        background: rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 20px rgba(163, 230, 53, 0.15);
    }

    .primary-glow {
        text-shadow: 0 0 15px rgba(163, 230, 53, 0.5);
    }
</style>
@endpush

@section('content')
<main class="pt-32 pb-24 px-6 max-w-[1440px] mx-auto" x-data="{ paymentMethod: 'card' }">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Page Header -->
        <div class="lg:col-span-12 mb-8">
            <h1 class="font-['Space_Grotesk'] text-5xl md:text-6xl font-bold uppercase tracking-tight text-white">
                Thanh Toán
            </h1>
            <p class="text-lime-400/60 mt-2 font-['Space_Grotesk'] tracking-[0.3em] uppercase text-xs font-bold">
                Phòng Giao Dịch Protocol v2.4
            </p>
        </div>

        <!-- Left Column: Form Sections -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Shipping Info -->
            <section class="glass-panel p-8 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-lime-400"></div>
                <div class="flex items-center gap-3 mb-8">
                    <i data-lucide="truck" class="text-lime-400 w-6 h-6"></i>
                    <h2 class="font-['Space_Grotesk'] text-2xl font-semibold text-white uppercase tracking-wider">Thông Tin Giao Hàng</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="neo-label">Họ và Tên</label>
                        <input class="neo-input" placeholder="NGUYEN VAN A" type="text">
                    </div>
                    <div class="space-y-1">
                        <label class="neo-label">Số Điện Thoại</label>
                        <input class="neo-input" placeholder="+84 000 000 000" type="text">
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label class="neo-label">Địa Chỉ Nhận Hàng</label>
                        <input class="neo-input" placeholder="Số nhà, Tên đường, Quận/Huyện, Thành phố" type="text">
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label class="neo-label">Ghi Chú Đơn Hàng</label>
                        <textarea class="neo-input min-h-[100px] resize-none" placeholder="Yêu cầu cấu hình thêm hoặc lưu ý cho shipper..." rows="3"></textarea>
                    </div>
                </div>
            </section>

            <!-- Payment Method -->
            <section class="glass-panel p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-lime-400"></div>
                <div class="flex items-center gap-3 mb-8">
                    <i data-lucide="wallet" class="text-lime-400 w-6 h-6"></i>
                    <h2 class="font-['Space_Grotesk'] text-2xl font-semibold text-white uppercase tracking-wider">Phương Thức Thanh Toán</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Card -->
                    <button @click="paymentMethod = 'card'"
                            :class="paymentMethod === 'card' ? 'border-lime-400 bg-lime-400/5 text-lime-400' : 'border-white/10 text-white/60 hover:border-white/30'"
                            class="flex flex-col items-center gap-4 p-6 border transition-all cursor-pointer group">
                        <i data-lucide="credit-card" :class="paymentMethod === 'card' ? 'animate-pulse' : ''" class="w-8 h-8"></i>
                        <span class="text-[10px] uppercase font-bold tracking-[0.2em]">Thẻ Tín Dụng</span>
                    </button>
                    <!-- Bank -->
                    <button @click="paymentMethod = 'bank'"
                            :class="paymentMethod === 'bank' ? 'border-lime-400 bg-lime-400/5 text-lime-400' : 'border-white/10 text-white/60 hover:border-white/30'"
                            class="flex flex-col items-center gap-4 p-6 border transition-all cursor-pointer group">
                        <i data-lucide="banknote" :class="paymentMethod === 'bank' ? 'animate-pulse' : ''" class="w-8 h-8"></i>
                        <span class="text-[10px] uppercase font-bold tracking-[0.2em]">Chuyển Khoản</span>
                    </button>
                    <!-- Digital Wallet -->
                    <button @click="paymentMethod = 'wallet'"
                            :class="paymentMethod === 'wallet' ? 'border-lime-400 bg-lime-400/5 text-lime-400' : 'border-white/10 text-white/60 hover:border-white/30'"
                            class="flex flex-col items-center gap-4 p-6 border transition-all cursor-pointer group">
                        <i data-lucide="wallet-minimal" :class="paymentMethod === 'wallet' ? 'animate-pulse' : ''" class="w-8 h-8"></i>
                        <span class="text-[10px] uppercase font-bold tracking-[0.2em]">Ví Điện Tử</span>
                    </button>
                </div>
            </section>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-4">
            <div class="sticky top-28 space-y-6">
                <section class="glass-panel p-8 border border-lime-400/20 shadow-[0_0_50px_rgba(0,255,102,0.05)]">
                    <h2 class="font-['Space_Grotesk'] text-xl font-bold text-white uppercase tracking-wider mb-8 pb-4 border-b border-white/5 flex justify-between items-center">
                        Tóm Tắt Đơn Hàng
                        <span class="text-[10px] bg-lime-400/10 text-lime-400 px-2 py-1">ID: #9942</span>
                    </h2>
                    
                    <div class="space-y-6 mb-8">
                        @if(isset($cartItems) && count($cartItems) > 0)
                            @foreach($cartItems as $item)
                            <div class="flex gap-4">
                                <div class="w-20 h-20 bg-gray-900 border border-white/10 flex-shrink-0 relative overflow-hidden group">
                                    <img src="{{ $item->product->link_anh_dai_dien ?? 'https://via.placeholder.com/200' }}" 
                                         alt="Product" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </div>
                                <div class="flex flex-col justify-between py-1 flex-grow">
                                    <div>
                                        <h3 class="text-[11px] font-bold text-white uppercase tracking-tight line-clamp-1">
                                            {{ $item->product->ten_san_pham }}
                                        </h3>
                                        <p class="text-[9px] text-white/40 mt-1 uppercase tracking-widest font-['Space_Grotesk']">
                                            SL: {{ $item->so_luong }} • {{ number_format($item->product->gia_ban, 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                    <p class="text-lime-400 font-bold text-xs">
                                        {{ number_format($item->product->gia_ban * $item->so_luong, 0, ',', '.') }}đ
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="flex gap-4">
                                <div class="w-24 h-24 bg-gray-900 border border-white/10 flex-shrink-0 relative overflow-hidden group">
                                    <img src="https://images.unsplash.com/photo-1544006659-f0b21f04cb1d?q=80&w=2670&auto=format&fit=crop" 
                                         alt="Premium Laptop" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </div>
                                <div class="flex flex-col justify-between py-1">
                                    <div>
                                        <h3 class="text-xs font-bold text-white uppercase tracking-tight">Laptop Acer Swift X 14</h3>
                                        <p class="text-[10px] text-white/40 mt-1 uppercase tracking-widest font-['Space_Grotesk']">RTX 4050 • 16GB RAM</p>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <p class="text-[10px] text-white/40">Số lượng: 1</p>
                                        <p class="text-lime-400 font-bold">40.990.000đ</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3 pt-6 border-t border-white/5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/40 uppercase tracking-widest text-[10px]">Tạm tính</span>
                            <span class="text-white font-['Space_Grotesk']">{{ number_format($cart->tong_tien ?? 40990000, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/40 uppercase tracking-widest text-[10px]">Phí vận hành</span>
                            <span class="text-lime-400 uppercase font-bold text-[10px]">FREE_OPS</span>
                        </div>
                        <div class="flex justify-between items-end pt-8 mt-4 border-t border-white/10">
                            <span class="text-white font-bold uppercase tracking-widest text-xs">Tổng cộng</span>
                            <span class="text-4xl font-black text-lime-400 font-['Space_Grotesk'] tracking-tighter primary-glow">
                                {{ number_format($cart->tong_tien ?? 40990000, 0, ',', '.') }}đ
                            </span>
                        </div>
                    </div>

                    <button class="w-full mt-10 py-5 bg-lime-400 text-black font-black uppercase tracking-[0.2em] text-xs shadow-[0_0_25px_rgba(0,255,102,0.4)] hover:shadow-[0_0_40px_rgba(0,255,102,0.6)] hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-3 cursor-pointer group">
                        Xác nhận giao dịch
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </section>

                <div class="px-6 py-4 flex items-center gap-4 text-white/20 border border-white/5 bg-white/[0.02]">
                    <i data-lucide="shield-check" class="text-lime-400/40 w-5 h-5"></i>
                    <span class="text-[9px] uppercase tracking-[0.2em] leading-relaxed">
                        Mã hóa bảo mật chuẩn quân sự AES-256 GCM. <br>
                        Giao dịch được xác thực bởi VNTech Network.
                    </span>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    // Khởi tạo Lucide Icons
    lucide.createIcons();
</script>
@endsection
