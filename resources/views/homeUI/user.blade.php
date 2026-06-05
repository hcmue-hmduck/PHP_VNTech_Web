@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân | VNTech')

@section('content')
@php
    $realUser = $user ?? Auth::user();
    $tab = request()->query('tab', 'profile');
    if (in_array($tab, ['change-email', 'change-password'])) {
        $tab = 'profile';
    }
    
    $addresses = $user_address ?? collect();
    $orders = $orders ?? collect();
@endphp

<style>
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
</style>

<div class="min-h-screen bg-[#fcf9f8] text-[#1c1b1b] selection:bg-[#ff5c00]/20 selection:text-[#ff5c00] font-sans">
    <main class="pt-10 pb-10 px-4 md:px-12 max-w-7xl mx-auto w-full animate-[fadeIn_0.5s_ease-out]">
        
        <!-- Header -->
        <header class="mb-10 text-center select-none relative flex flex-col items-center">
            <!-- Back trigger: top-left corner -->
            <a href="{{ url('/') }}" 
               class="absolute left-0 top-0 flex items-center gap-2 px-4 py-2 border border-neutral-200 bg-white hover:bg-neutral-50 rounded-xl text-neutral-500 hover:text-neutral-800 text-[11px] font-display font-bold uppercase tracking-widest transition-all duration-200 shadow-xs">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Trang chủ</span>
            </a>

            <h1 class="font-display font-black text-4xl md:text-5xl text-neutral-900 uppercase tracking-tight">
                Hồ sơ cá nhân
            </h1>
            <p class="text-neutral-500 mt-2 font-display font-bold uppercase tracking-wider text-xs">
                Quản lý thông tin tài khoản và danh sách địa chỉ của bạn.
            </p>
        </header>

        <!-- Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Sidebar -->
            <div class="lg:col-span-3 lg:sticky lg:top-24 flex flex-col gap-2 h-fit bg-white p-5 border border-neutral-200/60 rounded-3xl shadow-sm">
                <!-- User Profile Intro -->
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-neutral-100 select-none">
                    <img src="{{ !empty($realUser->avatar_url) ? $realUser->avatar_url : asset('images/AvatarDefault.jpg') }}" alt="Avatar" class="w-12 h-12 rounded-full border border-neutral-200 object-cover shrink-0">
                    <div class="overflow-hidden">
                        <h4 class="text-neutral-900 font-display font-bold text-sm truncate uppercase tracking-tight">{{ $realUser->ho_ten ?? 'Khách hàng' }}</h4>
                        <p class="text-neutral-400 text-xs truncate font-mono">{{ $realUser->email }}</p>
                    </div>
                </div>

                <!-- Tabs Sidebar Menu Links -->
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('user.view', ['tab' => 'profile']) }}" 
                       class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-3 select-none {{ $tab === 'profile' ? 'bg-[#ff5c00] text-white shadow-sm' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <i data-lucide="user" class="w-5 h-5 shrink-0"></i>
                        <span>Hồ sơ của tôi</span>
                    </a>
                    <a href="{{ route('user.view', ['tab' => 'addresses']) }}" 
                       class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-3 select-none {{ $tab === 'addresses' ? 'bg-[#ff5c00] text-white shadow-sm' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <i data-lucide="map-pin" class="w-5 h-5 shrink-0"></i>
                        <span>Số địa chỉ</span>
                    </a>
                    
                    <hr class="my-3 border-neutral-100" />
                    
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    <button
                      type="button"
                      onclick="event.preventDefault(); if (confirm('Bạn có chắc chắn muốn đăng xuất khỏi VNTech?')) { document.getElementById('sidebar-logout-form').submit(); }"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-all duration-200 text-left w-full outline-none"
                    >
                      <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                      <span>Đăng xuất</span>
                    </button>
                </div>

            </div>

            <!-- Right Column: Interactive Content -->
            <div class="lg:col-span-9 space-y-8">
                
                <!-- TAB 1: PROFILE INFO -->
                @if($tab === 'profile')
                <div class="flex flex-col gap-6 animate-[fadeIn_0.4s_ease-out]">
                    <!-- Header Profile Info Section -->
                    <section class="bg-white rounded-3xl p-6 md:p-8 border border-neutral-200/60 shadow-sm flex flex-col md:flex-row items-center gap-6 md:gap-8 select-none">
                        <div class="relative group shrink-0">
                            <div class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-neutral-100">
                                <img id="avatar-preview" 
                                     alt="Profile Picture" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" 
                                     src="{{ !empty($realUser->avatar_url) ? $realUser->avatar_url : asset('images/AvatarDefault.jpg') }}" />
                                <div id="avatar-placeholder" 
                                     class="w-full h-full bg-orange-50 flex items-center justify-center text-[#ff5c00] font-display font-black text-4xl hidden">
                                </div>
                            </div>
                            <label class="absolute bottom-0 right-0 bg-[#ff5c00] hover:bg-[#e04f00] text-white p-2.5 rounded-full shadow-lg hover:scale-110 active:scale-90 transition-transform cursor-pointer">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                                <input type="file" name="avatar" id="avatar-file-input" form="main-profile-form" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                            </label>
                        </div>

                        <div class="text-center md:text-left flex-1 min-w-0">
                            <h1 class="font-display text-2xl md:text-3xl font-black text-neutral-900 truncate">
                                {{ $realUser->ho_ten ?? 'Khách hàng' }}
                            </h1>
                            <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-800 rounded-full border border-amber-100">
                                <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-500 text-amber-500"></i>
                                <span class="font-semibold text-xs uppercase">{{ strtoupper($realUser->vai_tro ?? 'USER') }}</span>
                            </div>
                            <p class="text-neutral-500 text-xs mt-3 font-semibold">
                                Thành viên từ: {{ !empty($realUser->created_at) && method_exists($realUser->created_at, 'format') ? $realUser->created_at->format('d/m/Y') : '2026' }}
                            </p>
                        </div>

                        <!-- Quantities summaries -->
                        <div class="flex gap-4 md:ml-auto w-full md:w-auto justify-center border-t md:border-t-0 md:border-l border-neutral-100 pt-4 md:pt-0 md:pl-6">
                            <div class="text-center px-4 md:px-6">
                                <p class="font-display text-2xl md:text-3xl font-black text-[#ff5c00]">
                                    {{ $orders->count() }}
                                </p>
                                <p class="font-semibold text-xs text-neutral-500 mt-1">Đơn hàng</p>
                            </div>
                            <div class="h-10 w-px bg-neutral-100 self-center"></div>
                            <div class="text-center px-4 md:px-6">
                                <p class="font-display text-2xl md:text-3xl font-black text-[#ff5c00]">
                                    {{ $addresses->count() }}
                                </p>
                                <p class="font-semibold text-xs text-neutral-500 mt-1">Địa chỉ</p>
                            </div>
                        </div>
                    </section>

                    <!-- Personal Information Form Card -->
                    <section class="bg-white rounded-3xl p-6 md:p-8 border border-neutral-200/60 shadow-sm" x-data="{ isEditing: false, hasNewAvatar: false }">
                        <div class="flex justify-between items-center mb-6 border-b border-neutral-100 pb-4">
                            <h2 class="font-display text-xl font-extrabold text-neutral-900 select-none">
                                Thông tin cá nhân
                            </h2>
                            <button 
                                type="button"
                                @click="isEditing = !isEditing"
                                class="text-[#ff5c00] hover:text-[#e04f00] font-semibold text-sm transition-colors hover:underline"
                                x-text="isEditing ? 'Hủy bỏ' : 'Chỉnh sửa'"
                            >
                                Chỉnh sửa
                            </button>
                        </div>

                        <form id="main-profile-form" action="{{ route('user.update', $realUser->ma_nguoi_dung) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                <div class="flex flex-col gap-2">
                                    <label for="fullName" class="font-bold text-xs text-neutral-500 uppercase tracking-wider select-none">
                                        Họ và tên
                                    </label>
                                    <input
                                        id="fullName"
                                        name="ho_ten"
                                        type="text"
                                        required
                                        :disabled="!isEditing"
                                        class="w-full px-4 py-3 rounded-xl border outline-none transition-all text-sm font-semibold focus:outline-none"
                                        :class="isEditing 
                                            ? 'border-neutral-350 focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10 bg-white text-neutral-800' 
                                            : 'border-neutral-100 bg-neutral-50 text-neutral-500 cursor-not-allowed'"
                                        value="{{ old('ho_ten', $realUser->ho_ten) }}"
                                    />
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label for="phone" class="font-bold text-xs text-neutral-500 uppercase tracking-wider select-none">
                                        Số điện thoại
                                    </label>
                                    <input
                                        id="phone"
                                        name="so_dien_thoai"
                                        type="tel"
                                        required
                                        :disabled="!isEditing"
                                        class="w-full px-4 py-3 rounded-xl border outline-none transition-all text-sm font-mono font-bold focus:outline-none"
                                        :class="isEditing 
                                            ? 'border-neutral-350 focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10 bg-white text-neutral-800' 
                                            : 'border-neutral-100 bg-neutral-50 text-neutral-500 cursor-not-allowed'"
                                        value="{{ old('so_dien_thoai', $realUser->so_dien_thoai) }}"
                                    />
                                </div>

                                <div class="flex flex-col gap-2 md:col-span-2">
                                    <label for="email" class="font-bold text-xs text-neutral-500 uppercase tracking-wider select-none">
                                        Địa chỉ Email
                                    </label>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        required
                                        disabled
                                        class="w-full px-4 py-3 rounded-xl border border-neutral-100 bg-neutral-50 text-neutral-400 cursor-not-allowed text-sm font-mono focus:outline-none"
                                        value="{{ old('email', $realUser->email) }}"
                                    />
                                </div>

                                <div class="flex flex-col gap-2 md:col-span-2">
                                    <label for="bio" class="font-bold text-xs text-neutral-500 uppercase tracking-wider select-none">
                                        Tiểu sử (Bio)
                                    </label>
                                    <textarea
                                        id="bio"
                                        name="bio"
                                        rows="3"
                                        :disabled="!isEditing"
                                        class="w-full px-4 py-3 rounded-xl border outline-none transition-all text-sm font-medium leading-relaxed focus:outline-none min-h-[100px]"
                                        :class="isEditing 
                                            ? 'border-neutral-350 focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10 bg-white text-neutral-800' 
                                            : 'border-neutral-100 bg-neutral-50 text-neutral-500 cursor-not-allowed'"
                                    >{{ old('bio', $realUser->bio) }}</textarea>
                                </div>

                                <div class="md:col-span-2 flex justify-end mt-4" x-show="isEditing || hasNewAvatar" x-cloak>
                                    <button
                                        id="btn-save-profile" 
                                        type="submit"
                                        class="bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-bold text-sm px-8 py-3 rounded-xl transition-all active:scale-95 shadow-sm flex items-center gap-2"
                                    >
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                        <!-- TAB: ĐỔI EMAIL -->
                        <div>
                            <form action="{{ route('user.email.change.request') }}" method="POST" class="bg-white border border-neutral-200/60 p-6 md:p-8 rounded-3xl shadow-sm">
                                @csrf
                                <div class="space-y-6">
                                    <div class="border-b border-neutral-100 pb-4">
                                        <h3 class="text-lg font-display font-extrabold text-neutral-900 tracking-tight flex items-center gap-2 select-none">
                                            <i data-lucide="mail" class="text-[#ff5c00] w-5 h-5"></i>
                                            <span>Thay đổi địa chỉ Email</span>
                                        </h3>
                                        <p class="text-neutral-500 text-xs font-semibold mt-1">Cập nhật địa chỉ email và xác thực OTP bảo vệ tài khoản</p>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                                            <i data-lucide="mail" class="w-3.5 h-3.5"></i> Email mới
                                        </label>
                                        <input type="email" name="new_email" value="{{ old('new_email', $realUser->email) }}" required placeholder="Nhập email mới" class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-[#ff5c00] focus:bg-white text-sm rounded-xl py-3 px-4 focus:ring-2 focus:ring-[#ff5c00]/20 transition-all text-neutral-800 font-medium focus:outline-none">
                                    </div>

                                    <button type="submit" class="w-full bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-bold text-sm py-3 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                        <span>Cập nhật email</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: CHANGE PASSWORD -->
                        <div>
                            <form action="{{ route('user.update', $realUser->ma_nguoi_dung) }}" method="POST" class="bg-white border border-neutral-200/60 p-6 md:p-8 rounded-3xl shadow-sm">
                                @csrf
                                @method('PUT')
                                <div class="space-y-6">
                                    <div class="border-b border-neutral-100 pb-4">
                                        <h3 class="text-lg font-display font-extrabold text-neutral-900 tracking-tight flex items-center gap-2 select-none">
                                            <i data-lucide="key-round" class="text-[#ff5c00] w-5 h-5"></i>
                                            <span>Thay đổi mật khẩu</span>
                                        </h3>
                                        <p class="text-neutral-500 text-xs font-semibold mt-1">Cập nhật mật khẩu bảo vệ tài khoản của bạn</p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 w-full">
                                        <!-- Old Password -->
                                        <div class="flex flex-col gap-2">
                                            <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                                                <i data-lucide="lock" class="w-3.5 h-3.5"></i> Mật khẩu cũ
                                            </label>
                                            <div class="relative">
                                                <input type="password" id="old_password" name="old_password" required placeholder="Nhập mật khẩu hiện tại" class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-[#ff5c00] focus:bg-white text-sm rounded-xl py-3 pl-4 pr-10 focus:ring-2 focus:ring-[#ff5c00]/20 transition-all text-neutral-800 font-medium focus:outline-none">
                                                <button type="button" onclick="togglePasswordVisibility('old_password', this)" class="absolute inset-y-0 right-3 flex items-center text-neutral-400 hover:text-neutral-700 transition-colors">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                            @error('old_password')
                                                <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- New Password -->
                                        <div class="flex flex-col gap-2">
                                            <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Mật khẩu mới
                                            </label>
                                            <div class="relative">
                                                <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu mới" class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-[#ff5c00] focus:bg-white text-sm rounded-xl py-3 pl-4 pr-10 focus:ring-2 focus:ring-[#ff5c00]/20 transition-all text-neutral-800 font-medium focus:outline-none">
                                                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-3 flex items-center text-neutral-400 hover:text-neutral-700 transition-colors">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="flex flex-col gap-2">
                                            <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                                                <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i> Xác nhận mật khẩu mới
                                            </label>
                                            <div class="relative">
                                                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Xác nhận lại mật khẩu mới" class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-[#ff5c00] focus:bg-white text-sm rounded-xl py-3 pl-4 pr-10 focus:ring-2 focus:ring-[#ff5c00]/20 transition-all text-neutral-800 font-medium focus:outline-none">
                                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute inset-y-0 right-3 flex items-center text-neutral-400 hover:text-neutral-700 transition-colors">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-bold text-sm py-3 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                        <span>Cập nhật mật khẩu</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <!-- TAB 3: ADDRESSES -->
                @if($tab === 'addresses')
                <div class="bg-white border border-neutral-200/60 p-6 md:p-10 rounded-3xl shadow-sm space-y-8 animate-[fadeIn_0.4s_ease-out]"
                     x-data="{
                         openAdd: false,
                         openEdit: false,
                         editingAddress: { ho_ten: '', so_dien_thoai: '', dia_chi_chi_tiet: '', tinh_thanh: '', quan_huyen: '', phuong_xa: '', is_default: false, ma_dia_chi: '' },
                         provinces: [],
                         wards: [],
                         selectedProvince: '',
                         selectedWard: '',
                         editProvince: '',
                         editWard: '',
                         editWards: [],
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
                         },
                         async fetchEditWards() {
                             this.editWards = [];
                             this.editWard = '';
                             if (!this.editProvince) return;
                             try {
                                 const res = await fetch(`https://provinces.open-api.vn/api/v2/p/${this.editProvince}?depth=2`);
                                 const data = await res.json();
                                 this.editWards = data.wards || [];
                             } catch (e) {
                                 console.error('Lỗi tải danh mục Phường/Xã', e);
                             }
                         },
                         async openEditModal(address) {
                             this.editingAddress = {
                                 ho_ten: address.ho_ten,
                                 so_dien_thoai: address.so_dien_thoai,
                                 dia_chi_chi_tiet: address.dia_chi_chi_tiet,
                                 tinh_thanh: address.tinh_thanh || '',
                                 quan_huyen: address.quan_huyen || '',
                                 phuong_xa: address.phuong_xa || '',
                                 is_default: !!address.is_default,
                                 ma_dia_chi: address.ma_dia_chi
                             };
                             this.openEdit = true;
                             
                             const matching = this.provinces.find(p => p.name === address.tinh_thanh);
                             if (matching) {
                                 this.editProvince = matching.code;
                                 await this.fetchEditWards();
                                 const ward = this.editWards.find(w => w.name === address.phuong_xa);
                                 this.editWard = ward ? ward.code : '';
                             } else {
                                 this.editProvince = '';
                                 this.editWards = [];
                                 this.editWard = '';
                             }
                             this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                         }
                     }">
                    
                    <div class="border-b border-neutral-100 pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4 select-none">
                        <div>
                            <h3 class="text-xl font-display font-extrabold text-neutral-900 tracking-tight flex items-center gap-2">
                                <i data-lucide="map-pinned" class="text-[#ff5c00]"></i>
                                <span>Sổ địa chỉ giao hàng</span>
                            </h3>
                            <p class="text-neutral-500 text-xs font-semibold mt-1">Quản lý các địa chỉ nhận hàng để thanh toán nhanh chóng hơn.</p>
                        </div>
                        <button
                            type="button"
                            @click="openAdd = true"
                            class="bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-bold text-xs px-4 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm flex items-center justify-center gap-1.5 self-start md:self-auto cursor-pointer"
                        >
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Thêm địa chỉ mới
                        </button>
                    </div>

                    <!-- Address cards list -->
                    <div class="space-y-4">
                        @forelse($addresses as $address)
                        <div class="p-5 rounded-2xl border transition-all duration-200 relative {{ $address->is_default ? 'border-[#ff5c00] bg-orange-50/5' : 'border-neutral-200/60 hover:border-[#ff5c00]/60 bg-white' }}">
                            <div class="flex items-start gap-4">
                                <div class="p-2.5 rounded-xl shrink-0 {{ $address->is_default ? 'bg-orange-50 text-[#ff5c00]' : 'bg-neutral-50 text-neutral-400' }}">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>

                                <div class="flex-1 min-w-0 pr-8">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-display font-bold text-sm text-neutral-900 truncate">{{ $address->ho_ten }}</span>
                                        @if($address->is_default)
                                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-0.5 border border-emerald-100/60 select-none">
                                            <i data-lucide="check" class="w-3 h-3"></i> Mặc định
                                        </span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-neutral-500 mt-2 font-semibold">
                                        Số điện thoại: <span class="font-mono text-neutral-800 font-bold">{{ $address->so_dien_thoai }}</span>
                                    </p>
                                    <p class="text-xs text-neutral-500 mt-1 font-semibold leading-relaxed">
                                        Địa chỉ: {{ $address->dia_chi_chi_tiet }}, {{ $address->phuong_xa }}, {{ $address->tinh_thanh }}
                                    </p>
                                </div>
                            </div>

                            <!-- Card actions -->
                            <div class="mt-4 pt-3 border-t border-dashed border-neutral-100 flex justify-between items-center select-none">
                                @if(!$address->is_default)
                                <form action="{{ route('user-address.set-default', $address->ma_dia_chi) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#ff5c00] hover:text-[#e04f00] font-bold transition-colors cursor-pointer">
                                        Đặt làm mặc định
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-emerald-700 font-bold inline-flex items-center gap-1">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Địa chỉ giao hàng ưu tiên
                                </span>
                                @endif

                                <div class="flex items-center gap-3">
                                    <button
                                        type="button"
                                        @click="openEditModal({{ Js::from($address) }})"
                                        class="text-xs text-[#ff5c00] hover:text-[#e04f00] font-bold inline-flex items-center gap-1 transition-colors cursor-pointer"
                                    >
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i> Sửa
                                    </button>
                                    <form action="{{ route('user-address.destroy', $address->ma_dia_chi) }}"
                                          method="POST"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');"
                                          class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-700 font-bold inline-flex items-center gap-0.5 transition-colors cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 bg-neutral-50/50 rounded-2xl border border-dashed border-neutral-200">
                            <i data-lucide="map" class="w-12 h-12 text-neutral-300 mx-auto opacity-70 mb-3 animate-pulse"></i>
                            <p class="font-bold text-xs uppercase tracking-wider text-neutral-400">Bạn chưa lưu địa chỉ nào.</p>
                            <p class="text-[11px] text-neutral-400 mt-1 font-semibold">Hãy nhấn nút "Thêm địa chỉ mới" để cấu hình điểm giao hàng.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- ADD ADDRESS DIALOG MODAL -->
                    <div x-show="openAdd" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4" x-cloak>
                        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-neutral-100 overflow-hidden animate-[scaleUp_0.2s_ease-out]">
                            <!-- Modal Header -->
                            <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100 select-none">
                                <h3 class="font-display font-extrabold text-neutral-900 text-base">Thêm địa chỉ giao hàng mới</h3>
                                <button type="button" @click="openAdd = false" class="p-1.5 hover:bg-neutral-100 rounded-full text-neutral-400 hover:text-neutral-600 transition-colors">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </div>

                            <!-- Modal Form -->
                            <form action="{{ route('user-address.store') }}" method="POST" class="p-6 space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Họ và tên người nhận *</label>
                                        <input type="text" name="ho_ten" required class="px-3 py-2.5 border border-neutral-205 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10" placeholder="Nguyễn Văn A">
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Số điện thoại liên hệ *</label>
                                        <input type="tel" name="so_dien_thoai" required class="px-3 py-2.5 border border-neutral-205 rounded-xl text-sm font-mono font-bold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10" placeholder="0900 000 000">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Tỉnh / Thành Phố *</label>
                                        <select x-model="selectedProvince"
                                                @change="fetchWards()"
                                                class="w-full px-3 py-2.5 bg-white border border-neutral-205 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10 appearance-none cursor-pointer">
                                            <option value="">-- Chọn Tỉnh/TP --</option>
                                            <template x-for="p in provinces" :key="p.code">
                                                <option :value="p.code" x-text="p.name"></option>
                                            </template>
                                        </select>
                                        <input type="hidden" name="tinh_thanh" :value="provinces.find(p => String(p.code) === String(selectedProvince))?.name || ''">
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Phường / Xã *</label>
                                        <select x-model="selectedWard"
                                                :disabled="!selectedProvince"
                                                class="w-full px-3 py-2.5 bg-white border border-neutral-205 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10 disabled:opacity-40 disabled:cursor-not-allowed appearance-none cursor-pointer">
                                            <option value="">-- Chọn Phường/Xã --</option>
                                            <template x-for="w in wards" :key="w.code">
                                                <option :value="w.code" x-text="w.name"></option>
                                            </template>
                                        </select>
                                        <input type="hidden" name="phuong_xa" :value="wards.find(w => String(w.code) === String(selectedWard))?.name || ''">
                                    </div>
                                </div>
                                <input type="hidden" name="quan_huyen" value="">

                                <div class="flex flex-col gap-1.5">
                                    <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Số nhà, tên đường *</label>
                                    <input type="text" name="dia_chi_chi_tiet" required class="px-3 py-2.5 border border-neutral-205 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10" placeholder="Ví dụ: 123 Đường Ba Tháng Hai">
                                </div>

                                <div class="flex items-center gap-2 pt-2 select-none">
                                    <input type="checkbox" name="is_default" value="1" id="add_is_default" class="rounded text-[#ff5c00] focus:ring-[#ff5c00] h-4 w-4 accent-[#ff5c00]">
                                    <label for="add_is_default" class="text-xs font-semibold text-neutral-500">Đặt làm địa chỉ nhận mặc định</label>
                                </div>

                                <div class="border-t border-neutral-100 pt-4 flex gap-3 justify-end select-none">
                                    <button type="button" @click="openAdd = false" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-600 font-semibold text-xs px-4 py-2.5 rounded-xl transition-colors cursor-pointer">Hủy</button>
                                    <button type="submit" class="bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-bold text-xs px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm cursor-pointer">Xác nhận lưu</button>
                                </div>
                             </form>
                         </div>
                     </div>

                     <!-- EDIT ADDRESS DIALOG MODAL -->
                     <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4" x-cloak>
                         <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-neutral-100 overflow-hidden animate-[scaleUp_0.2s_ease-out]">
                             <!-- Modal Header -->
                             <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100 select-none">
                                 <h3 class="font-display font-extrabold text-neutral-900 text-base">Chỉnh sửa địa chỉ nhận</h3>
                                 <button type="button" @click="openEdit = false" class="p-1.5 hover:bg-neutral-100 rounded-full text-neutral-400 hover:text-neutral-600 transition-colors">
                                     <i data-lucide="x" class="w-5 h-5"></i>
                                 </button>
                             </div>

                             <!-- Modal Form -->
                             <form :action="'/user-address/' + editingAddress.ma_dia_chi + '/edit'" method="POST" class="p-6 space-y-4">
                                 @csrf
                                 @method('PUT')
                                 
                                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                     <div class="flex flex-col gap-1.5">
                                         <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Họ và tên người nhận *</label>
                                         <input type="text" name="ho_ten" required x-model="editingAddress.ho_ten" class="px-3 py-2.5 border border-neutral-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10">
                                     </div>
                                     <div class="flex flex-col gap-1.5">
                                         <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Số điện thoại liên hệ *</label>
                                         <input type="tel" name="so_dien_thoai" required x-model="editingAddress.so_dien_thoai" class="px-3 py-2.5 border border-neutral-200 rounded-xl text-sm font-mono font-bold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10">
                                     </div>
                                 </div>

                                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                     <div class="flex flex-col gap-1.5">
                                         <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Tỉnh / Thành Phố *</label>
                                         <select x-model="editProvince"
                                                 @change="fetchEditWards()"
                                                 class="w-full px-3 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10 appearance-none cursor-pointer">
                                             <option value="">-- Chọn Tỉnh/TP --</option>
                                             <template x-for="p in provinces" :key="p.code">
                                                 <option :value="p.code" x-text="p.name"></option>
                                             </template>
                                         </select>
                                         <input type="hidden" name="tinh_thanh" :value="provinces.find(p => String(p.code) === String(editProvince))?.name || ''">
                                     </div>

                                     <div class="flex flex-col gap-1.5">
                                         <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Phường / Xã *</label>
                                         <select x-model="editWard"
                                                 :disabled="!editProvince"
                                                 class="w-full px-3 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10 disabled:opacity-40 disabled:cursor-not-allowed appearance-none cursor-pointer">
                                             <option value="">-- Chọn Phường/Xã --</option>
                                             <template x-for="w in editWards" :key="w.code">
                                                 <option :value="w.code" x-text="w.name"></option>
                                             </template>
                                         </select>
                                         <input type="hidden" name="phuong_xa" :value="editWards.find(w => String(w.code) === String(editWard))?.name || ''">
                                     </div>
                                 </div>
                                 <input type="hidden" name="quan_huyen" value="">

                                 <div class="flex flex-col gap-1.5">
                                      <label class="font-bold text-[10px] text-neutral-500 uppercase tracking-widest select-none">Số nhà, tên đường *</label>
                                      <input type="text" name="dia_chi_chi_tiet" required x-model="editingAddress.dia_chi_chi_tiet" class="px-3 py-2.5 border border-neutral-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#ff5c00] focus:ring-2 focus:ring-[#ff5c00]/10">
                                  </div>

                                 <div class="flex items-center gap-2 pt-2 select-none">
                                     <input type="checkbox" name="is_default" value="1" id="edit_is_default" x-model="editingAddress.is_default" class="rounded text-[#ff5c00] focus:ring-[#ff5c00] h-4 w-4 accent-[#ff5c00]">
                                     <label for="edit_is_default" class="text-xs font-semibold text-neutral-500">Đặt làm địa chỉ nhận mặc định</label>
                                 </div>

                                 <div class="border-t border-neutral-100 pt-4 flex gap-3 justify-end select-none">
                                     <button type="button" @click="openEdit = false" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-600 font-semibold text-xs px-4 py-2.5 rounded-xl transition-colors cursor-pointer">Hủy</button>
                                     <button type="submit" class="bg-[#ff5c00] hover:bg-[#e04f00] text-white font-display font-bold text-xs px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm cursor-pointer">Xác nhận lưu</button>
                                 </div>
                             </form>
                         </div>
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

    function previewAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');

                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');

                // Trigger Alpine hasNewAvatar so the "Lưu thay đổi" button appears
                const section = document.querySelector('[x-data*="hasNewAvatar"]');
                if (section && section._x_dataStack) {
                    section._x_dataStack[0].hasNewAvatar = true;
                } else if (section) {
                    // Alpine 3: use $data via __x
                    Alpine.evaluate(section, 'hasNewAvatar = true');
                }

                if (typeof lucide !== 'undefined') lucide.createIcons();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4"></i>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<i data-lucide="eye" class="w-4 h-4"></i>';
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endsection
