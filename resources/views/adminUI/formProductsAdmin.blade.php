@extends('layouts.admin')

@section('title', isset($product) ? 'Chỉnh sửa sản phẩm - VNTech' : 'Thêm sản phẩm mới - VNTech')

@section('content')
<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-section { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
    .glass-panel { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .glass-panel:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.05); border-color: rgba(0, 229, 91, 0.3); }

    input, textarea, select { transition: all 0.3s ease; }
    input:focus, textarea:focus, select:focus {
        background: rgba(0, 229, 91, 0.05) !important;
        box-shadow: 0 0 15px rgba(0, 229, 91, 0.1) !important;
        transform: scale(1.01);
    }

    /* Custom Quill */
    .ql-toolbar.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; background: #1a1a1a !important; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    .ql-container.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; background: white !important; min-height: 350px; font-size: 16px; }
    .ql-editor { color: #000000 !important; }
    .ql-snow .ql-stroke { stroke: #ddd !important; }
    .ql-snow .ql-fill { fill: #ddd !important; }
    .ql-snow .ql-picker { color: #ddd !important; }

    /* Fluid Typography & Neon Header */
    .text-clamp-lg { font-size: clamp(1.8rem, 4vw, 2.6rem); }
    .text-neon-green { color: #00e55b; }
    .bg-neon-green { background-color: #00e55b; }
    
    .image-preview-slot { position: relative; aspect-ratio: 1/1; background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); overflow: hidden; }
    .image-preview-slot img { width: 100%; height: 100%; object-fit: cover; }
    .remove-img-btn { position: absolute; top: 5px; right: 5px; background: rgba(255,0,0,0.8); color: white; border-radius: 50%; padding: 2px; cursor: pointer; }
</style>

@php
    // Khởi tạo an toàn: Ưu tiên dữ liệu cũ -> dữ liệu sản phẩm -> mặc định 1 dòng trống
    $techSpecs = old('thong_so_ky_thuat_chung', (isset($product) && isset($product->thong_so_ky_thuat_chung)) ? $product->thong_so_ky_thuat_chung : [['ten' => '', 'gia_tri' => '']]);
    if (!is_array($techSpecs)) $techSpecs = [['ten' => '', 'gia_tri' => '']];

    $moreInfo = old('thong_tin_them', (isset($product) && isset($product->thong_tin_them)) ? $product->thong_tin_them : [['ten' => '', 'gia_tri' => '']]);
    if (!is_array($moreInfo)) $moreInfo = [['ten' => '', 'gia_tri' => '']];
@endphp

<form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" 
      method="POST" id="createProductForm" class="pb-32 px-2 md:px-6 w-full" enctype="multipart/form-data"
      data-is-edit="{{ isset($product) ? 'true' : 'false' }}"
      data-spec-count="{{ count($techSpecs) }}"
      data-info-count="{{ count($moreInfo) }}"
      data-variant-count="{{ isset($product_variant) ? $product_variant->count() : 0 }}">
    @csrf
    @if(isset($product))
        @method('PUT')
        <input type="hidden" name="ma_san_pham" value="{{ $product->ma_san_pham }}">
    @endif
    
    <header class="mb-8 animate-section" style="animation-delay: 0.1s">
        <h1 class="text-clamp-lg font-display font-black text-white uppercase tracking-tighter leading-tight">
            {{ isset($product) ? 'CẬP NHẬT' : 'THÊM' }} SẢN PHẨM <span class="text-neon-green italic drop-shadow-[0_0_10px_rgba(0,229,91,0.5)]">{{ isset($product) ? 'CHỈNH SỬA' : 'MỚI' }}</span>
        </h1>
        <div class="h-1 w-20 bg-gradient-to-r from-neon-green to-transparent mt-3"></div>
    </header>

    <div class="space-y-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Cột trái - Giờ là Full Width -->
            <div class="lg:col-span-12 space-y-12">
                <section class="glass-panel p-6 md:p-10 rounded-3xl border border-white/5 bg-surface/20 animate-section" style="animation-delay: 0.2s">
                    <div class="flex items-center gap-4 mb-10">
                        <i data-lucide="database" class="text-neon-green size-6"></i>
                        <h2 class="font-display text-2xl font-bold uppercase text-white">Thông tin cơ bản</h2>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                        <!-- Cột trái: Thông tin chữ, Giá & Trạng thái (Chiếm 8 phần) -->
                        <div class="lg:col-span-8 space-y-6">
                            <div class="group space-y-3">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Tên sản phẩm</label>
                                <input name="ten_san_pham" id="ten_san_pham" value="{{ old('ten_san_pham', $product->ten_san_pham ?? '') }}" required class="w-full bg-white/[0.03] border border-white/10 p-5 text-white text-lg font-display uppercase tracking-widest rounded-2xl" placeholder="Nhập tên thiết bị..." />
                            </div>

                            <!-- Gộp hàng: Mặc định 1 cột trên Mobile, tự chia 2 cột từ màn hình MD trở lên -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- KHỐI CHỌN THƯƠNG HIỆU -->
                                <div class="group space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Thương hiệu</label>
                                    <div class="relative">
                                        <select id="brand" name="ma_thuong_hieu" class="w-full bg-white/[0.03] border border-white/10 p-5 pr-12 text-white text-sm font-display uppercase tracking-widest rounded-2xl outline-none focus:border-neon-green/50 appearance-none cursor-pointer">
                                            <option value="" class="bg-[#0d0f10] text-gray-400">Không có / Mặc định</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->ma_thuong_hieu }}" class="bg-[#0d0f10] text-white"
                                                    {{ old('ma_thuong_hieu', $product->ma_thuong_hieu ?? '') == $brand->ma_thuong_hieu ? 'selected' : '' }}>
                                                    {{ $brand->ten_thuong_hieu }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-gray-500 text-xs">▼</div>
                                    </div>
                                </div>

                                <!-- KHỐI CHỌN DANH MỤC -->
                                <div class="group space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Danh mục</label>
                                    <div class="relative">
                                        <select id="category" name="ma_danh_muc" class="w-full bg-white/[0.03] border border-white/10 p-5 pr-12 text-white text-sm font-display uppercase tracking-widest rounded-2xl outline-none focus:border-neon-green/50 appearance-none cursor-pointer">
                                            <option value="" class="bg-[#0d0f10] text-gray-400">Không có / Mặc định</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->ma_danh_muc }}" class="bg-[#0d0f10] text-white"
                                                    {{ old('ma_danh_muc', $product->ma_danh_muc ?? '') == $cat->ma_danh_muc ? 'selected' : '' }}>
                                                    {{ $cat->ten_danh_muc }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-gray-500 text-xs">▼</div>
                                    </div>
                                </div>

                            </div>

                            <div class="group space-y-3">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Mô tả ngắn</label>
                                <input name="mo_ta_ngan" value="{{ old('mo_ta_ngan', $product->mo_ta_ngan ?? '') }}" class="w-full bg-white/[0.03] border border-white/10 p-5 text-white rounded-2xl" />
                            </div>

                            <!-- ĐƯA GIÁ VÀ TRẠNG THÁI SANG CỘT TRÁI -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Giá niêm yết</label>
                                    <div class="relative"><span class="absolute left-5 top-1/2 -translate-y-1/2 text-neon-green font-black text-xl">₫</span><input name="gia_thap_nhat" type="number" value="{{ old('gia_thap_nhat', $product->gia_thap_nhat ?? 0) }}" class="w-full bg-neon-green/5 border border-neon-green/20 p-5 pl-12 text-lg font-bold text-neon-green rounded-2xl" /></div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Trạng thái</label>
                                    <div class="relative">
                                        <select name="trang_thai" class="w-full bg-white/[0.03] border border-white/10 p-5 pr-12 text-white text-sm font-display uppercase tracking-widest rounded-2xl outline-none focus:border-neon-green/50 appearance-none cursor-pointer">
                                            <option value="active" class="bg-[#0d0f10] text-neon-green" {{ old('trang_thai', $product->trang_thai ?? '') == 'active' ? 'selected' : '' }}>Đang kinh doanh</option>
                                            <option value="inactive" class="bg-[#0d0f10] text-gray-400" {{ old('trang_thai', $product->trang_thai ?? '') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-gray-500 text-xs">▼</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nhận thông số kỹ thuật chung -->
                            <div class="group space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Thông số kỹ thuật chung</label>
                                    <button type="button" onclick="addSpecRow()" class="px-4 py-2 bg-white/5 border border-white/10 text-white text-[10px] font-bold uppercase tracking-[0.1em] hover:bg-neon-green hover:text-black transition-all rounded-xl">+ Thêm dòng</button>
                                </div>
                                <div class="bg-white/[0.03] border border-white/10 p-5 rounded-3xl space-y-4" id="techSpecsBody">
                                    @foreach($techSpecs as $index => $spec)
                                    <div class="grid grid-cols-12 gap-4 items-center sortable-row">
                                        <div class="col-span-1 text-center cursor-move handle"><i data-lucide="grip-vertical" class="text-gray-500 size-5"></i></div>
                                        <div class="col-span-3"><input name="thong_so_ky_thuat_chung[{{ $index }}][ten]" value="{{ $spec['ten'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Thuộc tính" /></div>
                                        <div class="col-span-7"><input name="thong_so_ky_thuat_chung[{{ $index }}][gia_tri]" value="{{ $spec['gia_tri'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Giá trị" /></div>
                                        <div class="col-span-1 text-center"><button type="button" onclick="removeRow(this)" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="x" class="size-5"></i></button></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Cột phải: Chỉ làm Quản lý Hình ảnh (Chiếm 4 phần) -->
                        <div class="lg:col-span-4 space-y-6">
                            
                            <div class="space-y-6">
                                <!-- Ảnh chính (To như cũ) -->
                                <div class="group relative aspect-square bg-black/40 border-2 border-dashed border-white/10 rounded-3xl overflow-hidden flex flex-col items-center justify-center hover:border-neon-green/40 transition-all cursor-pointer">
                                    @if(isset($product) && $product->link_anh_dai_dien)
                                        <div id="main-preview" class="absolute inset-0 flex items-center justify-center"><img src="{{ $product->link_anh_dai_dien }}" class="w-full h-full object-cover"></div>
                                        <div id="main-upload-ui" class="flex flex-col items-center hidden">
                                            <i data-lucide="upload-cloud" class="size-12 text-gray-700 group-hover:text-neon-green transition-all mb-4"></i>
                                            <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">Thay đổi ảnh</span>
                                        </div>
                                    @else
                                        <div id="main-preview" class="absolute inset-0 flex items-center justify-center hidden"><img src="" class="w-full h-full object-cover"></div>
                                        <div id="main-upload-ui" class="flex flex-col items-center">
                                            <i data-lucide="upload-cloud" class="size-12 text-gray-700 group-hover:text-neon-green transition-all mb-4"></i>
                                            <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">Ảnh đại diện chính</span>
                                        </div>
                                    @endif
                                    <input type="file" name="link_anh_dai_dien" onchange="previewMain(this)" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                </div>

                                <!-- Gallery -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Bộ sưu tập ảnh phụ</span>
                                        <label class="cursor-pointer bg-white/5 hover:bg-neon-green hover:text-black px-3 py-1.5 rounded-lg border border-white/10 transition-all text-[9px] font-bold uppercase tracking-widest">
                                            + Tải lên
                                            <input type="file" name="hinh_anh[]" multiple onchange="previewGallery(this)" class="hidden" accept="image/*">
                                        </label>
                                    </div>
                                    <div id="gallery-previews" class="grid grid-cols-4 gap-2">
                                        @if(isset($product) && is_array($product->hinh_anh))
                                            @foreach($product->hinh_anh as $img)
                                                <div class="image-preview-slot"><img src="{{ $img }}"><div class="remove-img-btn" onclick="this.parentElement.remove()"><i data-lucide="x" class="size-3"></i></div></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Quản lý Biến thể (Variants) - ĐƯỢC ƯU TIÊN ĐƯA LÊN TRÊN -->
                <section class="glass-panel p-6 md:p-10 rounded-3xl border border-neon-green/30 bg-neon-green/5 animate-section mt-12" style="animation-delay: 0.25s">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-4">
                            <i data-lucide="boxes" class="text-neon-green size-6"></i>
                            <h2 class="font-display text-2xl font-bold uppercase text-white">Quản lý Biến thể</h2>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="addVariantRow()" class="px-6 py-3 bg-neon-green text-black text-[12px] font-black uppercase tracking-[0.2em] hover:scale-105 transition-all rounded-xl shadow-[0_0_20px_rgba(0,229,91,0.3)]">+ Thêm biến thể</button>
                        </div>
                    </div>

                    <!-- Bảng danh sách biến thể sinh ra -->
                    <div class="overflow-x-auto {{ isset($product_variant) && $product_variant->count() > 0 ? '' : 'hidden' }}" id="variantsTableContainer">
                        <table class="w-full text-white text-sm">
                            <thead>
                                <tr class="text-[10px] uppercase tracking-wider text-gray-500 border-b border-white/5">
                                    <th class="p-4 text-left">Tên biến thể</th>
                                    <th class="p-4 text-left">Ảnh</th>
                                    <th class="p-4 text-left">Giá bán</th>
                                    <th class="p-4 text-left">Giá niêm yết</th>
                                    <th class="p-4 text-left">Kho</th>
                                    <th class="p-4 text-left">Trạng thái</th>
                                    <th class="p-4 text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="variantsTableBody" class="divide-y divide-white/5">
                                @if(isset($product_variant) && $product_variant->count() > 0)
                                    @foreach($product_variant as $index => $variant)
                                        <tr class="hover:bg-white/[0.02] transition-all">
                                            <td class="p-4">
                                                <input type="hidden" name="variants[{{ $index }}][ma_bien_the]" value="{{ $variant->ma_bien_the ?? '' }}" />
                                                <input name="variants[{{ $index }}][ten_bien_the]" value="{{ $variant->ten_bien_the ?? '' }}" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-white w-full" placeholder="Tên biến thể..." />
                                            </td>
                                            <td class="p-4">
                                                <div class="relative w-12 h-12 bg-white/5 rounded-lg overflow-hidden flex items-center justify-center border border-white/10 hover:border-neon-green/30 transition-all">
                                                    @if($variant->link_anh_bien_the)
                                                        <img id="preview-{{ $index }}" src="{{ $variant->link_anh_bien_the }}" class="absolute inset-0 w-full h-full object-cover">
                                                        <i data-lucide="image" class="size-4 text-gray-500 hidden" id="icon-{{ $index }}"></i>
                                                    @else
                                                        <img id="preview-{{ $index }}" class="absolute inset-0 w-full h-full object-cover hidden">
                                                        <i data-lucide="image" class="size-4 text-gray-500" id="icon-{{ $index }}"></i>
                                                    @endif
                                                    <input type="file" name="variants[{{ $index }}][link_anh_bien_the]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" data-index="{{ $index }}" onchange="previewVariantImage(this, this.dataset.index)">
                                                </div>
                                            </td>
                                            <td class="p-4"><input name="variants[{{ $index }}][gia_ban]" type="number" value="{{ $variant->gia_ban }}" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-neon-green w-full" /></td>
                                            <td class="p-4"><input name="variants[{{ $index }}][gia_niem_yet]" type="number" value="{{ $variant->gia_niem_yet }}" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-gray-400 w-full" /></td>
                                            <td class="p-4"><input name="variants[{{ $index }}][so_luong_ton_kho]" type="number" value="{{ $variant->so_luong_ton_kho }}" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-white w-full" /></td>
                                            <td class="p-4">
                                                <select name="variants[{{ $index }}][trang_thai]" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-white w-full">
                                                    <option value="active" {{ $variant->trang_thai == 'active' ? 'selected' : '' }}>Mở</option>
                                                    <option value="inactive" {{ $variant->trang_thai == 'inactive' ? 'selected' : '' }}>Khóa</option>
                                                </select>
                                            </td>
                                            <td class="p-4 text-center">
                                                <button type="button" data-index="{{ $index }}" onclick="toggleVariantSpecs(this.dataset.index)" class="mr-2 text-gray-400 hover:text-neon-green transition-all"><i data-lucide="list" class="size-4"></i></button>
                                                <button type="button" data-index="{{ $index }}" onclick="this.closest('tr').remove(); document.getElementById('variant-specs-' + this.dataset.index)?.remove();" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="trash-2" class="size-4"></i></button>
                                            </td>
                                        </tr>
                                        <tr class="variant-specs-row" id="variant-specs-{{ $index }}" style="display: none;">
                                            <td colspan="7" class="p-4 bg-white/[0.02]">
                                                <div class="flex items-center justify-between mb-4">
                                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest text-left">Thông số kỹ thuật riêng</span>
                                                    <button type="button" data-index="{{ $index }}" onclick="addVariantSpecRow(this.dataset.index)" class="px-3 py-1.5 bg-white/5 border border-white/10 hover:bg-neon-green hover:text-black rounded-lg text-[10px] font-bold uppercase transition-all">+ Thêm thông số</button>
                                                </div>
                                                <div class="space-y-3 text-left" id="variantSpecsBody-{{ $index }}">
                                                    @if(isset($variant->thong_so_ky_thuat_rieng) && is_array($variant->thong_so_ky_thuat_rieng))
                                                        @foreach($variant->thong_so_ky_thuat_rieng as $sIndex => $spec)
                                                            <div class="grid grid-cols-12 gap-3 items-center">
                                                                <div class="col-span-4"><input name="variants[{{ $index }}][thong_so_ky_thuat_rieng][{{ $sIndex }}][ten]" value="{{ $spec['ten'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-2 text-xs text-white rounded-lg" placeholder="Tên thông số" /></div>
                                                                <div class="col-span-7"><input name="variants[{{ $index }}][thong_so_ky_thuat_rieng][{{ $sIndex }}][gia_tri]" value="{{ $spec['gia_tri'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-2 text-xs text-white rounded-lg" placeholder="Giá trị" /></div>
                                                                <div class="col-span-1 text-center"><button type="button" onclick="this.closest('.grid').remove()" class="text-gray-600 hover:text-red-500"><i data-lucide="x" class="size-4"></i></button></div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                <!-- Code JS sẽ tự động đổ thêm các dòng vào đây nếu nhấn Tạo -->
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Nhận thông tin thêm -->
                <section class="glass-panel p-6 md:p-10 rounded-3xl border border-white/5 bg-surface/20 animate-section mt-12" style="animation-delay: 0.28s">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-4">
                            <i data-lucide="info" class="text-neon-green size-6"></i>
                            <h2 class="font-display text-2xl font-bold uppercase text-white">Thông tin thêm</h2>
                        </div>
                        <button type="button" onclick="addInfoRow()" class="px-4 py-2 bg-white/5 border border-white/10 text-white text-[10px] font-bold uppercase tracking-[0.1em] hover:bg-neon-green hover:text-black transition-all rounded-xl">+ Thêm dòng</button>
                    </div>
                    <div class="space-y-4" id="moreInfoBody">
                        @foreach($moreInfo as $index => $info)
                        <div class="grid grid-cols-12 gap-4 items-center sortable-row">
                            <div class="col-span-1 text-center cursor-move handle"><i data-lucide="grip-vertical" class="text-gray-500 size-5"></i></div>
                            <div class="col-span-3"><input name="thong_tin_them[{{ $index }}][ten]" value="{{ $info['ten'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Tên thông tin" /></div>
                            <div class="col-span-7"><input name="thong_tin_them[{{ $index }}][gia_tri]" value="{{ $info['gia_tri'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Giá trị" /></div>
                            <div class="col-span-1 text-center"><button type="button" onclick="removeRow(this)" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="x" class="size-5"></i></button></div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <section class="glass-panel p-6 md:p-10 rounded-3xl border border-white/5 bg-surface/20 animate-section" style="animation-delay: 0.3s">
                    <div class="flex items-center gap-4 mb-10">
                        <i data-lucide="file-text" class="text-neon-green size-6"></i>
                        <h2 class="font-display text-2xl font-bold uppercase text-white">Mô tả chi tiết</h2>
                    </div>
                    <div id="editor" style="height: 400px;">{!! old('mo_ta_chi_tiet', $product->mo_ta_chi_tiet ?? '') !!}</div>
                    <input type="hidden" name="mo_ta_chi_tiet" id="mo_ta_chi_tiet">
                </section>


            </div>
        </div>
    </div>

    <footer class="fixed bottom-0 left-0 lg:left-72 right-0 glass-panel border-t border-white/10 px-6 md:px-12 py-4 flex justify-between items-center z-50 bg-black/80 backdrop-blur-2xl">
        <a href="{{ route('admin.products.index') }}" class="px-8 py-3 border border-white/10 text-white font-bold uppercase text-[10px] tracking-[0.2em] hover:bg-white/5 rounded-xl transition-all">HỦY BỎ</a>
        <div class="flex gap-4">
            @if(!isset($product))
            <button type="button" onclick="document.getElementById('createProductForm').reset()" class="px-8 py-3 border border-white/10 text-white font-bold uppercase text-[10px] tracking-[0.2em] hover:bg-white/5 rounded-xl transition-all">LÀM MỚI</button>
            @endif
            <button type="submit" class="px-10 py-3 bg-neon-green text-black font-black uppercase text-[10px] tracking-[0.4em] shadow-[0_0_30px_rgba(0,229,91,0.2)] rounded-xl transition-all hover:scale-105 active:scale-95">
                {{ isset($product) ? 'CẬP NHẬT SẢN PHẨM' : 'LƯU SẢN PHẨM' }}
            </button>
        </div>
    </footer>
</form>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // Lấy cấu hình từ thuộc tính data của Form (Sạch lỗi IDE)
    const formEl = document.getElementById('createProductForm');
    const IS_EDIT_MODE = formEl.dataset.isEdit === 'true';
    let specIndex = parseInt(formEl.dataset.specCount) || 0;
    let infoIndex = parseInt(formEl.dataset.infoCount) || 0;

    var quill = new Quill('#editor', { 
        theme: 'snow', 
        modules: { 
            toolbar: [
                [{ 'header': [1, 2, 3, false] }], 
                ['bold', 'italic', 'underline', 'strike'], 
                [{ 'list': 'ordered'}, { 'list': 'bullet' }], 
                ['link', 'image'], 
                ['clean']
            ] 
        } 
    });
    
    document.getElementById('createProductForm').onsubmit = function() { 
        document.getElementById('mo_ta_chi_tiet').value = quill.root.innerHTML; 
        return true; 
    };
    
    function previewMain(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => { 
                document.getElementById('main-preview').classList.remove('hidden');
                document.getElementById('main-preview').querySelector('img').src = e.target.result;
                document.getElementById('main-upload-ui').classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGallery(input) {
        const container = document.getElementById('gallery-previews');
        if (input.files) {
            Array.from(input.files).forEach(file => {
                let reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'image-preview-slot animate-fadeIn';
                    div.innerHTML = `<img src="${e.target.result}"><div class="remove-img-btn" onclick="this.parentElement.remove()"><i data-lucide="x" class="size-3"></i></div>`;
                    container.appendChild(div);
                    lucide.createIcons();
                };
                reader.readAsDataURL(file);
            });
        }
    }



    function addSpecRow() {
        const body = document.getElementById('techSpecsBody');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-4 items-center sortable-row mt-4';
        row.innerHTML = `<div class="col-span-1 text-center cursor-move handle"><i data-lucide="grip-vertical" class="text-gray-500 size-5"></i></div><div class="col-span-3"><input name="thong_so_ky_thuat_chung[${specIndex}][ten]" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Thuộc tính" /></div><div class="col-span-7"><input name="thong_so_ky_thuat_chung[${specIndex}][gia_tri]" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Giá trị" /></div><div class="col-span-1 text-center"><button type="button" onclick="removeRow(this)" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="x" class="size-5"></i></button></div>`;
        body.appendChild(row); 
        lucide.createIcons(); 
        specIndex++;
    }
    
    function removeRow(btn) { btn.closest('.grid').remove(); }

    function previewVariantImage(input, index) {
        const preview = document.getElementById(`preview-${index}`);
        const icon = document.getElementById(`icon-${index}`);
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Khởi tạo Sortable cho Thông số kỹ thuật chung
    new Sortable(document.getElementById('techSpecsBody'), {
        handle: '.handle',
        animation: 150,
        ghostClass: 'bg-white/5'
    });

    // Khởi tạo Sortable cho Thông tin thêm
    new Sortable(document.getElementById('moreInfoBody'), {
        handle: '.handle',
        animation: 150,
        ghostClass: 'bg-white/5'
    });
</script>
<script>
    // Biến đếm để quản lý name của input gửi lên Laravel
    let variantIndex = parseInt(formEl.dataset.variantCount || 0, 10);

    function addInfoRow() {
        const body = document.getElementById('moreInfoBody');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-4 items-center sortable-row mt-4';
        row.innerHTML = `<div class="col-span-1 text-center cursor-move handle"><i data-lucide="grip-vertical" class="text-gray-500 size-5"></i></div><div class="col-span-3"><input name="thong_tin_them[${infoIndex}][ten]" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Tên thông tin" /></div><div class="col-span-7"><input name="thong_tin_them[${infoIndex}][gia_tri]" class="w-full bg-white/[0.03] border border-white/5 p-2 text-white rounded-xl focus:border-neon-green/30" placeholder="Giá trị" /></div><div class="col-span-1 text-center"><button type="button" onclick="removeRow(this)" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="x" class="size-5"></i></button></div>`;
        body.appendChild(row); 
        lucide.createIcons(); 
        infoIndex++;
    }

    function toggleVariantSpecs(index) {
        const row = document.getElementById(`variant-specs-${index}`);
        if (row) {
            row.style.display = (row.style.display === 'none') ? 'table-row' : 'none';
        }
    }

    function addVariantSpecRow(index) {
        const body = document.getElementById(`variantSpecsBody-${index}`);
        if (!body) return;
        const subIndex = body.children.length;
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-3 items-center mt-2';
        div.innerHTML = `
            <div class="col-span-4"><input name="variants[${index}][thong_so_ky_thuat_rieng][${subIndex}][ten]" class="w-full bg-white/[0.03] border border-white/5 p-2 text-xs text-white rounded-lg" placeholder="Tên thông số" /></div>
            <div class="col-span-7"><input name="variants[${index}][thong_so_ky_thuat_rieng][${subIndex}][gia_tri]" class="w-full bg-white/[0.03] border border-white/5 p-2 text-xs text-white rounded-lg" placeholder="Giá trị" /></div>
            <div class="col-span-1 text-center"><button type="button" onclick="this.closest('.grid').remove()" class="text-gray-600 hover:text-red-500"><i data-lucide="x" class="size-4"></i></button></div>
        `;
        body.appendChild(div);
        lucide.createIcons();
    }

    function addVariantRow() {
        const container = document.getElementById('variantsTableContainer');
        container.classList.remove('hidden');
        
        const body = document.getElementById('variantsTableBody');
        const index = variantIndex;
        
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-white/[0.02] transition-all';
        tr.innerHTML = `
            <td class="p-4">
                <input name="variants[${index}][ten_bien_the]" value="" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-white w-full" placeholder="Tên biến thể..." />
            </td>
            <td class="p-4">
                <div class="relative w-12 h-12 bg-white/5 rounded-lg overflow-hidden flex items-center justify-center border border-white/10 hover:border-neon-green/30 transition-all">
                    <img id="preview-${index}" class="absolute inset-0 w-full h-full object-cover hidden">
                    <i data-lucide="image" class="size-4 text-gray-500" id="icon-${index}"></i>
                    <input type="file" name="variants[${index}][link_anh_bien_the]" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewVariantImage(this, ${index})">
                </div>
            </td>
            <td class="p-4"><input name="variants[${index}][gia_ban]" type="number" placeholder="0" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-neon-green w-full" /></td>
            <td class="p-4"><input name="variants[${index}][gia_niem_yet]" type="number" placeholder="0" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-gray-400 w-full" /></td>
            <td class="p-4"><input name="variants[${index}][so_luong_ton_kho]" type="number" value="10" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-white w-full" /></td>
            <td class="p-4">
                <select name="variants[${index}][trang_thai]" class="bg-white/[0.03] border border-white/5 p-2 rounded-lg text-xs text-white w-full">
                    <option value="active">Mở</option>
                    <option value="inactive">Khóa</option>
                </select>
            </td>
            <td class="p-4 text-center">
                <button type="button" onclick="toggleVariantSpecs(${index})" class="mr-2 text-gray-400 hover:text-neon-green transition-all"><i data-lucide="list" class="size-4"></i></button>
                <button type="button" onclick="this.closest('tr').remove(); document.getElementById('variant-specs-${index}')?.remove();" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="trash-2" class="size-4"></i></button>
            </td>
        `;
        
        const specsTr = document.createElement('tr');
        specsTr.className = 'variant-specs-row';
        specsTr.id = `variant-specs-${index}`;
        specsTr.style.display = 'none';
        specsTr.innerHTML = `
            <td colspan="7" class="p-4 bg-white/[0.02]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest text-left">Thông số kỹ thuật riêng</span>
                    <button type="button" onclick="addVariantSpecRow(${index})" class="px-3 py-1.5 bg-white/5 border border-white/10 hover:bg-neon-green hover:text-black rounded-lg text-[10px] font-bold uppercase transition-all">+ Thêm thông số</button>
                </div>
                <div class="space-y-3 text-left" id="variantSpecsBody-${index}"></div>
            </td>
        `;
        
        body.appendChild(tr);
        body.appendChild(specsTr);
        
        lucide.createIcons();
        variantIndex++;
    }
</script>
@endsection