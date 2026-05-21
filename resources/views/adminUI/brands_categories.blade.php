@extends('layouts.admin')
@section('title', 'Thương hiệu & Danh mục')

@section('content')
@php
	// $brands = $brands ?? collect();
	// $categories = $categories ?? collect();

	$activeBrands = $brands->filter(fn ($brand) => strtolower((string) ($brand->trang_thai ?? '')) === 'active')->count();
	$inactiveBrands = $brands->filter(fn ($brand) => strtolower((string) ($brand->trang_thai ?? '')) !== 'active')->count();
	$activeCategories = $categories->filter(fn ($category) => strtolower((string) ($category->trang_thai ?? '')) === 'active')->count();
	$inactiveCategories = $categories->filter(fn ($category) => strtolower((string) ($category->trang_thai ?? '')) !== 'active')->count();
@endphp

<div class="w-full">
	<!-- Dashboard Header -->
	<div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
		<div>
			<h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)] uppercase">
				HÃNG / DANH MỤC
			</h1>
		</div>

		<div class="flex flex-wrap items-center gap-3">
			<a href="{{ route('admin.brandscategories.index') }}" class="group flex items-center gap-3 bg-transparent border-2 border-neon-green text-neon-green px-6 py-3 font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300">
				<i data-lucide="refresh-cw" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
				<span>TẢI LẠI</span>
			</a>
		</div>
	</div>

		<!-- Modals: Brand & Category -->
        <div id="modalOverlay" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center px-4 transition-all duration-300">
            
            <!-- BRAND MODAL -->
            <div id="brandModal" class="hidden max-w-2xl w-full bg-[#0d0f10]/95 border border-white/10 p-8 relative shadow-[0_0_50px_rgba(0,255,102,0.08)] rounded-xl group">
                <div class="absolute top-0 left-0 w-16 h-[2px] bg-neon-green shadow-[0_0_8px_#00FF66]"></div>
            
                <button id="closeBrandModal" class="absolute top-4 right-4 text-gray-400 hover:text-white">✕</button>
                
                <h3 class="text-lg font-bold mb-4">Thêm Thương Hiệu</h3>

                <form action="{{ route('admin.brandscategories.brand.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ma_thuong_hieu" id="brand_id_hidden" value="{{ $brand->ma_danh_muc ?? '' }}">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        
                        <div class="md:col-span-4 flex flex-col items-center justify-center text-center self-center h-full py-2">
                            <label for="brandLogoInput" class="cursor-pointer group flex flex-col items-center gap-3">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden border border-white/10 bg-black/40 flex items-center justify-center group-hover:border-neon-green/40 transition-all duration-300 relative shadow-inner">
                                    <img id="brandLogoPreview" src="" alt="preview" class="hidden w-full h-full object-cover" />
                                    <span id="brandLogoPlaceholder" class="text-neon-green font-display font-bold text-2xl">A</span>
                                </div>
                                <div class="text-xs text-gray-400 group-hover:text-neon-green transition-colors">Logo</div>
                            </label>
                            <input id="brandLogoInput" name="logo" type="file" accept="image/*" class="hidden" />
                        </div>

                        <div class="md:col-span-8 space-y-4">
                            <div>
                                <label class="block text-xs font-bold mb-1">Tên thương hiệu</label>
                                <input name="ten_thuong_hieu" type="text" required class="w-full px-3 py-2 bg-dark-bg border border-white/10 rounded text-sm text-white outline-none focus:border-neon-green/50 transition-colors" placeholder="VD: Apple" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1">Mô tả</label>
                                <textarea name="mo_ta" rows="3" class="w-full px-3 py-2 bg-dark-bg border border-white/10 rounded text-sm text-white outline-none focus:border-neon-green/50 resize-none transition-colors" placeholder="Mô tả ngắn về thương hiệu..."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1">Trạng thái</label>
                                <div class="relative">
                                    <select name="trang_thai" class="w-full px-3 py-2 bg-[#0d0f10] border border-white/10 rounded text-sm text-white outline-none focus:border-neon-green/50 appearance-none cursor-pointer">
                                        <option value="active">ACTIVE</option>
                                        <option value="inactive">INACTIVE</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 text-xs">▼</div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-12 flex items-center justify-end gap-3 pt-4 border-t border-white/5 mt-2">
                            <button type="button" id="cancelBrand" class="px-4 py-2 bg-transparent border border-white/10 text-white rounded transition-colors">Hủy</button>
                            <button type="submit" class="px-5 py-2 bg-neon-green text-black font-bold rounded shadow-[0_6px_18px_rgba(0,229,91,0.12)] hover:scale-[1.02] active:scale-95 transition-all">Tạo thương hiệu</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- CATEGORY MODAL -->
            <div id="categoryModal" class="hidden max-w-2xl w-full bg-[#0d0f10]/95 border border-white/10 p-8 relative shadow-[0_0_50px_rgba(0,255,102,0.08)] rounded-xl group">
    
                <div class="absolute top-0 left-0 w-16 h-[2px] bg-neon-green shadow-[0_0_8px_#00FF66]"></div>
                
                <button id="closeCategoryModal" class="absolute top-4 right-4 text-gray-400 hover:text-white">✕</button>
                
                <h3 class="text-lg font-bold mb-4">Thêm Danh Mục</h3>

                <form action="{{ route('admin.brandscategories.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ma_danh_muc" id="category_id_hidden" value="{{ $category->ma_danh_muc ?? '' }}">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        
                        <div class="md:col-span-8 space-y-4">
                            <div>
                                <label class="block text-xs font-bold mb-1">Tên danh mục</label>
                                <input name="ten_danh_muc" type="text" required class="w-full px-3 py-2 bg-dark-bg border border-white/10 rounded text-sm text-white outline-none focus:border-neon-green/50 transition-colors" placeholder="VD: Laptop" />
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold mb-1">Danh mục cha</label>
                                    <div class="relative">
                                        <select name="ma_danh_muc_cha" class="w-full px-3 py-2 bg-[#0d0f10] border border-white/10 rounded text-sm text-white outline-none focus:border-neon-green/50 appearance-none cursor-pointer">
                                            <option value="">ROOT</option>
                                            @foreach($categories as $catOpt)
                                                <option value="{{ $catOpt->_id ?? $catOpt->id }}">{{ $catOpt->ten_danh_muc }} ({{ $catOpt->ma_danh_muc ?? $catOpt->_id ?? $catOpt->id }})</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 text-xs">▼</div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold mb-1">Trạng thái</label>
                                    <div class="relative">
                                        <select name="trang_thai" class="w-full px-3 py-2 bg-[#0d0f10] border border-white/10 rounded text-sm text-white outline-none focus:border-neon-green/50 appearance-none cursor-pointer">
                                            <option value="active">ACTIVE</option>
                                            <option value="inactive">INACTIVE</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 text-xs">▼</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-4 flex flex-col items-center justify-center text-center self-center h-full py-2">
                            <label for="categoryLogoInput" class="cursor-pointer group flex flex-col items-center gap-3">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden border border-white/10 bg-black/40 flex items-center justify-center group-hover:border-neon-green/40 transition-all duration-300 relative shadow-inner">
                                    <img id="categoryLogoPreview" src="" alt="preview" class="hidden w-full h-full object-cover" />
                                    <span id="categoryLogoPlaceholder" class="text-neon-green font-display font-bold text-2xl">C</span>
                                </div>
                                <div class="text-xs text-gray-400 group-hover:text-neon-green transition-colors">Logo</div>
                            </label>
                            <input id="categoryLogoInput" name="logo" type="file" accept="image/*" class="hidden" />
                        </div>

                        <div class="md:col-span-12 flex items-center justify-end gap-3 pt-4 border-t border-white/5 mt-2">
                            <button type="button" id="cancelCategory" class="px-4 py-2 bg-transparent border border-white/10 text-white rounded transition-colors">Hủy</button>
                            <button type="submit" class="px-5 py-2 bg-neon-green text-black font-bold rounded shadow-[0_6px_18px_rgba(0,229,91,0.12)] hover:scale-[1.02] active:scale-95 transition-all">Tạo danh mục</button>
                        </div>

                    </div>
                </form>
            </div>
            
        </div>

	<!-- Summary Cards -->
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
		<div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
			<div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
			<div class="flex justify-between items-start z-10">
				<p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng thương hiệu</p>
				<i data-lucide="badge-check" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
			</div>
			<div class="z-10">
				<h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $brands->count() }}</h3>
				<p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Đang quản lý</p>
			</div>
		</div>

		<div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
			<div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
			<div class="flex justify-between items-start z-10">
				<p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Thương hiệu hoạt động</p>
				<i data-lucide="trending-up" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
			</div>
			<div class="z-10">
				<h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $activeBrands }}</h3>
				<p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Active</p>
			</div>
		</div>

		<div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
			<div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
			<div class="flex justify-between items-start z-10">
				<p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng danh mục</p>
				<i data-lucide="layers-3" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
			</div>
			<div class="z-10">
				<h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $categories->count() }}</h3>
				<p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Đang quản lý</p>
			</div>
		</div>

		<div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
			<div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
			<div class="flex justify-between items-start z-10">
				<p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Danh mục hoạt động</p>
				<i data-lucide="folder-tree" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
			</div>
			<div class="z-10">
				<h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $activeCategories }}</h3>
				<p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Active</p>
			</div>
		</div>
	</div>

	<!-- Filters Bar -->
	<div class="glass-panel p-6 border-l-4 border-l-neon-green mb-12 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
		<div class="space-y-1.5">
			<label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Search Brands</label>
			<div class="relative">
				<input
					type="text"
					placeholder="MÃ, TÊN HOẶC MÔ TẢ..."
					class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none transition-colors"
				/>
			</div>
		</div>

		<div class="space-y-1.5">
			<label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Brand Status</label>
			<select class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer">
				<option>TẤT CẢ TRẠNG THÁI</option>
				<option value="active">ACTIVE</option>
				<option value="inactive">INACTIVE</option>
			</select>
		</div>

		<div class="space-y-1.5">
			<label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Category Status</label>
			<select class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer">
				<option>TẤT CẢ TRẠNG THÁI</option>
				<option value="active">ACTIVE</option>
				<option value="inactive">INACTIVE</option>
			</select>
		</div>

		<button class="h-11 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-[0.2em] transition-all">
			Apply Filter
		</button>
	</div>

	<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
		<!-- Brands Table -->
		<div class="glass-panel overflow-hidden" id="brand-section">
			<div class="flex items-center justify-between px-6 py-5 border-b border-white/10 bg-surface-high/40">
				<div>
					<h2 class="font-display text-xl font-bold uppercase text-white">Danh sách thương hiệu</h2>
					<p class="text-[10px] text-gray-500 mt-1 uppercase tracking-[0.2em]">Partner registry</p>
				</div>
				<div class="flex items-center gap-3">
					<button id="openBrandModal" type="button" class="group flex items-center gap-2 bg-neon-green text-black px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:brightness-110 transition-all duration-300 shadow-[0_0_20px_rgba(0,229,91,0.2)]">
						<i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300"></i>
						<span>THÊM THƯƠNG HIỆU</span>
					</button>
					<div class="text-[10px] font-mono text-gray-500 uppercase tracking-widest">
						{{ $brands->count() }} thương hiệu
					</div>
				</div>
			</div>

			<div class="overflow-x-auto">
				<table class="w-full text-left">
					<thead class="bg-surface-high/80 border-b border-white/10">
						<tr>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Logo</th>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Thông tin</th>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Trạng thái</th>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 text-right">Thao tác</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-white/5">
						@forelse($brands as $brand)
							@php
								$brandStatus = strtolower((string) ($brand->trang_thai ?? 'inactive'));
								$brandInitial = strtoupper(mb_substr((string) ($brand->ten_thuong_hieu ?? '-'), 0, 1));
							@endphp
							<tr class="group hover:bg-white/[0.02] transition-colors">
								<td class="px-6 py-4">
									<div class="w-12 h-12 rounded-xl overflow-hidden border border-white/10 bg-black/40 flex items-center justify-center group-hover:border-neon-green/40 transition-all">
										@if(!empty($brand->logo_url))
											<img src="{{ $brand->logo_url }}" alt="{{ $brand->ten_thuong_hieu }}" class="w-full h-full object-cover" />
										@else
											<span class="text-neon-green font-display font-bold text-lg">{{ $brandInitial }}</span>
										@endif
									</div>
								</td>
								<td class="px-6 py-4">
									<div class="text-sm font-semibold group-hover:text-neon-green transition-colors line-clamp-1 uppercase">{{ $brand->ten_thuong_hieu }}</div>
									<div class="text-[9px] font-mono text-gray-500 mt-1 uppercase tracking-wider">ID: {{ $brand->ma_thuong_hieu ?? '#-' }}</div>
									<div class="text-[10px] text-gray-400 mt-2 line-clamp-2">{{ $brand->mo_ta ?: 'Chưa có mô tả' }}</div>
								</td>
								<td class="px-6 py-4">
									<div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase {{ $brandStatus === 'active' ? 'text-neon-green border border-neon-green/50 bg-neon-green/5' : 'text-gray-500 border border-gray-700 bg-white/5' }}">
										<div class="w-1 h-1 rounded-full {{ $brandStatus === 'active' ? 'bg-neon-green animate-pulse' : 'bg-gray-700' }}"></div>
										{{ $brandStatus === 'active' ? 'Active' : 'Inactive' }}
									</div>
								</td>
								<td class="px-6 py-4 text-right">
									<div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
										<button class="p-2 hover:text-neon-green hover:bg-neon-green/10 transition-colors border border-transparent hover:border-white/10 rounded-lg">
											<i data-lucide="eye" class="w-4 h-4"></i>
										</button>
										<button class="p-2 hover:text-blue-400 hover:bg-blue-400/10 transition-colors border border-transparent hover:border-white/10 rounded-lg">
											<i data-lucide="pencil" class="w-4 h-4"></i>
										</button>
										<button class="p-2 hover:text-red-500 hover:bg-red-500/10 transition-colors border border-transparent hover:border-white/10 rounded-lg">
											<i data-lucide="trash-2" class="w-4 h-4"></i>
										</button>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="4" class="px-6 py-8 text-center text-gray-500 uppercase tracking-widest">Chưa có thương hiệu nào</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Categories Table -->
		<div class="glass-panel overflow-hidden" id="category-section">
			<div class="flex items-center justify-between px-6 py-5 border-b border-white/10 bg-surface-high/40">
				<div>
					<h2 class="font-display text-xl font-bold uppercase text-white">Danh sách danh mục</h2>
					<p class="text-[10px] text-gray-500 mt-1 uppercase tracking-[0.2em]">Catalog hierarchy</p>
				</div>
				<div class="flex items-center gap-3">
					<button id="openCategoryModal" type="button" class="group flex items-center gap-2 bg-transparent border-2 border-neon-green text-neon-green px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300">
						<i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300"></i>
						<span>THÊM DANH MỤC</span>
					</button>
					<div class="text-[10px] font-mono text-gray-500 uppercase tracking-widest">
						{{ $categories->count() }} records
					</div>
				</div>
			</div>

			<div class="overflow-x-auto">
				<table class="w-full text-left">
					<thead class="bg-surface-high/80 border-b border-white/10">
						<tr>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Logo</th>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Thông tin</th>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Danh mục cha</th>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Trạng thái</th>
							<th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 text-right">Thao tác</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-white/5">
						@forelse($categories as $category)
							@php
								$categoryStatus = strtolower((string) ($category->trang_thai ?? 'inactive'));
								$categoryInitial = strtoupper(mb_substr((string) ($category->ten_danh_muc ?? '-'), 0, 1));
							@endphp
							<tr class="group hover:bg-white/[0.02] transition-colors">
								<td class="px-6 py-4">
									<div class="w-12 h-12 rounded-xl overflow-hidden border border-white/10 bg-black/40 flex items-center justify-center group-hover:border-neon-green/40 transition-all">
										@if(!empty($category->logo_url))
											<img src="{{ $category->logo_url }}" alt="{{ $category->ten_danh_muc }}" class="w-full h-full object-cover" />
										@else
											<span class="text-neon-green font-display font-bold text-lg">{{ $categoryInitial }}</span>
										@endif
									</div>
								</td>
								<td class="px-6 py-4">
									<div class="text-sm font-semibold group-hover:text-neon-green transition-colors line-clamp-1 uppercase">{{ $category->ten_danh_muc }}</div>
									<div class="text-[9px] font-mono text-gray-500 mt-1 uppercase tracking-wider">ID: {{ $category->ma_danh_muc ?? '#-' }}</div>
								</td>
								<td class="px-6 py-4">
									<div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase border bg-white/5 text-gray-400 border-gray-700">
										{{ $category->ma_danh_muc_cha ?: 'ROOT' }}
									</div>
								</td>
								<td class="px-6 py-4">
									<div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase {{ $categoryStatus === 'active' ? 'text-neon-green border border-neon-green/50 bg-neon-green/5' : 'text-gray-500 border border-gray-700 bg-white/5' }}">
										<div class="w-1 h-1 rounded-full {{ $categoryStatus === 'active' ? 'bg-neon-green animate-pulse' : 'bg-gray-700' }}"></div>
										{{ $categoryStatus === 'active' ? 'Active' : 'Inactive' }}
									</div>
								</td>
								<td class="px-6 py-4 text-right">
									<div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
										<button class="p-2 hover:text-neon-green hover:bg-neon-green/10 transition-colors border border-transparent hover:border-white/10 rounded-lg">
											<i data-lucide="eye" class="w-4 h-4"></i>
										</button>
										<button class="p-2 hover:text-blue-400 hover:bg-blue-400/10 transition-colors border border-transparent hover:border-white/10 rounded-lg">
											<i data-lucide="pencil" class="w-4 h-4"></i>
										</button>
										<button class="p-2 hover:text-red-500 hover:bg-red-500/10 transition-colors border border-transparent hover:border-white/10 rounded-lg">
											<i data-lucide="trash-2" class="w-4 h-4"></i>
										</button>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="px-6 py-8 text-center text-gray-500 uppercase tracking-widest">Chưa có danh mục nào</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>

    
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
	const overlay = document.getElementById('modalOverlay');

	const brandModal = document.getElementById('brandModal');
	const openBrandBtn = document.getElementById('openBrandModal');
	const closeBrandBtn = document.getElementById('closeBrandModal');
	const cancelBrand = document.getElementById('cancelBrand');

	const categoryModal = document.getElementById('categoryModal');
	const openCategoryBtn = document.getElementById('openCategoryModal');
	const closeCategoryBtn = document.getElementById('closeCategoryModal');
	const cancelCategory = document.getElementById('cancelCategory');

	function showModal(which) {
		overlay.classList.remove('hidden');
		if (which === 'brand') {
			brandModal.classList.remove('hidden');
			categoryModal.classList.add('hidden');
		} else {
			categoryModal.classList.remove('hidden');
			brandModal.classList.add('hidden');
		}
	}

	function hideModal() {
		overlay.classList.add('hidden');
		brandModal.classList.add('hidden');
		categoryModal.classList.add('hidden');
	}

	openBrandBtn?.addEventListener('click', () => showModal('brand'));
	// Logo preview handling
	const brandLogoInput = document.getElementById('brandLogoInput');
	const brandLogoPreview = document.getElementById('brandLogoPreview');
	const brandLogoPlaceholder = document.getElementById('brandLogoPlaceholder');

	brandLogoInput?.addEventListener('change', function (e) {
		const file = this.files && this.files[0];
		if (!file) {
			brandLogoPreview.classList.add('hidden');
			brandLogoPlaceholder.classList.remove('hidden');
			return;
		}
		brandLogoPlaceholder.classList.add('hidden');
		brandLogoPreview.src = URL.createObjectURL(file);
		brandLogoPreview.classList.remove('hidden');
	});
	// Category logo preview
	const categoryLogoInput = document.getElementById('categoryLogoInput');
	const categoryLogoPreview = document.getElementById('categoryLogoPreview');
	const categoryLogoPlaceholder = document.getElementById('categoryLogoPlaceholder');

	categoryLogoInput?.addEventListener('change', function (e) {
		const file = this.files && this.files[0];
		if (!file) {
			categoryLogoPreview.classList.add('hidden');
			categoryLogoPlaceholder.classList.remove('hidden');
			return;
		}
		categoryLogoPlaceholder.classList.add('hidden');
		categoryLogoPreview.src = URL.createObjectURL(file);
		categoryLogoPreview.classList.remove('hidden');
	});
	openCategoryBtn?.addEventListener('click', () => showModal('category'));
	closeBrandBtn?.addEventListener('click', hideModal);
	closeCategoryBtn?.addEventListener('click', hideModal);
	cancelBrand?.addEventListener('click', hideModal);
	cancelCategory?.addEventListener('click', hideModal);

	overlay?.addEventListener('click', function (e) {
		if (e.target === overlay) hideModal();
	});
});
</script>
@endpush