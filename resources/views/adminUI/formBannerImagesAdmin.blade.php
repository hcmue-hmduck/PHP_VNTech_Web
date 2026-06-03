@extends('layouts.admin')

@section('title', isset($bannerImage) ? 'Cập nhật Banner - VNTech' : 'Tạo mới Banner - VNTech')

@section('content')
@php
    $isEdit = isset($bannerImage) && $bannerImage->exists;
@endphp

<div class="w-full">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <a href="{{ route('admin.banner.index') }}"
               class="group inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-neon-green transition-colors mb-3 no-underline">
                <i data-lucide="arrow-left" class="size-3 group-hover:-translate-x-0.5 transition-transform"></i>
                <span>Trở lại danh sách</span>
            </a>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)] uppercase leading-none">
                {{ $isEdit ? 'CẬP NHẬT BANNER' : 'TẠO MỚI BANNER' }}
            </h1>
            @if($isEdit)
            <div class="mt-3 flex items-center gap-2 text-[10px] font-mono text-gray-400 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-neon-green animate-pulse"></span>
                <span>Mã Banner:</span>
                <span class="text-neon-green font-bold">{{ $bannerImage->ma_banner }}</span>
            </div>
            @endif
        </div>
    </div>



    <form method="POST" 
          action="{{ $isEdit ? route('admin.banner.update', $bannerImage->ma_banner) : route('admin.banner.store') }}" 
          enctype="multipart/form-data" 
          class="space-y-8 pb-28">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- Card 1: Thông tin cấu hình -->
        <div class="glass-panel p-6 lg:p-8 border-l-4 border-l-neon-green relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-neon-green/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
            <h2 class="text-lg font-display font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <i data-lucide="info" class="text-neon-green size-5"></i>
                THÔNG TIN BANNER
            </h2>
            
            <div class="space-y-6">
                <!-- Hàng 1: Tiêu đề & Link -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Tiêu đề -->
                    <div class="space-y-1.5">
                        <label for="tieu_de" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Tiêu đề Banner</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="type" class="size-4 text-gray-500"></i>
                            </div>
                            <input
                                id="tieu_de" name="tieu_de" type="text"
                                value="{{ old('tieu_de', $bannerImage->tieu_de ?? '') }}"
                                class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm text-white focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg"
                                placeholder="Nhập tiêu đề hiển thị trên slide..."
                            />
                        </div>
                    </div>

                    <!-- Đường dẫn liên kết -->
                    <div class="space-y-1.5">
                        <label for="lien_ket" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Link liên kết (Click chuyển hướng)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="link" class="size-4 text-gray-500"></i>
                            </div>
                            <input
                                id="lien_ket" name="lien_ket" type="text"
                                value="{{ old('lien_ket', $bannerImage->lien_ket ?? '') }}"
                                class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg"
                                placeholder="Vd: /flashsales hoặc https://..."
                            />
                        </div>
                    </div>
                </div>

                <!-- Hàng 2: Mô tả -->
                <div class="space-y-1.5">
                    <label for="mo_ta" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Mô tả chi tiết</label>
                    <textarea
                        id="mo_ta" name="mo_ta"
                        class="w-full min-h-[100px] bg-dark-bg/50 border border-white/10 p-4 text-sm text-gray-300 focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg resize-none"
                        placeholder="Nhập phụ đề hoặc thông điệp quảng cáo ngắn..."
                    >{{ old('mo_ta', $bannerImage->mo_ta ?? '') }}</textarea>
                </div>

                <!-- Hàng 3: Thứ tự hiển thị & Trạng thái -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Thứ tự hiển thị -->
                    <div class="space-y-1.5">
                        <label for="thu_tu_hien_thi" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Thứ tự hiển thị (Ưu tiên sắp xếp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="sort-asc" class="size-4 text-gray-500"></i>
                            </div>
                            <input
                                id="thu_tu_hien_thi" name="thu_tu_hien_thi" type="number" min="0"
                                value="{{ old('thu_tu_hien_thi', $bannerImage->thu_tu_hien_thi ?? 0) }}"
                                class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-4 text-sm font-mono text-white focus:border-neon-green focus:bg-neon-green/5 outline-none transition-all rounded-lg"
                                placeholder="Vd: 0, 1, 2"
                            />
                        </div>
                    </div>

                    <!-- Trạng thái -->
                    <div class="space-y-1.5">
                        <label for="trang_thai" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Trạng thái hoạt động</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="activity" class="size-4 text-gray-500"></i>
                            </div>
                            <select
                                id="trang_thai" name="trang_thai"
                                class="w-full h-12 bg-dark-bg/50 border border-white/10 pl-11 pr-10 text-sm font-mono text-white focus:border-neon-green focus:bg-neon-green/5 outline-none appearance-none cursor-pointer rounded-lg transition-all"
                            >
                                @php
                                    $currentStatus = old('trang_thai', $bannerImage->trang_thai ?? 'active');
                                @endphp
                                <option value="active" {{ $currentStatus === 'active' || $currentStatus === true || $currentStatus === '1' ? 'selected' : '' }}>HOẠT ĐỘNG (HIỂN THỊ)</option>
                                <option value="inactive" {{ $currentStatus === 'inactive' || $currentStatus === false || $currentStatus === '0' ? 'selected' : '' }}>ẨN (TẠM NGƯNG)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i data-lucide="chevron-down" class="size-4 text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tải lên hình ảnh (Nằm dưới phần thông tin) -->
                <div class="space-y-3 pt-4 border-t border-white/5">
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Tải lên hình ảnh banner</label>
                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="w-full md:w-2/3">
                            <input
                                id="image" name="image" type="file" accept="image/*"
                                class="w-full text-xs text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border file:border-white/10 file:text-[10px] file:font-bold file:uppercase file:bg-white/5 file:text-white hover:file:bg-white/10 file:cursor-pointer outline-none cursor-pointer"
                            />
                            <p class="text-[9px] text-gray-500 mt-2 font-mono uppercase tracking-wider">Kích thước khuyên dùng: 1920x720 (Độ phân giải ngang rộng)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Bản xem trước thời gian thực (Real-time Live Preview) -->
        <div class="glass-panel p-6 lg:p-8 border-l-4 border-l-yellow-400 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
            <h2 class="text-lg font-display font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <i data-lucide="eye" class="text-yellow-400 size-5"></i>
                BẢN XEM TRƯỚC BANNER TRANG CHỦ
            </h2>
            
            <div class="relative w-full rounded-[32px] border border-white/10 overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.4)] h-[720px] bg-slate-950 flex items-center group">
                <!-- Background Image -->
                <img id="livePreviewImage" 
                     src="{{ old('image_url', $bannerImage->image_url ?? null) }}" 
                     alt="Thêm ảnh để thấy Preview" 
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                     referrerpolicy="no-referrer">
                     
                <!-- Dark Overlay to make text legible -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent z-10"></div>
                
                <!-- Text Overlay Content -->
                <div class="absolute inset-y-0 left-0 pl-8 md:pl-16 pr-8 flex flex-col justify-center z-20 max-w-[70%] select-none pointer-events-none">
                    <h3 id="livePreviewTitle" class="text-lg md:text-4xl font-black text-white uppercase tracking-wide drop-shadow-md leading-tight mb-2 md:mb-4">
                        {{ old('tieu_de', $bannerImage->tieu_de ?? 'TIÊU ĐỀ BANNER QUẢNG CÁO') }}
                    </h3>
                    <p id="livePreviewDesc" class="text-[10px] md:text-sm text-gray-300 font-medium line-clamp-2 leading-relaxed max-w-lg">
                        {{ old('mo_ta', $bannerImage->mo_ta ?? 'Mô tả ngắn gọn về chương trình khuyến mãi, dòng sản phẩm nổi bật của bạn sẽ hiển thị tại đây.') }}
                    </p>
                    <div class="mt-4 md:mt-6">
                        <span class="inline-flex items-center gap-2 bg-neon-green text-black px-4 py-2 text-[8px] md:text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-lg">
                            <span>Xem ngay</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed Action Footer -->
        <div class="fixed bottom-0 left-0 lg:left-72 right-0 px-12 py-4 bg-dark-bg/95 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 z-40 shadow-[0_-8px_30px_rgb(0,0,0,0.6)] backdrop-blur-md">
            <div class="text-[10px] text-gray-500 uppercase tracking-widest font-mono hidden md:block">
                * Bản xem trước tự động cập nhật ngay khi bạn nhập chữ hoặc chọn tệp ảnh mới.
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('admin.banner.index') }}" class="w-full md:w-auto flex justify-center items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 px-6 py-3 text-xs font-bold uppercase tracking-widest transition-all rounded-lg no-underline font-mono">
                    <span>Hủy bỏ</span>
                </a>
                <button type="submit" class="w-full md:w-auto group flex justify-center items-center gap-2 bg-neon-green text-black border border-neon-green px-6 py-3 text-xs font-bold uppercase tracking-widest hover:brightness-110 transition-all rounded-lg shadow-[0_0_15px_rgba(0,229,91,0.2)] font-mono">
                    <i data-lucide="{{ $isEdit ? 'save' : 'plus' }}" class="w-4 h-4 group-hover:scale-110 transition-all"></i>
                    <span>{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Live text update elements
        const tieuDeInput = document.getElementById('tieu_de');
        const moTaInput = document.getElementById('mo_ta');
        const livePreviewTitle = document.getElementById('livePreviewTitle');
        const livePreviewDesc = document.getElementById('livePreviewDesc');

        if (tieuDeInput && livePreviewTitle) {
            tieuDeInput.addEventListener('input', function() {
                livePreviewTitle.textContent = this.value.trim() !== '' ? this.value.toUpperCase() : 'TIÊU ĐỀ BANNER QUẢNG CÁO';
            });
        }

        if (moTaInput && livePreviewDesc) {
            moTaInput.addEventListener('input', function() {
                livePreviewDesc.textContent = this.value.trim() !== '' ? this.value : 'Mô tả ngắn gọn về chương trình khuyến mãi, dòng sản phẩm nổi bật của bạn sẽ hiển thị tại đây.';
            });
        }

        // Image Preview handler
        const imageInput = document.getElementById('image');
        const livePreviewImage = document.getElementById('livePreviewImage');

        if (imageInput && livePreviewImage) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        livePreviewImage.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
