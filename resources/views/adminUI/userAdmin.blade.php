@extends('layouts.admin')

@section('title', 'Quản lý người dùng - VNTech')

@section('content')
@php
    $totalUsers = $totalUsersCount;
    $activeUsers = $activeUsersCount;
    $inactiveUsers = $inactiveUsersCount;
@endphp

<div class="w-full">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)]">
                QUẢN LÝ NGƯỜI DÙNG
            </h1>
            <p class="text-[10px] font-mono text-gray-500 uppercase tracking-widest mt-2">
                Hệ thống quản trị tài khoản người dùng của VNTech
            </p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Total Users -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Tổng người dùng</p>
                <i data-lucide="users" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalUsers }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Tài khoản khách hàng</p>
            </div>
        </div>

        <!-- Active Users -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Đang hoạt động</p>
                <i data-lucide="shield-check" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $activeUsers }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Tài khoản bình thường</p>
            </div>
        </div>

        <!-- Inactive/Blocked Users -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Đã bị khóa</p>
                <i data-lucide="shield-ban" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $inactiveUsers }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Truy cập đã bị đình chỉ</p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="glass-panel p-6 border-l-4 border-l-neon-green mb-12 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div class="md:col-span-2 space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Tìm kiếm khách hàng</label>
            <input 
                id="searchTerm"
                type="text" 
                placeholder="Tên khách hàng hoặc Email..." 
                value="{{ request('search') }}"
                onkeydown="if(event.key === 'Enter') { 
                    const url = new URL(window.location.href);
                    if(this.value.trim() === '') {
                        url.searchParams.delete('search');
                    } else {
                        url.searchParams.set('search', this.value);
                    }
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }"
                class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none transition-all rounded-lg text-white"
            />
        </div>
        
        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Trạng thái tài khoản</label>
            <div class="relative">
                <select id="statusFilter" 
                        onchange="
                            const url = new URL(window.location.href);
                            if(this.value === 'all') {
                                url.searchParams.delete('status');
                            } else {
                                url.searchParams.set('status', this.value);
                            }
                            url.searchParams.delete('page');
                            window.location.href = url.toString();
                        "
                        class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer rounded-lg text-gray-300">
                    <option value="all" {{ request('status') === 'all' || !request()->has('status') ? 'selected' : '' }}>TẤT CẢ TRẠNG THÁI</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>ĐANG HOẠT ĐỘNG</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>ĐÃ BỊ KHÓA</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 text-xs">▼</div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="glass-panel overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-high/80 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Khách hàng</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Email</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Trạng thái</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-white/5">
                    @forelse($users as $u)
                    @php
                        $status = strtolower($u->trang_thai ?? 'active');
                        $isActive = ($status === 'active');
                    @endphp
                    <tr class="group hover:bg-white/[0.02] transition-colors"
                        data-name="{{ $u->ho_ten }}"
                        data-email="{{ $u->email }}"
                        data-status="{{ $status }}">
                        
                        <!-- User (Avatar + Name) -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if(!empty($u->avatar_url))
                                    <img src="{{ $u->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full border border-white/10 object-contain">
                                @else
                                    <div class="w-10 h-10 rounded-full border border-white/10 bg-white/5 flex items-center justify-center font-bold text-white uppercase text-sm">
                                        {{ mb_substr($u->ho_ten ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <div class="text-sm font-semibold text-white">{{ $u->ho_ten ?? 'Không có tên' }}</div>
                            </div>
                        </td>
                        
                        <!-- Email -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-mono text-gray-300">{{ $u->email }}</div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            @if($isActive)
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 text-[9px] font-bold uppercase text-neon-green border border-neon-green/50 bg-neon-green/5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                                    hoạt động
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 text-[9px] font-bold uppercase text-rose-500 border border-rose-500/50 bg-rose-500/5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    bị khóa
                                </div>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.user.update', $u) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                @if($isActive)
                                    <input type="hidden" name="trang_thai" value="inactive">
                                    <button type="submit" class="inline-flex items-center gap-1 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-black border border-rose-500/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-all duration-300 rounded-lg">
                                        <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                        <span>Khóa</span>
                                    </button>
                                @else
                                    <input type="hidden" name="trang_thai" value="active">
                                    <button type="submit" class="inline-flex items-center gap-1 bg-neon-green/10 hover:bg-neon-green text-neon-green hover:text-black border border-neon-green/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-all duration-300 rounded-lg">
                                        <i data-lucide="unlock" class="w-3.5 h-3.5"></i>
                                        <span>Mở khóa</span>
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 uppercase tracking-widest font-mono">
                            Không tìm thấy người dùng nào trong hệ thống
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-white/5 bg-surface-high/20">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
