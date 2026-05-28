@extends('layouts.admin')

@section('title', 'Quản lý Flash Sales - VNTech')

@section('content')
@php
    // Tính toán số liệu thống kê trực tiếp từ Collection bằng PHP thuần (Blade)
    $flash_sales = $flash_sales ?? collect();
    $totalCampaigns = $flash_sales->count();
    
    $liveCampaigns = $flash_sales->filter(function($c) {
        return in_array(strtolower($c->trang_thai ?? ''), ['live', 'active', 'đang hoạt động']);
    })->count();

    $scheduledCampaigns = $flash_sales->filter(function($c) {
        return in_array(strtolower($c->trang_thai ?? ''), ['scheduled', 'upcoming', 'sắp diễn ra', 'draft', 'bản nháp']);
    })->count();

    $endedCampaigns = $flash_sales->filter(function($c) {
        return in_array(strtolower($c->trang_thai ?? ''), ['ended', 'expired', 'đã kết thúc', 'finished']);
    })->count();
@endphp

<div class="w-full">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)]">
                QUẢN LÝ FLASH SALES
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.flashsales.index') }}" class="group flex items-center gap-3 bg-transparent border-2 border-neon-green text-neon-green px-6 py-3 font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300">
                <i data-lucide="refresh-cw" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
                <span>TẢI LẠI</span>
            </a>
            <a href="{{ route('admin.flashsales.create') }}" class="group flex items-center gap-3 bg-neon-green text-black border-2 border-neon-green px-6 py-3 font-bold uppercase tracking-widest hover:brightness-110 transition-all duration-300">
                <i data-lucide="plus" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
                <span>TẠO FLASH SALES</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng chiến dịch</p>
                <i data-lucide="bar-chart-3" class="size-5 text-gray-400 opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalCampaigns }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Hệ thống ghi nhận</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Đang hoạt động</p>
                <i data-lucide="radio" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity animate-pulse"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-neon-green tracking-tight leading-tight">{{ $liveCampaigns }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Live Now</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-400/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Sắp diễn ra</p>
                <i data-lucide="calendar" class="size-5 text-yellow-400 opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-yellow-400 tracking-tight leading-tight">{{ $scheduledCampaigns }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Scheduled</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Đã kết thúc</p>
                <i data-lucide="history" class="size-5 text-gray-500 opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-gray-400 tracking-tight leading-tight">{{ $endedCampaigns }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Archived operations</p>
            </div>
        </div>
    </div>

    <form action="/" method="GET" class="glass-panel p-6 border-l-4 border-l-neon-green mb-12 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Search Campaigns</label>
            <div class="relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="MÃ HOẶC TÊN CHIẾN DỊCH..."
                    class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono text-white focus:border-neon-green/50 outline-none transition-colors"
                />
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Status</label>
            <select name="status" class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono text-white focus:border-neon-green/50 outline-none appearance-none cursor-pointer">
                <option value="">TẤT CẢ TRẠNG THÁI</option>
                <option value="LIVE" {{ request('status') == 'LIVE' ? 'selected' : '' }}>LIVE NOW</option>
                <option value="SCHEDULED" {{ request('status') == 'SCHEDULED' ? 'selected' : '' }}>SCHEDULED</option>
                <option value="ENDED" {{ request('status') == 'ENDED' ? 'selected' : '' }}>ENDED</option>
            </select>
        </div>

        <div class="flex gap-4">
            <a href="/" class="flex-1 h-11 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-[0.2em] transition-all flex items-center justify-center">
                Clear Filters
            </a>
            <button type="submit" class="flex-1 h-11 bg-neon-green text-black text-[10px] font-bold uppercase tracking-[0.2em] transition-all hover:brightness-110">
                Apply Filter
            </button>
        </div>
    </form>

    <div class="space-y-4 mb-8">
        @forelse($flash_sales as $campaign)
            @php
                $status = strtolower($campaign->trang_thai ?? 'draft');
                $isLive = $status === 'live' || $status === 'active';
                $isScheduled = $status === 'scheduled' || $status === 'upcoming' || $status === 'draft';
                $isEnded = $status === 'ended' || $status === 'expired' || $status === 'finished';
                
                $start = substr($campaign->bat_dau, 0, 16);
                $end = substr($campaign->ket_thuc, 0, 16);
            @endphp

            <div class="glass-panel p-5 rounded-xl flex flex-col lg:flex-row items-center gap-6 transition-all duration-500 relative overflow-hidden group border border-white/5 
                {{ $isLive ? 'border-neon-green/30 hover:border-neon-green shadow-[0_0_15px_rgba(0,229,91,0.05)] hover:shadow-[0_0_20px_rgba(0,229,91,0.15)]' : '' }}
                {{ $isEnded ? 'opacity-70 grayscale hover:grayscale-0 hover:opacity-100' : '' }}">
                
                <div class="absolute top-0 left-0 w-1.5 h-full 
                    {{ $isLive ? 'bg-neon-green' : ($isScheduled ? 'bg-yellow-400' : 'bg-gray-600') }}">
                </div>

                <div class="w-full lg:w-32 h-32 rounded-lg border border-white/10 shrink-0 bg-dark-bg flex items-center justify-center relative">
                    <i data-lucide="zap" class="size-10 {{ $isLive ? 'text-neon-green drop-shadow-[0_0_10px_rgba(0,229,91,0.5)]' : ($isScheduled ? 'text-yellow-400 drop-shadow-[0_0_10px_rgba(250,204,21,0.5)]' : 'text-gray-600') }} opacity-80 group-hover:scale-110 transition-transform duration-500"></i>
                    
                    @if($isLive)
                        <div class="absolute top-2 right-2 px-1.5 py-0.5 rounded-full bg-dark-bg/80 backdrop-blur-md border border-neon-green/20 text-[8px] font-mono text-neon-green flex items-center gap-1">
                            <span class="w-1 h-1 rounded-full bg-neon-green animate-pulse"></span>
                            LIVE
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0 w-full">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        @if($isLive)
                            <span class="px-2.5 py-0.5 rounded-full bg-neon-green/10 text-neon-green text-[10px] font-bold border border-neon-green/20 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                                LIVE NOW
                            </span>
                        @elseif($isScheduled)
                            <span class="px-2.5 py-0.5 rounded-full bg-yellow-400/10 text-yellow-400 text-[10px] font-bold border border-yellow-400/20 uppercase">
                                SCHEDULED
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-surface-high text-gray-400 text-[10px] font-bold border border-white/10 uppercase">
                                ENDED
                            </span>
                        @endif
                        
                        <span class="text-gray-500 font-mono text-xs uppercase tracking-tighter">
                            ID: {{ $campaign->ma_flash_sales }}
                        </span>
                    </div>

                    <h4 class="font-display text-lg lg:text-xl text-white font-bold group-hover:text-neon-green transition-colors truncate">
                        {{ $campaign->ten_flash_sales }}
                    </h4>

                    <div class="flex flex-col gap-1.5 mt-3 text-gray-400">
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="size-4 text-gray-500"></i>
                            <span class="text-xs font-medium font-mono text-gray-300">
                                {{ $isEnded ? "Đã kết thúc ( $start - $end )" : "$start - $end" }}
                            </span>
                        </div>

                        <div class="flex items-start gap-2">
                            <i data-lucide="file-text" class="size-4 text-gray-500 shrink-0 mt-0.5"></i>
                            <span class="text-xs font-medium text-gray-400 line-clamp-2">
                                {{ $campaign->mo_ta ?: 'Không có mô tả cho chiến dịch này.' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full lg:w-auto shrink-0 border-t lg:border-t-0 lg:border-l border-white/10 pt-4 lg:pt-0 lg:pl-6 justify-between lg:justify-end">

                    <div class="flex items-center gap-2">
                        @if($isEnded)
                            <a href="/" class="py-1.5 px-6 rounded-lg border border-white/20 text-gray-300 font-bold hover:bg-white/10 hover:text-white transition-all duration-300 uppercase tracking-wider text-[11px] font-display">
                                BÁO CÁO
                            </a>
                        @else
                            <a href="{{ route('admin.flashsales.edit', $campaign->ma_flash_sales) }}" class="py-1.5 px-6 rounded-lg border border-neon-green text-neon-green font-bold hover:bg-neon-green hover:text-black transition-all duration-300 uppercase tracking-wider text-[11px] font-display">
                                QUẢN LÝ
                            </a>
                        @endif

                        <div class="relative" x-data="{ showDropdown: false }">
                            <button @click="showDropdown = !showDropdown" @click.away="showDropdown = false" class="p-2 rounded-lg bg-surface-high border border-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors cursor-pointer" title="More Actions">
                                <i data-lucide="more-vertical" class="size-4"></i>
                            </button>

                            <div x-show="showDropdown" style="display: none;" class="absolute right-0 mt-2 w-48 bg-surface-high border border-white/10 rounded-xl shadow-2xl py-1 z-30 animate-in fade-in slide-in-from-top-2 duration-150">
                                
                                <a href="{{ route('admin.flashsales.edit', $campaign->ma_flash_sales) }}" class="w-full text-left px-4 py-2.5 text-xs text-gray-200 hover:bg-white/5 hover:text-neon-green flex items-center gap-2 transition-colors">
                                    <i data-lucide="edit-2" class="size-3.5"></i> Sửa thông tin
                                </a>

                                <form action="/" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-gray-200 hover:bg-white/5 hover:text-neon-green flex items-center gap-2 transition-colors">
                                        <i data-lucide="copy" class="size-3.5"></i> Nhân bản chiến dịch
                                    </button>
                                </form>

                                <a href="/" class="w-full text-left px-4 py-2.5 text-xs text-gray-200 hover:bg-white/5 hover:text-neon-green flex items-center gap-2 transition-colors">
                                    <i data-lucide="bar-chart-2" class="size-3.5"></i> Xem báo cáo chi tiết
                                </a>

                                <form action="" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chiến dịch này không?');" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-red-400 hover:bg-red-500/10 flex items-center gap-2 border-t border-white/10 mt-1 transition-colors">
                                        <i data-lucide="trash-2" class="size-3.5"></i> Xoá chiến dịch
                                    </button>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-panel p-16 text-center rounded-xl space-y-4">
                <i data-lucide="inbox" class="size-12 text-gray-600 mx-auto"></i>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Không tìm thấy chiến dịch Flash Sale nào trong hệ thống</p>
            </div>
        @endforelse
    </div>

    @if(method_exists($flash_sales, 'hasPages') && $flash_sales->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center gap-6 py-4">
            <div class="text-[10px] font-mono text-gray-500 uppercase tracking-widest">
                Displaying <span class="text-neon-green font-bold">{{ $flash_sales->firstItem() }} - {{ $flash_sales->lastItem() }}</span> of <span class="text-gray-300">{{ $flash_sales->total() }}</span> Records Identified
            </div>
            <div class="flex items-center gap-1">
                {{ $flash_sales->links('pagination::tailwind') }}
            </div>
        </div>
    @endif
</div>
@endsection