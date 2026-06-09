@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm - VNTech')

@section('content')

<div class="w-full">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)]">
                QUẢN LÝ SẢN PHẨM
            </h1>
        </div>
        <a href="{{ route('admin.products.create') }}" class="group flex items-center gap-3 bg-transparent border-2 border-neon-green text-neon-green px-6 py-3 font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300">
            <i data-lucide="plus" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
            <span>THÊM SẢN PHẨM MỚI</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng sản phẩm</p>
                <i data-lucide="package" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalProducts }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Đang quản lý</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Đang mở bán</p>
                <i data-lucide="shopping-bag" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $activeProducts }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Đang hoạt động</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Cảnh báo tồn kho</p>
                <i data-lucide="alert-triangle" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $lowStockProducts }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Biến thể sắp hết hàng (số lượng tồn <= 20)</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng giá trị kho</p>
                <i data-lucide="banknote" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ number_format($inventoryValue, 0, ',', '.') }} VNĐ</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Giá trị vốn hàng hóa</p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="glass-panel p-6 border-l-4 border-l-neon-green mb-12 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Tìm kiếm sản phẩm</label>
            <div class="relative">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tên sản phẩm..." 
                    class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none transition-colors"
                />
            </div>
        </div>
        
        <div class="space-y-1.5">
            <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Danh mục</label>
            <select name="category" class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer">
                <option value="">TẤT CẢ DANH MỤC</option>
                @foreach($categories as $category)
                    <option value="{{ $category->ma_danh_muc }}" {{ request('category') == $category->ma_danh_muc ? 'selected' : '' }}>{{ mb_strtoupper($category->ten_danh_muc, 'UTF-8') }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <button type="submit" class="h-11 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>Áp dụng</span>
            </button>
            <a href="{{ route('admin.products.index') }}" class="h-11 bg-red-500/10 border border-red-500/20 hover:bg-red-500/25 text-red-400 hover:text-red-300 flex items-center justify-center text-[10px] font-bold uppercase tracking-[0.2em] transition-all gap-1.5">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Xóa lọc</span>
            </a>
        </div>
    </form>

    <!-- Product Table -->
    <div class="glass-panel overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-high/80 border-b border-white/10">
                    <tr>
                        <th class="w-[10%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono text-center">Ảnh</th>
                        <th class="w-[40%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Thông tin sản phẩm</th>
                        <th class="w-[15%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Giá bán (VNĐ)</th>
                        <th class="w-[12%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Tồn kho</th>
                        <th class="w-[10%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Lượt xem</th>
                        <th class="w-[10%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono">Trạng thái</th>
                        <th class="w-[3%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 font-mono text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($products as $product)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-5 text-center">
                            <div class="w-20 h-20 bg-black/40 border border-white/10 p-1 group-hover:border-neon-green/40 transition-all mx-auto">
                                <img 
                                    src="{{ $product->link_anh_dai_dien ?: asset('images/no-image.png') }}" 
                                    alt="{{ $product->ten_san_pham }}" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-semibold text-white group-hover:text-neon-green transition-colors line-clamp-2 leading-snug">{{ $product->ten_san_pham }}</div>
                            <div class="text-[9px] font-mono text-gray-500 mt-2 uppercase tracking-wider">Mã SP: {{ $product->ma_san_pham }}</div>
                        </td>
                        <td class="px-6 py-5">
                            @php 
                                $displayPrice = $product->gia_thap_nhat;
                                $originalPrice = $product->gia_niem_yet ?? 0;
                            @endphp
                            <div class="text-sm font-mono font-bold text-neon-green">{{ number_format($displayPrice, 0, ',', '.') }}₫</div>
                            @if($originalPrice > 0)
                                <div class="text-[10px] text-gray-500 line-through font-mono mt-1">{{ number_format($originalPrice, 0, ',', '.') }}₫</div>
                            @endif
                        </td>
                        <td class="px-6 py-5 w-44">
                            <div class="flex items-center gap-3.5">
                                @php 
                                    $stock = $product->variants->sum('so_luong_ton_kho');
                                @endphp
                                <div class="flex-1 h-2 bg-dark-bg/60 rounded-full overflow-hidden">
                                    <div 
                                        class="h-full {{ $stock < 20 ? 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]' : 'bg-neon-green shadow-[0_0_8px_rgba(0,229,91,0.5)]' }} transition-all duration-1000" 
                                        @style(['width' => min(100, ($stock / 200) * 100) . '%'])
                                    ></div>
                                </div>
                                <span class="text-[10px] font-mono font-bold {{ $stock < 20 ? 'text-red-500' : 'text-gray-300' }}">{{ $stock }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2 text-[10px] font-mono text-gray-400">
                                <i data-lucide="eye" class="w-4 h-4 text-gray-500"></i>
                                {{ number_format($product->luot_xem ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php 
                                $status = strtolower($product->trang_thai ?? '');
                                $isActive = ($status === 'active'); 
                            @endphp
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 text-[9px] font-bold uppercase {{ $isActive ? 'text-neon-green border border-neon-green/50 bg-neon-green/5' : 'text-gray-500 border border-gray-700 bg-white/5' }}">
                                <div class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-neon-green animate-pulse' : 'bg-gray-700' }}"></div>
                                {{ $isActive ? 'active' : 'inactive' }}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2.5">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2.5 text-gray-400 hover:text-blue-400 hover:bg-blue-400/10 transition-colors border border-transparent hover:border-white/10 rounded-lg" title="Chỉnh sửa">
                                    <i data-lucide="pencil" class="w-4.5 h-4.5"></i>
                                </a>
                                <form action="{{ route('admin.product.delete', $product) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-500/10 transition-colors border border-transparent hover:border-white/10 rounded-lg" title="Xóa">
                                        <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $products->links('pagination::tailwind') }}
    </div>
</div>
@endsection