@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân | VNTech')

@section('content')
@php
    $realUser = $user ?? Auth::user();
    $tab = request()->query('tab', 'profile');
    
    $addresses = $user_address ?? collect();
    $orders = $orders ?? collect();
@endphp

<style>
    .grid-bg {
        background-image: radial-gradient(rgba(0, 255, 102, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .text-neon-green { color: #00FF66; }
    .bg-neon-green { background-color: #00FF66; }
    .border-neon-green { border-color: #00FF66; }
    .neon-border-glow:focus {
        border-color: #00FF66 !important;
        box-shadow: 0 0 10px rgba(0, 255, 102, 0.2);
    }
</style>

<div class="grid-bg min-h-screen bg-[#0f1111]">
    <main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
        <!-- Nút quay lại trang chủ -->
        <div class="mb-6 flex justify-start animate-fadeInUp">
            <a href="{{ url('/') }}" 
               class="flex items-center gap-2 px-4 py-2 border border-white/5 bg-white/[0.02] hover:bg-neon-green/5 hover:border-neon-green/40 rounded-lg text-gray-400 hover:text-neon-green text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group shadow-sm">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1"></i>
                <span>Trang chủ</span>
            </a>
        </div>

        <!-- Tiêu đề chính -->
        <header class="mb-12 text-center animate-fadeInUp">
            <h1 class="font-space text-5xl font-bold text-gray-100 uppercase tracking-tight">
                Hồ sơ cá nhân
            </h1>
            <p class="text-gray-400 mt-2 uppercase tracking-wide text-sm">
                Quản lý thông tin tài khoản, sổ địa chỉ và theo dõi đơn hàng của bạn.
            </p>
        </header>

        <!-- Thông báo thành công / lỗi -->
        @if(session('success'))
        <div class="mb-8 max-w-3xl mx-auto p-4 rounded-lg bg-neon-green/10 border border-neon-green/30 text-neon-green text-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Layout chính -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar điều hướng bên trái -->
            <div class="lg:col-span-1 flex flex-col gap-2 h-fit bg-[#1a1c1c]/50 p-4 border border-white/5 backdrop-blur-md rounded-xl">
                <!-- User Quick Info -->
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-white/10">
                    @if(!empty($realUser->avatar_url))
                        <img src="{{ $realUser->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full border border-neon-green/30 object-cover">
                    @else
                        <div class="w-12 h-12 rounded-full bg-neon-green/10 border border-neon-green/30 flex items-center justify-center text-neon-green font-space text-xl font-bold flex-shrink-0">
                            {{ strtoupper(substr($realUser->ho_ten ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <h4 class="text-white font-bold text-sm truncate uppercase tracking-tight">{{ $realUser->ho_ten ?? 'Khách hàng' }}</h4>
                        <p class="text-gray-500 text-xs truncate">{{ $realUser->email }}</p>
                    </div>
                </div>

                <!-- Tabs Links (Chuyển trang bằng tham số query của PHP) -->
                <a href="{{ route('user.view', ['tab' => 'profile']) }}" 
                   class="w-full text-left px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-widest transition-all duration-300 flex items-center gap-3 {{ $tab === 'profile' ? 'bg-neon-green text-black shadow-[0_0_15px_rgba(0,255,102,0.2)]' : 'bg-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Thông tin cá nhân</span>
                </a>
                <a href="{{ route('user.view', ['tab' => 'change-password']) }}" 
                   class="w-full text-left px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-widest transition-all duration-300 flex items-center gap-3 {{ $tab === 'change-password' ? 'bg-neon-green text-black shadow-[0_0_15px_rgba(0,255,102,0.2)]' : 'bg-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="key-round" class="w-4 h-4"></i>
                    <span>Đổi mật khẩu</span>
                </a>
                <a href="{{ route('user.view', ['tab' => 'addresses']) }}" 
                   class="w-full text-left px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-widest transition-all duration-300 flex items-center gap-3 {{ $tab === 'addresses' ? 'bg-neon-green text-black shadow-[0_0_15px_rgba(0,255,102,0.2)]' : 'bg-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>Sổ địa chỉ</span>
                </a>
                <a href="{{ route('user.view', ['tab' => 'orders']) }}" 
                   class="w-full text-left px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-widest transition-all duration-300 flex items-center gap-3 {{ $tab === 'orders' ? 'bg-neon-green text-black shadow-[0_0_15px_rgba(0,255,102,0.2)]' : 'bg-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Lịch sử đơn hàng</span>
                </a>
            </div>

            <!-- Vùng nội dung bên phải -->
            <div class="lg:col-span-3 space-y-8">
                <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
                @if($tab === 'profile')
                <form action="#" method="POST" class="bg-[#1a1c1c]/50 border border-white/5 backdrop-blur-md rounded-xl p-8 animate-fadeInUp">
                    @csrf
                    <div class="flex flex-col gap-8">
                        <!-- Title & Header -->
                        <div class="border-b border-white/5 pb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold uppercase text-white tracking-tight flex items-center gap-3">
                                    <i data-lucide="user-cog" class="text-neon-green"></i> Thông tin cá nhân
                                </h3>
                                <p class="text-gray-500 text-xs mt-1 uppercase tracking-wider">Chi tiết tài khoản của bạn trên hệ thống</p>
                            </div>
                            <!-- Nút cập nhật -->
                            <button type="submit" class="flex items-center gap-1.5 border border-neon-green/30 hover:border-neon-green bg-neon-green/10 hover:bg-neon-green text-neon-green hover:text-black px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all duration-300 shadow-[0_0_15px_rgba(0,255,102,0.1)] hover:shadow-[0_0_15px_rgba(0,255,102,0.3)]">
                                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                <span>Cập nhật</span>
                            </button>
                        </div>

                        <!-- Layout Chi tiết Profile -->
                        <div class="flex flex-col md:flex-row gap-8 items-center w-full">
                            <!-- Cột Trái: Avatar -->
                            <div class="flex flex-col items-center gap-4 w-full md:w-1/4">
                                <div class="relative">
                                    @if(!empty($realUser->avatar_url))
                                        <img src="{{ $realUser->avatar_url }}" alt="Avatar" class="w-32 h-32 rounded-full border-2 border-neon-green/40 object-cover shadow-[0_0_20px_rgba(0,255,102,0.1)]">
                                    @else
                                        <div class="w-32 h-32 rounded-full bg-neon-green/10 border-2 border-neon-green/30 flex items-center justify-center text-neon-green font-space text-4xl font-bold shadow-[0_0_20px_rgba(0,255,102,0.1)]">
                                            {{ strtoupper(substr($realUser->ho_ten ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <!-- Nút upload ảnh nhỏ dạng camera ở góc dưới bên phải -->
                                    <div class="absolute bottom-1 right-1">
                                        <label class="w-8 h-8 rounded-full bg-[#121414] border border-white/10 hover:border-neon-green text-gray-400 hover:text-neon-green flex items-center justify-center cursor-pointer transition-all duration-300 shadow-lg hover:shadow-[0_0_10px_rgba(0,255,102,0.3)]">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                            <input type="file" name="avatar" class="hidden" accept="image/*" onchange="this.form.submit()">
                                        </label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-neon-green/10 text-neon-green border border-neon-green/20">
                                        {{ strtoupper($realUser->vai_tro ?? 'USER') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Cột Phải: Grid các trường thông tin -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow w-full">
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="user" class="w-3 h-3"></i> Họ và tên
                                    </label>
                                    <input type="text" name="ho_ten" value="{{ old('ho_ten', $realUser->ho_ten) }}" class="bg-[#121414]/80 border border-white/5 text-white rounded-lg py-3 px-4 text-sm font-medium focus:outline-none focus:border-neon-green/50 transition-colors w-full">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="phone" class="w-3 h-3"></i> Số điện thoại
                                    </label>
                                    <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai', $realUser->so_dien_thoai) }}" class="bg-[#121414]/80 border border-white/5 text-white rounded-lg py-3 px-4 text-sm font-mono focus:outline-none focus:border-neon-green/50 transition-colors w-full">
                                </div>
                                <div class="flex flex-col gap-2 md:col-span-2">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="mail" class="w-3 h-3"></i> Địa chỉ Email
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $realUser->email) }}" class="bg-[#121414]/80 border border-white/5 text-gray-400 rounded-lg py-3 px-4 text-sm font-mono focus:outline-none focus:border-neon-green/50 transition-colors w-full" readonly>
                                </div>
                                <div class="flex flex-col gap-2 md:col-span-2">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="text-quote" class="w-3 h-3"></i> Tiểu sử (Bio)
                                    </label>
                                    <textarea name="bio" rows="3" class="bg-[#121414]/80 border border-white/5 text-gray-400 rounded-lg py-3 px-4 text-sm min-h-[80px] leading-relaxed focus:outline-none focus:border-neon-green/50 transition-colors w-full">{{ old('bio', $realUser->bio) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endif

                <!-- TAB: ĐỔI MẬT KHẨU -->
                @if($tab === 'change-password')
                <form action="#" method="POST" class="bg-[#1a1c1c]/50 border border-white/5 backdrop-blur-md rounded-xl p-8 animate-fadeInUp">
                    @csrf
                    <div class="flex flex-col gap-8">
                        <!-- Title & Header -->
                        <div class="border-b border-white/5 pb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold uppercase text-white tracking-tight flex items-center gap-3">
                                    <i data-lucide="key-round" class="text-neon-green"></i> Đổi mật khẩu
                                </h3>
                                <p class="text-gray-500 text-xs mt-1 uppercase tracking-wider">Cập nhật mật khẩu bảo mật của bạn</p>
                            </div>
                            <!-- Nút Cập nhật mật khẩu -->
                            <button type="submit" class="flex items-center gap-1.5 border border-neon-green/30 hover:border-neon-green bg-neon-green/10 hover:bg-neon-green text-neon-green hover:text-black px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all duration-300 shadow-[0_0_15px_rgba(0,255,102,0.1)] hover:shadow-[0_0_15px_rgba(0,255,102,0.3)]">
                                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                <span>Cập nhật</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-6 w-full">
                            <!-- Mật khẩu cũ -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                    <i data-lucide="lock" class="w-3 h-3"></i> Mật khẩu cũ
                                </label>
                                <input type="password" name="old_password" placeholder="Nhập mật khẩu hiện tại" class="bg-[#121414]/80 border border-white/5 text-white placeholder-gray-600 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-neon-green/50 transition-colors w-full">
                            </div>

                            <!-- Mật khẩu mới -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                    <i data-lucide="shield-check" class="w-3 h-3"></i> Mật khẩu mới
                                </label>
                                <input type="password" name="password" placeholder="Nhập mật khẩu mới" class="bg-[#121414]/80 border border-white/5 text-white placeholder-gray-600 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-neon-green/50 transition-colors w-full">
                            </div>

                            <!-- Xác nhận mật khẩu mới -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1.5">
                                    <i data-lucide="shield-alert" class="w-3 h-3"></i> Xác nhận mật khẩu mới
                                </label>
                                <input type="password" name="password_confirmation" placeholder="Xác nhận lại mật khẩu mới" class="bg-[#121414]/80 border border-white/5 text-white placeholder-gray-600 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-neon-green/50 transition-colors w-full">
                            </div>
                        </div>
                    </div>
                </form>
                @endif

                <!-- TAB 2: SỔ ĐỊA CHỈ -->
                @if($tab === 'addresses')
                <div class="bg-[#1a1c1c]/50 border border-white/5 backdrop-blur-md rounded-xl p-8 animate-fadeInUp">
                    <div class="flex flex-col gap-8">
                        <!-- Header -->
                        <div class="border-b border-white/5 pb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold uppercase text-white tracking-tight flex items-center gap-3">
                                    <i data-lucide="map-pinned" class="text-neon-green"></i> Sổ địa chỉ
                                </h3>
                                <p class="text-gray-500 text-xs mt-1 uppercase tracking-wider">Quản lý các địa chỉ nhận hàng của bạn</p>
                            </div>
                        </div>

                        <!-- Danh sách địa chỉ đã lưu -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($addresses as $address)
                            <div class="border rounded-xl p-5 flex flex-col justify-between transition-all duration-300 bg-[#121414]/30 {{ $address->is_default ? 'border-neon-green shadow-[0_0_15px_rgba(0,255,102,0.05)]' : 'border-white/5 hover:border-white/20' }}">
                                <div>
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-2">
                                            <h5 class="font-bold text-white text-sm uppercase tracking-tight">{{ $address->ho_ten }}</h5>
                                            @if($address->is_default)
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase bg-neon-green/10 text-neon-green border border-neon-green/20">
                                                Mặc định
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="space-y-1 text-xs text-gray-400 font-mono">
                                        <p class="flex items-center gap-2">
                                            <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-600"></i>
                                            <span>{{ $address->so_dien_thoai }}</span>
                                        </p>
                                        <p class="flex items-start gap-2 mt-2 leading-relaxed">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-600 mt-0.5 flex-shrink-0"></i>
                                            <span>
                                                {{ $address->dia_chi_chi_tiet }}<br>
                                                {{ collect([$address->phuong_xa, $address->quan_huyen, $address->tinh_thanh])->filter()->implode(', ') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="md:col-span-2 py-12 flex flex-col items-center justify-center border border-white/5 border-dashed rounded-xl">
                                <i data-lucide="map" class="w-10 h-10 text-gray-700 mb-3"></i>
                                <p class="text-gray-500 italic text-xs tracking-widest uppercase">Bạn chưa lưu địa chỉ nào</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Form thêm địa chỉ mới (có thể thu gọn) -->
                        <div x-data="{ open: false }">
                            <button type="button" @click="open = !open"
                                class="w-full py-3 border border-dashed border-white/15 hover:border-neon-green/40 text-gray-500 hover:text-neon-green text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center justify-center gap-2">
                                <i data-lucide="plus" class="w-3.5 h-3.5" :class="open ? 'rotate-45' : ''" style="transition: transform 0.2s"></i>
                                <span x-text="open ? 'Hủy' : 'Thêm địa chỉ mới'"></span>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="mt-4">
                                <form action="{{ route('user-address.store') }}" method="POST"
                                      class="bg-[#121414]/60 border border-white/10 rounded-xl p-6">
                                    @csrf

                                    <!-- Họ tên & SĐT -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="flex flex-col gap-1.5">
                                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Họ và Tên</label>
                                            <input type="text" name="ho_ten" required placeholder="Nguyễn Văn A"
                                                   class="w-full px-3 py-2.5 text-sm bg-white/[0.03] border border-white/10 rounded-lg text-white placeholder:text-white/20 focus:border-neon-green/50 focus:outline-none transition-colors">
                                        </div>
                                        <div class="flex flex-col gap-1.5">
                                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Số điện thoại</label>
                                            <input type="text" name="so_dien_thoai" required placeholder="0900 000 000"
                                                   class="w-full px-3 py-2.5 text-sm bg-white/[0.03] border border-white/10 rounded-lg text-white placeholder:text-white/20 focus:border-neon-green/50 focus:outline-none transition-colors font-mono">
                                        </div>
                                    </div>

                                    <!-- Dropdown Tỉnh/TP & Phường/Xã via API -->
                                     <div
                                         x-data="{
                                             provinces: [],
                                             wards: [],
                                             selectedProvince: '',
                                             selectedWard: '',
                                             async init() {
                                                 try {
                                                     const res = await fetch('https://provinces.open-api.vn/api/v2/p/');
                                                     this.provinces = await res.json();
                                                 } catch (e) {
                                                     console.error('Lỗi tải danh mục Tỉnh/Thành', e);
                                                 }
                                             },
                                             async fetchWards() {
                                                 this.wards = [];
                                                 this.selectedWard = '';
                                                 if (!this.selectedProvince) return;

                                                 try {
                                                     const res = await fetch(`https://provinces.open-api.vn/api/v2/p/${this.selectedProvince}?depth=2`);
                                                     const data = await res.json();
                                                     this.wards = data.wards || [];
                                                 } catch (e) {
                                                     console.error('Lỗi tải danh mục Phường/Xã', e);
                                                 }
                                             }
                                         }"
                                     >
                                         <div class="grid grid-cols-2 gap-3 mb-3">
                                             {{-- Tỉnh / Thành Phố --}}
                                             <div>
                                                 <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Tỉnh / Thành Phố</label>
                                                 <select x-model="selectedProvince"
                                                         @change="fetchWards()"
                                                         class="w-full px-3 py-2 text-xs bg-white/5 border border-white/10 rounded-lg text-white focus:border-neon-green/50 focus:outline-none appearance-none cursor-pointer">
                                                     <option value="" class="bg-gray-900 text-gray-400">-- Chọn Tỉnh/TP --</option>
                                                     <template x-for="p in provinces" :key="p.code">
                                                         <option :value="p.code" x-text="p.name" class="bg-gray-900 text-white"></option>
                                                     </template>
                                                 </select>
                                                 <input type="hidden" name="tinh_thanh" :value="provinces.find(p => String(p.code) === String(selectedProvince))?.name || ''">
                                             </div>

                                             {{-- Phường / Xã --}}
                                             <div>
                                                 <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Phường / Xã</label>
                                                 <select x-model="selectedWard"
                                                         :disabled="!selectedProvince"
                                                         class="w-full px-3 py-2 text-xs bg-white/5 border border-white/10 rounded-lg text-white focus:border-neon-green/50 focus:outline-none appearance-none cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                                     <option value="" class="bg-gray-900 text-gray-400">-- Chọn Phường/Xã --</option>
                                                     <template x-for="w in wards" :key="w.code">
                                                         <option :value="w.code" x-text="w.name" class="bg-gray-900 text-white"></option>
                                                     </template>
                                                 </select>
                                                 <input type="hidden" name="phuong_xa" :value="wards.find(w => String(w.code) === String(selectedWard))?.name || ''">
                                             </div>
                                         </div>
                                         <input type="hidden" name="quan_huyen" value="">
                                     </div>
                                    <!-- Địa chỉ chi tiết -->
                                    <div class="flex flex-col gap-1.5 mb-5">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Địa chỉ chi tiết</label>
                                        <input type="text" name="dia_chi_chi_tiet" required placeholder="Số nhà, tên đường..."
                                               class="w-full px-3 py-2.5 text-sm bg-white/[0.03] border border-white/10 rounded-lg text-white placeholder:text-white/20 focus:border-neon-green/50 focus:outline-none transition-colors">
                                    </div>

                                    <button type="submit"
                                            class="w-full py-3 bg-neon-green hover:opacity-90 text-black font-black text-[10px] tracking-widest uppercase rounded-lg active:scale-[0.98] transition-all shadow-[0_0_20px_rgba(0,255,102,0.2)]">
                                        Lưu địa chỉ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- TAB 3: LỊCH SỬ ĐƠN HÀNG -->
                @if($tab === 'orders')
                <div class="bg-[#1a1c1c]/50 border border-white/5 backdrop-blur-md rounded-xl p-8 animate-fadeInUp">
                    <div class="border-b border-white/5 pb-4 mb-6">
                        <h3 class="text-xl font-bold uppercase text-white tracking-tight flex items-center gap-3">
                            <i data-lucide="receipt" class="text-neon-green"></i> Lịch sử mua hàng
                        </h3>
                        <p class="text-gray-500 text-xs mt-1 uppercase tracking-wider">Danh sách các đơn đặt hàng của bạn trên hệ thống</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-0">
                            <thead>
                                <tr class="text-[10px] text-gray-500 uppercase tracking-[0.2em] border-b border-white/5">
                                    <th class="px-4 py-3 font-bold border-b border-white/5">Mã đơn</th>
                                    <th class="px-4 py-3 font-bold border-b border-white/5">Ngày đặt</th>
                                    <th class="px-4 py-3 font-bold border-b border-white/5">Tổng tiền</th>
                                    <th class="px-4 py-3 font-bold border-b border-white/5">Trạng thái</th>
                                    <th class="px-4 py-3 font-bold text-right border-b border-white/5">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-mono">
                                @forelse($orders as $order)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-4 py-4 text-neon-green font-bold">#{{ $order->ma_don_hang }}</td>
                                    <td class="px-4 py-4 text-gray-400">{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="px-4 py-4 text-white font-bold">{{ number_format($order->tong_thanh_toan, 0, ',', '.') }}đ</td>
                                    <td class="px-4 py-4">
                                        @if($order->trang_thai === 'da_nhan_hang')
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold border bg-neon-green/10 text-neon-green border-neon-green/20">
                                                HOÀN TẤT
                                            </span>
                                        @elseif($order->trang_thai === 'cho_xac_nhan')
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold border bg-red-500/10 text-red-500 border-red-500/20 animate-pulse">
                                                CHỜ XÁC NHẬN
                                            </span>
                                        @elseif($order->trang_thai === 'cho_thanh_toan')
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold border bg-yellow-500/10 text-yellow-400 border-yellow-500/20">
                                                CHỜ THANH TOÁN
                                            </span>
                                        @elseif($order->trang_thai === 'da_huy')
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold border bg-gray-500/10 text-gray-400 border-gray-500/20">
                                                ĐÃ HỦY
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold border bg-blue-500/10 text-blue-400 border-blue-500/20">
                                                ĐANG XỬ LÝ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('order_detail.view', ['ma_don_hang' => $order->ma_don_hang]) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-white/10 hover:border-neon-green/40 bg-white/[0.02] hover:bg-neon-green/5 text-gray-300 hover:text-neon-green rounded text-[10px] font-bold uppercase tracking-widest transition-all duration-300 group">
                                            <span>Chi tiết</span>
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-700"></i>
                                            <span class="italic">Bạn chưa thực hiện đơn đặt hàng nào</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
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