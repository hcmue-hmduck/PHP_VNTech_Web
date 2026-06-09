@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá - VNTech')

@section('content')
@php
    use Illuminate\Support\Str;

    $ratingOptions = [5, 4, 3, 2, 1];
@endphp

<div class="w-full" data-review-admin>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-neon-green drop-shadow-[0_0_15px_rgba(0,229,91,0.3)]">
                QUẢN LÝ ĐÁNH GIÁ
            </h1>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="group flex items-center gap-3 bg-transparent border-2 border-neon-green text-neon-green px-6 py-3 font-bold uppercase tracking-widest hover:bg-neon-green hover:text-black transition-all duration-300">
            <i data-lucide="refresh-cw" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
            <span>TẢI LẠI</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng đánh giá</p>
                <i data-lucide="messages-square" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ number_format($totalReviews, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Toàn bộ sản phẩm</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Điểm trung bình</p>
                <i data-lucide="star" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ number_format($averageRating, 1) }}/5</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Rating average</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Đã phản hồi</p>
                <i data-lucide="reply" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ number_format($repliedReviewCount, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Có phản hồi active</p>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start z-10">
                <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Chưa phản hồi</p>
                <i data-lucide="message-circle-warning" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
            </div>
            <div class="z-10">
                <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ number_format($pendingReplyCount, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Cần xử lý</p>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reviews.index') }}" class="glass-panel p-6 border-l-4 border-l-neon-green mb-12 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="space-y-1.5 md:col-span-2">
            <label for="so_sao" class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-500">Lọc theo số sao</label>
            <select id="so_sao" name="so_sao" class="w-full h-11 bg-dark-bg border border-white/10 px-4 text-xs font-mono focus:border-neon-green/50 outline-none appearance-none cursor-pointer">
                <option value="">TẤT CẢ ĐÁNH GIÁ</option>
                @foreach($ratingOptions as $option)
                    <option value="{{ $option }}" @selected($rating === $option)>{{ $option }} SAO</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="h-11 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-[0.2em] transition-all">
            Áp dụng lọc
        </button>

        <a href="{{ route('admin.reviews.index') }}" class="h-11 inline-flex items-center justify-center bg-transparent border border-white/10 hover:border-neon-green/50 text-gray-400 hover:text-neon-green text-[10px] font-bold uppercase tracking-[0.2em] transition-all">
            Xoá lọc
        </a>
    </form>

    <div class="glass-panel overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-high/80 border-b border-white/10">
                    <tr>
                        <th class="w-[33%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Sản phẩm</th>
                        <th class="w-[37%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Nội dung đánh giá</th>
                        <th class="w-[18%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Phản hồi</th>
                        <th class="w-[12%] px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reviews as $review)
                        @php
                            $rowId = 'review-detail-' . ($review->ma_danh_gia ?? $review->_id);
                            $images = collect($review->danh_sach_anh ?? [])->map(fn ($image) => is_string($image) ? ['url' => $image] : $image)->filter(fn ($image) => !empty($image['url']));
                            $hasVideo = !empty($review->video['url']);
                            $hasMedia = $images->isNotEmpty() || $hasVideo;
                            $reply = $review->admin_reply;
                            $replyRouteModel = $reply ?: null;
                            $productDetailUrl = $review->ma_san_pham
                                ? route('home.product_detail', [
                                    'ma_san_pham' => $review->ma_san_pham,
                                    'ma_bien_the' => $review->ma_bien_the,
                                ])
                                : null;
                        @endphp
                        <tr class="group hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4 align-top">
                                @if($productDetailUrl)
                                    <a
                                        href="{{ $productDetailUrl }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="inline-flex max-w-full items-center gap-2 text-sm font-semibold text-white hover:text-neon-green transition-colors"
                                    >
                                        <span class="truncate">{{ $review->ten_hien_thi ?: 'Sản phẩm chưa xác định' }}</span>
                                        <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0 text-gray-500"></i>
                                    </a>
                                @else
                                    <div class="text-sm font-semibold text-white line-clamp-1">
                                        {{ $review->ten_hien_thi ?: 'Sản phẩm chưa xác định' }}
                                    </div>
                                @endif
                                <div class="text-[9px] font-mono text-gray-500 mt-1 uppercase tracking-wider">Mã đơn: {{ $review->ma_don_hang ?? 'N/A' }}</div>
                                <div class="text-[9px] font-mono text-gray-600 mt-1 uppercase tracking-wider">Mã review: {{ $review->ma_danh_gia ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="flex text-sm leading-none">
                                        @for($star = 1; $star <= 5; $star++)
                                            <span class="{{ $star <= (int) $review->so_sao ? 'text-amber-400' : 'text-gray-700' }}">★</span>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] font-mono text-gray-500">{{ optional($review->updated_at)->format('d/m/Y H:i') }}</span>
                                    @if($review->is_updated)
                                        <span class="border border-amber-400/30 bg-amber-400/10 px-2 py-0.5 text-[9px] font-bold uppercase text-amber-300">Đã sửa</span>
                                    @endif
                                </div>
                                <p class="max-w-xl text-sm text-gray-300 line-clamp-2">{{ Str::limit($review->noi_dung ?: 'Không có nội dung', 120) }}</p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if($reply)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase text-neon-green border border-neon-green/40 bg-neon-green/10">
                                        <span class="w-1 h-1 rounded-full bg-neon-green"></span>
                                        Đã phản hồi
                                    </div>
                                    <p class="mt-2 text-xs text-gray-400 line-clamp-2">{{ Str::limit($reply->noi_dung, 72) }}</p>
                                    <p class="mt-1 text-[9px] font-mono text-gray-600">{{ optional($reply->updated_at)->format('d/m/Y H:i') }}</p>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold uppercase text-amber-300 border border-amber-500/30 bg-amber-500/10">
                                        <span class="w-1 h-1 rounded-full bg-amber-400"></span>
                                        Chưa phản hồi
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" data-toggle-review="{{ $rowId }}" class="p-2 hover:text-neon-green hover:bg-neon-green/10 transition-colors border border-transparent hover:border-white/10 rounded-lg" title="Xem chi tiết">
                                        <i data-lucide="chevrons-up-down" class="w-4 h-4"></i>
                                    </button>
                                    <button type="button" data-toggle-review="{{ $rowId }}" data-focus-reply="reply-textarea-{{ $review->ma_danh_gia }}" class="px-3 py-2 border border-white/10 hover:border-neon-green/50 hover:text-neon-green text-[10px] font-bold uppercase tracking-wider transition-colors">
                                        {{ $reply ? 'Sửa phản hồi' : 'Phản hồi' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr id="{{ $rowId }}" class="hidden bg-black/20">
                            <td colspan="4" class="px-6 py-6">
                                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                                    <div class="xl:col-span-2 space-y-5">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-2">Full nội dung đánh giá</p>
                                            <div class="rounded-lg border border-white/10 bg-dark-bg/60 p-4 text-sm text-gray-200 whitespace-pre-line leading-relaxed">
                                                {{ $review->noi_dung ?: 'Không có nội dung' }}
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-2">Media đính kèm</p>
                                            @if($hasMedia)
                                                <div class="flex flex-wrap gap-3">
                                                    @if($hasVideo)
                                                        <a href="{{ $review->video['url'] }}" target="_blank" rel="noopener" class="relative w-28 h-28 border border-white/10 bg-black overflow-hidden rounded-lg">
                                                            <video src="{{ $review->video['url'] }}" class="w-full h-full object-cover" muted preload="metadata"></video>
                                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 text-white">
                                                                <i data-lucide="play" class="w-7 h-7 fill-current"></i>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @foreach($images as $image)
                                                        <a href="{{ $image['url'] }}" target="_blank" rel="noopener" class="w-28 h-28 border border-white/10 bg-black overflow-hidden rounded-lg">
                                                            <img src="{{ $image['url'] }}" alt="Ảnh đánh giá" class="w-full h-full object-cover" loading="lazy">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="rounded-lg border border-white/10 bg-dark-bg/60 p-4 text-sm text-gray-500">Không có media</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        @if($reply)
                                            <div class="rounded-lg border border-neon-green/20 bg-neon-green/5 p-4">
                                                <div class="flex items-center justify-between gap-3 mb-2">
                                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neon-green">Phản hồi hiện tại</p>
                                                    @if($reply->is_updated)
                                                        <span class="border border-amber-400/30 bg-amber-400/10 px-2 py-0.5 text-[9px] font-bold uppercase text-amber-300">Đã chỉnh sửa</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-200 whitespace-pre-line leading-relaxed">{{ $reply->noi_dung }}</p>
                                                <p class="mt-3 text-[9px] font-mono text-gray-500">{{ optional($reply->updated_at)->format('d/m/Y H:i') }}</p>
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ $reply ? route('admin.review-replies.update', [$review, $reply]) : route('admin.review-replies.store', $review) }}" class="rounded-lg border border-white/10 bg-surface-high/60 p-4 space-y-3">
                                            @csrf
                                            @if($reply)
                                                @method('PUT')
                                            @endif
                                            <label for="reply-textarea-{{ $review->ma_danh_gia }}" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">
                                                {{ $reply ? 'Sửa phản hồi' : 'Thêm phản hồi' }}
                                            </label>
                                            <textarea
                                                id="reply-textarea-{{ $review->ma_danh_gia }}"
                                                name="noi_dung"
                                                rows="5"
                                                required
                                                maxlength="2000"
                                                class="w-full bg-dark-bg border border-white/10 p-3 text-sm text-white outline-none focus:border-neon-green/50 resize-y"
                                            >{{ old('noi_dung', $reply->noi_dung ?? '') }}</textarea>
                                            <button type="submit" class="w-full h-11 bg-neon-green text-black hover:bg-white text-[10px] font-bold uppercase tracking-[0.2em] transition-colors">
                                                {{ $reply ? 'Lưu phản hồi' : 'Gửi phản hồi' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 uppercase tracking-widest">
                                Không có đánh giá phù hợp
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center gap-6 py-4">
        <div class="text-[10px] font-mono text-gray-500 uppercase tracking-widest">
            Displaying <span class="text-neon-green font-bold">{{ $reviews->firstItem() ?? 0 }} - {{ $reviews->lastItem() ?? 0 }}</span> of <span class="text-gray-300">{{ $reviews->total() ?? 0 }}</span> Records Identified
        </div>

        <div class="flex items-center gap-1">
            {{ $reviews->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-toggle-review]').forEach((button) => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.toggleReview);
            if (!target) return;

            target.classList.toggle('hidden');

            const textareaId = button.dataset.focusReply;
            if (textareaId && !target.classList.contains('hidden')) {
                const textarea = document.getElementById(textareaId);
                if (textarea) {
                    window.setTimeout(() => textarea.focus(), 100);
                }
            }
        });
    });
</script>
@endpush
