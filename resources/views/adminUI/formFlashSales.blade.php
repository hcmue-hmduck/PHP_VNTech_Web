@extends('layouts.admin')

@section('title', isset($flash_sales) ? 'Cấu hình chi tiết Flash Sale - VNTech' : 'Tạo mới Flash Sale - VNTech')

@section('content')
<div class="w-full">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <a href="{{ route('admin.flashsales.index') }}"
               class="group inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-neon-green transition-colors mb-3 no-underline">
                <i data-lucide="arrow-left" class="size-3 group-hover:-translate-x-0.5 transition-transform"></i>
                <span>Trở lại danh sách</span>
            </a>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)] uppercase leading-none">
                {{ isset($flash_sales) ? 'CẤU HÌNH FLASH SALE' : 'TẠO MỚI FLASH SALE' }}
            </h1>
            @if(isset($flash_sales))
            <div class="mt-3 flex items-center gap-2 text-[10px] font-mono text-gray-400 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-neon-green animate-pulse"></span>
                <span>Mã Flash Sales:</span>
                <span class="text-neon-green font-bold">{{ $flash_sales->ma_flash_sales }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($errors->any())
    <div class="glass-panel p-4 mb-8 border-l-4 border-l-rose-500">
        <div class="flex items-start gap-3 text-rose-400">
            <i data-lucide="alert-triangle" class="size-4 shrink-0 mt-0.5"></i>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em]">Lỗi chuẩn hóa dữ liệu</p>
                <ul class="text-xs mt-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ isset($flash_sales) ? route('admin.flashsales.update', $flash_sales->ma_flash_sales) : route('admin.flashsales.store') }}" class="space-y-6 mb-8">
        @csrf
        @if(isset($flash_sales))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            <!-- Cột chính: Thông tin cơ bản -->
            <div class="lg:col-span-2">
                <div class="glass-panel h-full p-6 lg:p-8 border-l-4 border-l-neon-green relative overflow-hidden flex flex-col">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-neon-green/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    
                    <h2 class="text-lg font-display font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2 shrink-0">
                        <i data-lucide="info" class="text-neon-green size-5"></i>
                        THÔNG TIN FLASH SALES
                    </h2>

                    <div class="space-y-5 flex-1 flex flex-col">
                        <div class="space-y-1.5 relative shrink-0">
                            <label for="ten_flash_sales" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Tên chiến dịch <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="tag" class="size-4 text-gray-500"></i>
                                </div>
                                <input
                                    id="ten_flash_sales" name="ten_flash_sales" type="text"
                                    value="{{ old('ten_flash_sales', $flash_sales->ten_flash_sales ?? '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg"
                                    placeholder="Vd: SIÊU SALE HÈ 2026..."
                                    required
                                />
                            </div>
                        </div>

                        <div class="space-y-1.5 flex-1 flex flex-col">
                            <label for="mo_ta" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 shrink-0">Mô tả nội dung</label>
                            <div class="relative flex-1 flex flex-col">
                                <textarea
                                    id="mo_ta" name="mo_ta"
                                    class="w-full flex-1 min-h-[120px] bg-dark-bg/50 border border-white/10 p-4 text-sm font-mono text-gray-300 focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg resize-none"
                                    placeholder="Nhập chi tiết nội dung, thể lệ để hiển thị cho hệ thống..."
                                >{{ old('mo_ta', $flash_sales->mo_ta ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phụ: Cấu hình thời gian & Trạng thái -->
            <div class="">
                <div class="glass-panel h-full p-6 lg:p-8 relative overflow-hidden flex flex-col">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>

                    <h2 class="text-lg font-display font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2 shrink-0">
                        <i data-lucide="calendar-clock" class="text-yellow-400 size-5"></i>
                        LỊCH TRÌNH & TRẠNG THÁI
                    </h2>

                    <div class="space-y-5 flex-1 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <label for="bat_dau" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Thời điểm bắt đầu <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input
                                    id="bat_dau" name="bat_dau" type="datetime-local"
                                    value="{{ old('bat_dau', isset($flash_sales->bat_dau) ? \Carbon\Carbon::parse($flash_sales->bat_dau)->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 px-4 text-sm font-mono text-white focus:border-yellow-400/50 focus:bg-yellow-400/5 outline-none transition-all rounded-lg dark-theme-date"
                                    required
                                />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="ket_thuc" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Thời điểm kết thúc <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input
                                    id="ket_thuc" name="ket_thuc" type="datetime-local"
                                    value="{{ old('ket_thuc', isset($flash_sales->ket_thuc) ? \Carbon\Carbon::parse($flash_sales->ket_thuc)->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 px-4 text-sm font-mono text-white focus:border-yellow-400/50 focus:bg-yellow-400/5 outline-none transition-all rounded-lg dark-theme-date"
                                    required
                                />
                            </div>
                        </div>

                        <div class="pt-5 border-t border-white/10 mt-auto space-y-1.5">
                            <label for="trang_thai" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Trạng thái phát hành</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="activity" class="size-4 text-gray-500"></i>
                                </div>
                                <select
                                    id="trang_thai" name="trang_thai"
                                    class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-10 text-sm font-mono text-white focus:border-neon-green focus:bg-neon-green/5 outline-none appearance-none cursor-pointer rounded-lg transition-all"
                                >
                                    @php
                                        $currentStatus = strtoupper(old('trang_thai', $flash_sales->trang_thai ?? 'DRAFT'));
                                    @endphp
                                    <option value="DRAFT" {{ $currentStatus === 'DRAFT' ? 'selected' : '' }}>BẢN NHÁP</option>
                                    <option value="ACTIVE" {{ $currentStatus === 'ACTIVE' ? 'selected' : '' }}>ĐANG HOẠT ĐỘNG</option>
                                    <option value="FINISHED" {{$currentStatus ==='FINISHED' ? 'selected' : '' }}>ĐÃ KẾT THÚC</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="size-4 text-gray-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-panel p-6 border-l-4 border-l-neon-green">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-2">Tích hợp sản phẩm</p>
                    <h2 class="text-2xl md:text-3xl font-display font-bold text-white uppercase leading-none">DANH SÁCH SẢN PHẨM FLASH SALE</h2>
                </div>
                <button type="button" onclick="document.getElementById('product-modal').classList.remove('hidden')" class="group flex items-center gap-2 bg-neon-green/10 border border-neon-green text-neon-green px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300 rounded-lg">
                    <i data-lucide="plus" class="size-4 group-hover:scale-110 transition-transform"></i>
                    <span>Thêm sản phẩm</span>
                </button>
            </div>

            @php
                $products = $flash_sale_products ?? collect();
            @endphp

            @if(empty($products) || (is_object($products) && $products->isEmpty()) || (is_array($products) && count($products) == 0))
                <div class="text-center py-16 border border-dashed border-white/15 bg-white/[0.02] rounded-xl">
                    <i data-lucide="inbox" class="mx-auto text-gray-500 mb-3 size-8"></i>
                    <p class="text-sm text-gray-300">Chưa có sản phẩm cấu hình thuộc chiến dịch</p>
                    <p class="text-xs text-gray-500 mt-1 uppercase font-mono">Thêm sản phẩm từ trang quản lý sản phẩm</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($products as $index => $prod)
                        <div class="p-4 rounded-xl border border-white/5 bg-dark-bg/70 transition-all duration-300 hover:border-neon-green/20 group flex flex-col xl:flex-row xl:items-center gap-5">
                            <input type="hidden" name="products[{{ $index }}][ma_bien_the]" value="{{ $prod->ma_bien_the }}">
                            
                            <!-- Thông tin sản phẩm -->
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="w-16 h-16 bg-white p-0.5 border border-white/10 rounded-lg flex items-center justify-center overflow-hidden text-2xl shrink-0">
                                    @if(isset($prod->variant) && $prod->variant->link_anh_bien_the)
                                        <img src="{{ $prod->variant->link_anh_bien_the }}" alt="{{ $prod->variant->ten_bien_the }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="image" class="text-gray-400 size-6"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-white font-bold text-base leading-snug truncate" title="{{ $prod->variant->ten_bien_the ?? 'Sản phẩm không tồn tại' }}">
                                        {{ $prod->variant->ten_bien_the ?? 'Sản phẩm không tồn tại' }}
                                    </h3>
                                    <p class="text-xs font-mono text-gray-500 uppercase tracking-wide mt-1.5 truncate">
                                      GIÁ GỐC: <span class="text-gray-400 line-through">{{ number_format($prod->variant->gia_ban ?? 0, 0, ',', '.') }} ₫</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Input Grid -->
                            <div class="grid grid-cols-2 md:flex md:flex-row gap-4 shrink-0">
                                <div class="space-y-1.5 w-full md:w-36 shrink-0">
                                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 truncate block">Giá flash sale</label>
                                    <div class="relative">
                                        <input
                                          name="products[{{ $index }}][gia_flash_sale]"
                                          type="number"
                                          value="{{ $prod->gia_flash_sale ?? 0 }}"
                                          class="w-full h-11 bg-dark-bg border border-white/10 px-3 pr-8 text-sm font-mono text-neon-green focus:border-neon-green/50 outline-none transition-colors text-right rounded-lg"
                                        />
                                        <span class="absolute right-3 top-3 text-[10px] text-neon-green font-mono">₫</span>
                                    </div>
                                </div>

                                <div class="space-y-1.5 w-full md:w-24 shrink-0">
                                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 truncate block" title="Số lượng giới hạn">SL giới hạn</label>
                                    <input
                                      name="products[{{ $index }}][so_luong_gioi_han]"
                                      type="number"
                                      value="{{ $prod->so_luong_gioi_han ?? 0 }}"
                                      class="w-full h-11 bg-dark-bg border border-white/10 px-2 text-sm font-mono text-white focus:border-neon-green/50 outline-none transition-colors text-center rounded-lg"
                                    />
                                </div>

                                <div class="space-y-1.5 w-full md:w-32 shrink-0">
                                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 truncate block" title="Giới hạn mỗi người">Giới hạn / User</label>
                                    <input
                                      name="products[{{ $index }}][gioi_han_moi_nguoi]"
                                      type="number"
                                      value="{{ $prod->gioi_han_moi_nguoi ?? 0 }}"
                                      class="w-full h-11 bg-dark-bg border border-white/10 px-2 text-sm font-mono text-white focus:border-neon-green/50 outline-none transition-colors text-center rounded-lg"
                                    />
                                </div>

                                <div class="space-y-1.5 w-full md:w-20 shrink-0">
                                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 truncate block">Đã bán</label>
                                    <div class="w-full h-11 bg-white/[0.03] border border-white/10 px-2 flex items-center justify-center text-sm font-mono text-gray-400 rounded-lg">
                                        <span>{{ $prod->so_luong_da_ban ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions & Status -->
                            <div class="flex items-center justify-end gap-2 shrink-0 md:pl-2">
                                <!-- Status Toggle -->
                                <div class="flex flex-col items-center">
                                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-1.5 truncate block">Trạng thái</label>
                                    <div class="h-11 flex items-center justify-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="products[{{ $index }}][trang_thai]" value="DRAFT">
                                            <input type="checkbox" name="products[{{ $index }}][trang_thai]" value="ACTIVE" {{ strtoupper($prod->trang_thai ?? 'DRAFT') === 'ACTIVE' ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-neon-green"></div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="w-px h-10 bg-white/10 mx-2 hidden md:block"></div>

                                <!-- Delete Button -->
                                <div class="flex flex-col items-center">
                                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-1.5 truncate block opacity-0 select-none">Xóa</label>
                                    <div class="h-11 flex items-center justify-center">
                                        <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg text-rose-500 hover:bg-rose-500 hover:text-white transition-colors" title="Xóa sản phẩm">
                                            <i data-lucide="trash-2" class="size-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex flex-col md:flex-row justify-end items-center gap-3">
            <button type="submit" name="action_type" value="DRAFT" class="w-full md:w-auto group flex justify-center items-center gap-3 bg-transparent border-2 border-white/10 text-white px-8 py-3.5 font-bold uppercase tracking-widest hover:bg-white/5 hover:border-neon-green/40 transition-all duration-300 rounded-none">
                <i data-lucide="save" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span>LƯU NHÁP</span>
            </button>
            <button type="submit" name="action_type" value="ACTIVE" class="w-full md:w-auto group flex justify-center items-center gap-3 bg-neon-green text-black border-2 border-neon-green px-8 py-3.5 font-bold uppercase tracking-widest hover:brightness-110 transition-all duration-300 rounded-none">
                <i data-lucide="zap" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span>KÍCH HOẠT</span>
            </button>
        </div>
    </form>
</div>

<!-- Modal Thêm Sản Phẩm -->
<div id="product-modal" class="fixed inset-0 z-[100] hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#111111] border border-white/10 rounded-2xl w-full max-w-3xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden glass-panel">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-white/10 bg-white/[0.02]">
            <h3 class="text-white font-display font-bold text-lg uppercase tracking-wide">Chọn sản phẩm bổ sung</h3>
            <button type="button" onclick="document.getElementById('product-modal').classList.add('hidden')" class="text-gray-400 hover:text-rose-500 transition-colors">
                <i data-lucide="x" class="size-6"></i>
            </button>
        </div>

        <!-- Search & Content -->
        <div class="p-5 flex-1 overflow-y-auto space-y-6 custom-scrollbar">
            <!-- Search -->
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-gray-500"></i>
                <input type="text" id="search-product" onkeyup="filterProducts()" placeholder="Tìm kiếm tên sản phẩm hoặc mã biến thể..." class="w-full h-12 bg-black/40 border border-white/10 rounded-xl pl-12 pr-4 text-sm text-white focus:border-neon-green/50 outline-none transition-colors">
            </div>

            <!-- List -->
            <div class="space-y-4" id="product-list-container">
                @if(isset($productWithVariants) && count($productWithVariants) > 0)
                    @foreach($productWithVariants as $product)
                        <div class="product-item bg-white/[0.02] border border-white/5 rounded-xl overflow-hidden">
                            <div class="p-4 bg-black/30 border-b border-white/5 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg border border-white/10 overflow-hidden bg-white/5 flex items-center justify-center shrink-0">
                                    @if(isset($product->link_anh_dai_dien) && $product->link_anh_dai_dien)
                                        <img src="{{ $product->link_anh_dai_dien }}" alt="{{ $product->ten_san_pham }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="image" class="text-gray-500 size-5"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-white font-bold text-sm product-name">{{ $product->ten_san_pham }}</h4>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">ID: {{ $product->ma_san_pham ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="p-4 space-y-3">
                                @if(isset($product->variants) && count($product->variants) > 0)
                                    @foreach($product->variants as $variant)
                                        <div class="variant-item flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-3 rounded-lg border border-white/5 hover:border-neon-green/30 bg-black/20 transition-colors">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-md border border-white/10 overflow-hidden bg-white flex items-center justify-center shrink-0">
                                                    @if(isset($variant->link_anh_bien_the) && $variant->link_anh_bien_the)
                                                        <img src="{{ $variant->link_anh_bien_the }}" class="w-full h-full object-cover">
                                                    @else
                                                        <i data-lucide="image" class="text-gray-400 size-4"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-white variant-name">{{ $variant->ten_bien_the }}</p>
                                                    <p class="text-[10px] text-neon-green font-mono mt-0.5 variant-code">{{ $variant->ma_bien_the }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4 shrink-0 sm:justify-end">
                                                <p class="text-xs font-mono text-gray-400 line-through">{{ number_format($variant->gia_ban ?? 0, 0, ',', '.') }} ₫</p>
                                                <button type="button" class="bg-neon-green/10 text-neon-green hover:bg-neon-green hover:text-black border border-neon-green px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all">
                                                    Thêm
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-xs text-gray-500 italic text-center">Sản phẩm này không có biến thể nào</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <i data-lucide="box" class="mx-auto text-gray-500 mb-3 size-8"></i>
                        <p class="text-gray-400 text-sm">Chưa có dữ liệu sản phẩm để chọn</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function filterProducts() {
        const query = document.getElementById('search-product').value.toLowerCase();
        const products = document.querySelectorAll('.product-item');
        
        products.forEach(prod => {
            const prodName = prod.querySelector('.product-name').textContent.toLowerCase();
            const variants = prod.querySelectorAll('.variant-item');
            
            let hasVisibleVariant = false;
            
            variants.forEach(variant => {
                const varName = variant.querySelector('.variant-name').textContent.toLowerCase();
                const varCode = variant.querySelector('.variant-code').textContent.toLowerCase();
                
                if (prodName.includes(query) || varName.includes(query) || varCode.includes(query)) {
                    variant.style.display = 'flex';
                    hasVisibleVariant = true;
                } else {
                    variant.style.display = 'none';
                }
            });
            
            // Show product if product name matches or any variant matches
            if (prodName.includes(query) || hasVisibleVariant) {
                prod.style.display = 'block';
            } else {
                prod.style.display = 'none';
            }
        });
    }
</script>

<style>
    /* Custom Scrollbar for Modal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>
@endsection