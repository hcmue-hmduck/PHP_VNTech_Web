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
    $techSpecs = old('thuoc_tinh_chung', (isset($product) && isset($product->thuoc_tinh_chung)) ? $product->thuoc_tinh_chung : [['ten' => '', 'gia_tri' => '']]);
    if (!is_array($techSpecs)) $techSpecs = [['ten' => '', 'gia_tri' => '']];
@endphp

<form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" 
      method="POST" id="createProductForm" class="pb-32 px-2 md:px-6 w-full" enctype="multipart/form-data"
      data-is-edit="{{ isset($product) ? 'true' : 'false' }}"
      data-spec-count="{{ count($techSpecs) }}">
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
            <!-- Cột trái -->
            <div class="lg:col-span-8 space-y-12">
                <section class="glass-panel p-6 md:p-10 rounded-3xl border border-white/5 bg-surface/20 animate-section" style="animation-delay: 0.2s">
                    <div class="flex items-center gap-4 mb-10">
                        <i data-lucide="database" class="text-neon-green size-6"></i>
                        <h2 class="font-display text-2xl font-bold uppercase text-white">Thông tin cơ bản</h2>
                    </div>
                    <div class="grid gap-8">
                        <div class="group space-y-3">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Tên sản phẩm</label>
                            <input name="ten_san_pham" id="ten_san_pham" value="{{ old('ten_san_pham', $product->ten_san_pham ?? '') }}" required class="w-full bg-white/[0.03] border border-white/10 p-5 text-white text-lg font-display uppercase tracking-widest rounded-2xl" placeholder="Nhập tên thiết bị..." />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group space-y-3">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Đường dẫn định danh</label>
                                <input name="slug" id="slug" value="{{ old('slug', $product->slug ?? '') }}" required class="w-full bg-white/[0.03] border border-white/10 p-5 text-neon-green text-sm font-mono rounded-2xl" />
                            </div>
                            <div class="group space-y-3">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Thương hiệu</label>
                                <select name="ma_thuong_hieu" class="w-full bg-white/[0.03] border border-white/10 p-5 text-white rounded-2xl appearance-none">
                                    <option value="VNTech" {{ old('ma_thuong_hieu', $product->ma_thuong_hieu ?? '') == 'VNTech' ? 'selected' : '' }}>VNTech Original</option>
                                    <option value="QuantumEdge" {{ old('ma_thuong_hieu', $product->ma_thuong_hieu ?? '') == 'QuantumEdge' ? 'selected' : '' }}>Quantum Edge</option>
                                </select>
                            </div>
                        </div>
                        <div class="group space-y-3">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Mô tả ngắn</label>
                            <input name="mo_ta_ngan" value="{{ old('mo_ta_ngan', $product->mo_ta_ngan ?? '') }}" class="w-full bg-white/[0.03] border border-white/10 p-5 text-white rounded-2xl" />
                        </div>
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

                <!-- Thông số kỹ thuật -->
                <section class="glass-panel p-6 md:p-10 rounded-3xl border border-white/5 bg-surface/20 animate-section" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-4">
                            <i data-lucide="layers" class="text-neon-green size-6"></i>
                            <h2 class="font-display text-2xl font-bold uppercase text-white">Thông số kỹ thuật</h2>
                        </div>
                        <button type="button" onclick="addSpecRow()" class="px-6 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-neon-green hover:text-black transition-all rounded-xl">+ Thêm dòng</button>
                    </div>
                    <div class="space-y-4" id="techSpecsBody">
                        @foreach($techSpecs as $index => $spec)
                        <div class="grid grid-cols-12 gap-4 items-center animate-fadeIn">
                            <div class="col-span-4"><input name="thuoc_tinh_chung[{{ $index }}][ten]" value="{{ $spec['ten'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-4 text-white rounded-xl focus:border-neon-green/30" placeholder="Thuộc tính" /></div>
                            <div class="col-span-7"><input name="thuoc_tinh_chung[{{ $index }}][gia_tri]" value="{{ $spec['gia_tri'] ?? '' }}" class="w-full bg-white/[0.03] border border-white/5 p-4 text-white rounded-xl focus:border-neon-green/30" placeholder="Giá trị" /></div>
                            <div class="col-span-1 text-center"><button type="button" onclick="removeRow(this)" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="x" class="size-5"></i></button></div>
                        </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <!-- Cột phải -->
            <div class="lg:col-span-4 space-y-12">
                <div class="sticky top-24 space-y-12">
                    <section class="glass-panel p-8 rounded-3xl border border-white/5 bg-surface/30 animate-section" style="animation-delay: 0.5s">
                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Giá niêm yết</label>
                                <div class="relative"><span class="absolute left-5 top-1/2 -translate-y-1/2 text-neon-green font-black text-xl">₫</span><input name="gia_thap_nhat" type="number" value="{{ old('gia_thap_nhat', $product->gia_thap_nhat ?? 0) }}" class="w-full bg-neon-green/5 border border-neon-green/20 p-6 pl-12 text-3xl font-black text-neon-green rounded-2xl" /></div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Trạng thái</label>
                                <select name="trang_thai" class="w-full bg-white/5 border border-white/10 p-5 text-white rounded-2xl">
                                    <option value="active" {{ old('trang_thai', $product->trang_thai ?? '') == 'active' ? 'selected' : '' }}>Đang kinh doanh</option>
                                    <option value="inactive" {{ old('trang_thai', $product->trang_thai ?? '') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    <section class="glass-panel p-8 rounded-3xl border border-white/5 bg-surface/30 animate-section" style="animation-delay: 0.6s">
                        <h2 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-8 text-center italic">Quản lý Hình ảnh</h2>
                        <div class="space-y-8">
                            <!-- Ảnh chính -->
                            <div class="group relative aspect-square bg-black/40 border-2 border-dashed border-white/10 rounded-3xl overflow-hidden flex flex-col items-center justify-center hover:border-neon-green/40 transition-all cursor-pointer">
                                @if(isset($product) && $product->link_anh_dai_dien)
                                    <div id="main-preview" class="absolute inset-0 flex items-center justify-center"><img src="{{ $product->link_anh_dai_dien }}" class="w-full h-full object-cover"></div>
                                    <div id="main-upload-ui" class="flex flex-col items-center hidden">
                                        <i data-lucide="upload-cloud" class="size-12 text-gray-700 group-hover:text-neon-green transition-all mb-4"></i>
                                        <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">Thay đổi ảnh đại diện</span>
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
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Bộ sưu tập ảnh phụ</span>
                                    <label class="cursor-pointer bg-white/5 hover:bg-neon-green hover:text-black px-3 py-1.5 rounded-lg border border-white/10 transition-all text-[9px] font-bold uppercase tracking-widest">
                                        + Tải lên
                                        <input type="file" name="hinh_anh_phu[]" multiple onchange="previewGallery(this)" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <div id="gallery-previews" class="grid grid-cols-4 gap-3">
                                    @if(isset($product) && is_array($product->gallery))
                                        @foreach($product->gallery as $img)
                                            <div class="image-preview-slot"><img src="{{ $img }}"><div class="remove-img-btn" onclick="this.parentElement.remove()"><i data-lucide="x" class="size-3"></i></div></div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
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

    document.getElementById('ten_san_pham').addEventListener('input', function() {
        if (!IS_EDIT_MODE) {
            let slug = this.value.toLowerCase().replace(/[áàảãạăắằẳẵặâấầẩẫậ]/g, 'a').replace(/[éèẻẽẹêếềểễệ]/g, 'e').replace(/[íìỉĩị]/g, 'i').replace(/[óòỏõọôốồổỗộơớờởỡợ]/g, 'o').replace(/[úùủũụưứừửữự]/g, 'u').replace(/[ýỳỷỹỵ]/g, 'y').replace(/đ/g, 'd').replace(/([^0-9a-z-\s])/g, '').replace(/(\s+)/g, '-').replace(/-+/g, '-').replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        }
    });

    function addSpecRow() {
        const body = document.getElementById('techSpecsBody');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-4 items-center';
        row.innerHTML = `<div class="col-span-4"><input name="thuoc_tinh_chung[${specIndex}][ten]" class="w-full bg-white/[0.03] border border-white/5 p-4 text-white rounded-xl focus:border-neon-green/30" placeholder="Thuộc tính" /></div><div class="col-span-7"><input name="thuoc_tinh_chung[${specIndex}][gia_tri]" class="w-full bg-white/[0.03] border border-white/5 p-4 text-white rounded-xl focus:border-neon-green/30" placeholder="Giá trị" /></div><div class="col-span-1 text-center"><button type="button" onclick="removeRow(this)" class="text-gray-600 hover:text-red-500 transition-all"><i data-lucide="x" class="size-5"></i></button></div>`;
        body.appendChild(row); 
        lucide.createIcons(); 
        specIndex++;
    }
    
    function removeRow(btn) { btn.closest('.grid').remove(); }
</script>
@endsection