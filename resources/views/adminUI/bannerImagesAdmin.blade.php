@extends('layouts.admin')

@section('title', 'Quản lý Banner - VNTech')

@section('content')
@php
    $totalBanners = $banner_images->count();
    $activeBanners = $banner_images->filter(fn($b) => $b->trang_thai && $b->trang_thai !== 'deleted')->count();
    $deletedBanners = $banner_images->filter(fn($b) => $b->trang_thai === 'deleted')->count();
@endphp

<div class="w-full">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)]">
                QUẢN LÝ BANNER
            </h1>
            <p class="text-[10px] font-mono text-gray-500 uppercase tracking-widest mt-2">
                Hệ thống quản lý banner quảng cáo hiển thị trên trang chủ của VNTech
            </p>
        </div>
        <div>
            <a href="{{ route('admin.banner.create') }}" class="inline-flex items-center gap-2 bg-neon-green hover:bg-neon-green/90 text-black px-6 py-3 text-xs font-bold uppercase tracking-widest transition-all duration-300 rounded-lg shadow-[0_0_15px_rgba(0,229,91,0.2)]">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Thêm Banner mới</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Total Banners -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Tổng banner</p>
                <i data-lucide="image" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalBanners }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Tất cả banner trong hệ thống</p>
            </div>
        </div>

        <!-- Active Banners -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Đang hoạt động</p>
                <i data-lucide="eye" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $activeBanners }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Hiển thị trên trang khách hàng</p>
            </div>
        </div>

        <!-- Deleted Banners -->
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase font-mono">Đã xoá tạm thời</p>
                <i data-lucide="trash" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-3xl font-display font-bold text-white tracking-tight leading-tight">{{ $deletedBanners }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Lịch sử banner đã ẩn/xoá</p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="glass-panel p-6 border-l-4 border-l-neon-green mb-12">
        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Tìm kiếm tiêu đề hoặc liên kết</label>
            <input 
                id="searchBanner"
                type="text" 
                placeholder="Nhập tiêu đề hoặc link liên kết..." 
                class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none transition-all rounded-lg text-white"
            />
        </div>
    </div>



    <!-- Banners Table -->
    <div class="glass-panel overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-high/80 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Hình ảnh</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Thông tin Banner</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Thứ tự</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="bannerTableBody" class="divide-y divide-white/5">
                    @forelse($banner_images as $b)
                    @php
                        $isDeleted = ($b->trang_thai === 'deleted');
                    @endphp
                    <tr class="group hover:bg-white/[0.02] transition-colors {{ $isDeleted ? 'opacity-50' : '' }}"
                        data-title="{{ $b->tieu_de }}"
                        data-link="{{ $b->lien_ket }}">
                        
                        <!-- Image Preview -->
                        <td class="px-6 py-4">
                            <div class="w-32 h-16 bg-white/5 border border-white/10 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                                @if(!empty($b->image_url))
                                    <img src="{{ $b->image_url }}" alt="Banner" class="w-full h-full object-cover">
                                @else
                                    <div class="text-[9px] text-gray-500 uppercase tracking-widest font-mono">Không có ảnh</div>
                                @endif
                            </div>
                        </td>

                        <!-- Info -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <h4 class="text-sm font-semibold text-white">{{ $b->tieu_de ?? 'Không có tiêu đề' }}</h4>
                                <p class="text-xs text-gray-400 font-medium truncate max-w-xs">{{ $b->mo_ta ?? 'Không có mô tả' }}</p>
                                @if(!empty($b->lien_ket))
                                    <a href="{{ $b->lien_ket }}" target="_blank" class="text-[10px] text-neon-green hover:underline font-mono inline-flex items-center gap-1">
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                        <span>{{ $b->lien_ket }}</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-gray-600 font-mono">Không có liên kết</span>
                                @endif
                            </div>
                        </td>

                        <!-- Display Order -->
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold font-mono text-white">{{ $b->thu_tu_hien_thi ?? 0 }}</span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2.5">
                                @if(!$isDeleted)
                                    <a href="{{ route('admin.banner.edit', $b->ma_banner) }}" class="inline-flex items-center gap-1 bg-neon-green/10 hover:bg-neon-green text-neon-green hover:text-black border border-neon-green/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-all duration-300 rounded-lg">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                        <span>Sửa</span>
                                    </a>
                                    
                                    <form action="{{ route('admin.banner.delete', $b->ma_banner) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xoá banner này không?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="inline-flex items-center gap-1 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-black border border-rose-500/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition-all duration-300 rounded-lg">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Xoá</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-rose-500 border border-rose-500/30 bg-rose-500/5 px-2 py-1 rounded font-bold uppercase tracking-widest font-mono">Đã xoá</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 uppercase tracking-widest font-mono">
                            Không tìm thấy banner nào trong hệ thống
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const searchInput = document.getElementById('searchBanner');
        const tableBody = document.getElementById('bannerTableBody');
        const rows = tableBody ? tableBody.querySelectorAll('tr[data-title]') : [];

        function filterBanners() {
            const query = searchInput.value.toLowerCase().trim();

            rows.forEach(row => {
                const title = (row.getAttribute('data-title') || '').toLowerCase();
                const link = (row.getAttribute('data-link') || '').toLowerCase();

                const matchesQuery = query === '' || title.includes(query) || link.includes(query);

                if (matchesQuery) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        if (searchInput) searchInput.addEventListener('input', filterBanners);
    });
</script>
@endpush
