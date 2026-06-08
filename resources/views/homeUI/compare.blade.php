@extends('layouts.app')

@section('title', 'So sánh cấu hình AI | VNTech')

@section('content')
<style>
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
    .compare-markdown :where(p, ul, ol, table) { margin-top: 0.65rem; }
    .compare-markdown :where(p:first-child, ul:first-child, ol:first-child, table:first-child) { margin-top: 0; }
    .compare-markdown ul { list-style: disc; padding-left: 1.25rem; }
    .compare-markdown ol { list-style: decimal; padding-left: 1.25rem; }
    .compare-markdown li + li { margin-top: 0.25rem; }
    .compare-markdown strong { color: #0f172a; font-weight: 800; }
    .compare-markdown table { width: 100%; border-collapse: collapse; font-size: 0.9em; }
    .compare-markdown th, .compare-markdown td { border: 1px solid #e2e8f0; padding: 0.5rem 0.65rem; text-align: left; vertical-align: top; }
    .compare-markdown th { background: #fff7ed; color: #c2410c; font-weight: 800; }
    .compare-ai-panel {
        position: relative;
        isolation: isolate;
        overflow: hidden;
    }
    .compare-ai-panel > * {
        position: relative;
        z-index: 1;
    }
    .compare-ai-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 0;
        border-radius: inherit;
        background:
            linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.45), transparent),
            linear-gradient(135deg, rgba(249, 115, 22, 0.24), rgba(14, 165, 233, 0.18));
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .compare-ai-panel::after {
        content: "";
        position: absolute;
        inset: 2px;
        z-index: 0;
        border-radius: inherit;
        background: #fff;
    }
    .compare-ai-panel.is-loading {
        border-color: transparent;
    }
    .compare-ai-panel.is-loading::before {
        opacity: 1;
        animation: compare-ai-border-glow 2.8s ease-in-out infinite;
    }
    @keyframes compare-ai-border-glow {
        0%, 100% {
            opacity: 0.38;
            transform: translateX(-18%) scale(1.02);
        }
        50% {
            opacity: 0.75;
            transform: translateX(18%) scale(1.02);
        }
    }
</style>

<section class="min-h-screen bg-[#FAF8F2] px-4 py-10 sm:px-8">
    <div class="mx-auto max-w-7xl">
        {{-- Page header + primary actions --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.28em] text-brand-500">VNTech AI</p>
                <h1 class="mt-2 font-display text-3xl font-black uppercase tracking-tight text-slate-900 sm:text-4xl">
                    So sánh cấu hình
                </h1>
                <p class="mt-2 max-w-2xl text-sm font-medium leading-relaxed text-slate-500">
                    Danh sách so sánh được lưu trên trình duyệt. Dữ liệu chi tiết sẽ được lấy từ hệ thống theo mã biến thể trước khi gửi cho AI phân tích.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('home.products') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-black uppercase tracking-wider text-slate-600 transition hover:bg-slate-50">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Thêm sản phẩm
                </a>
                <button type="button"
                        id="compare-clear-btn"
                        class="hidden items-center justify-center gap-2 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-xs font-black uppercase tracking-wider text-red-600 transition hover:bg-red-100">
                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                    Xóa tất cả
                </button>
            </div>
        </div>

        {{-- Empty state shown when localStorage has no variant IDs --}}
        <div id="compare-empty-state" class="hidden rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center shadow-xs">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-brand-500">
                <i data-lucide="git-compare" class="h-8 w-8"></i>
            </div>
            <h2 class="mt-5 font-display text-xl font-black uppercase tracking-tight text-slate-800">
                Chưa có sản phẩm để so sánh
            </h2>
            <p class="mx-auto mt-2 max-w-md text-sm font-medium leading-relaxed text-slate-500">
                Vào trang chi tiết sản phẩm và bấm nút so sánh để thêm tối đa 3 cấu hình.
            </p>
            <a href="{{ route('home.products') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 px-5 py-3 text-xs font-black uppercase tracking-wider text-white transition hover:bg-brand-600">
                <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                Xem sản phẩm
            </a>
        </div>

        {{-- Compare workspace shown when there is at least one selected variant --}}
        <div id="compare-content" class="hidden space-y-6">
            {{-- Selected variants summary + AI action --}}
            <div class="rounded-3xl border border-white bg-white p-5 shadow-[0_18px_45px_rgba(15,23,42,0.04)]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Danh sách đã chọn</p>
                        <p class="mt-1 text-sm font-bold text-slate-700">
                            <span id="compare-total-count">0</span>/3 biến thể đang chờ so sánh
                        </p>
                    </div>
                    <button type="button"
                            id="compare-ai-btn"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-wider text-white transition hover:bg-brand-500 disabled:cursor-not-allowed disabled:bg-slate-300">
                        <i data-lucide="sparkles" class="h-4 w-4"></i>
                        So sánh
                    </button>
                </div>
            </div>

            {{-- Variant cards are fetched from the backend using IDs stored in localStorage --}}
            <div id="compare-list" class="grid gap-4 md:grid-cols-3"></div>

            {{-- AI comparison response --}}
            <div id="compare-ai-panel" class="compare-ai-panel rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-brand-50 text-brand-500">
                        <i data-lucide="bot" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-wider text-slate-800">Kết quả so sánh</p>
                        <div id="compare-ai-result" class="compare-markdown mt-1 text-sm leading-relaxed text-slate-500">
                            Chọn ít nhất 2 cấu hình rồi bấm so sánh để AI phân tích và gợi ý lựa chọn phù hợp.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script>
    // Escape localStorage values before injecting them into HTML.
    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = String(value);
        return div.innerHTML;
    }

    const compareVariantsUrl = "{{ route('compare.variants') }}";
    const compareAiUrl = "{{ route('compare.ai') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN').format(Number(value || 0)) + '₫';
    }

    function normalizeSpecs(specs) {
        return Array.isArray(specs) ? specs.slice(0, 4) : [];
    }

    // Read selected variant IDs from localStorage, fetch variant data, and update the compare page.
    async function renderCompareView() {
        const ids = window.getCompareVariantIds ? window.getCompareVariantIds() : [];
        const emptyState = document.getElementById('compare-empty-state');
        const content = document.getElementById('compare-content');
        const clearBtn = document.getElementById('compare-clear-btn');
        const countLabel = document.getElementById('compare-total-count');
        const list = document.getElementById('compare-list');
        const aiBtn = document.getElementById('compare-ai-btn');

        emptyState?.classList.toggle('hidden', ids.length > 0);
        content?.classList.toggle('hidden', ids.length === 0);
        clearBtn?.classList.toggle('hidden', ids.length === 0);
        clearBtn?.classList.toggle('inline-flex', ids.length > 0);

        if (countLabel) countLabel.textContent = ids.length;
        if (aiBtn) aiBtn.disabled = ids.length < 2;
        if (!list) return;

        if (ids.length === 0) {
            list.innerHTML = '';
            return;
        }

        list.innerHTML = `
            <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-6 text-sm font-bold text-slate-500 shadow-xs">
                Đang tải thông tin cấu hình...
            </div>
        `;

        try {
            const response = await fetch(compareVariantsUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ variant_ids: ids }),
            });

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Không tải được dữ liệu so sánh');
            }

            const items = Array.isArray(payload.items) ? payload.items : [];
            const validIds = items
                .map((item) => window.normalizeCompareVariantId
                    ? window.normalizeCompareVariantId(item.ma_bien_the)
                    : String(item.ma_bien_the || ''))
                .filter(Boolean);

            if (validIds.length !== ids.length || validIds.some((id, index) => id !== ids[index])) {
                saveCompareVariantIds(validIds);

                emptyState?.classList.toggle('hidden', validIds.length > 0);
                content?.classList.toggle('hidden', validIds.length === 0);
                clearBtn?.classList.toggle('hidden', validIds.length === 0);
                clearBtn?.classList.toggle('inline-flex', validIds.length > 0);

                if (countLabel) countLabel.textContent = validIds.length;
                if (aiBtn) aiBtn.disabled = validIds.length < 2;
            }

            if (validIds.length === 0) {
                list.innerHTML = '';
                return;
            }

            list.innerHTML = items.map((item) => renderVariantCard(item)).join('');
        } catch (error) {
            list.innerHTML = `
                <div class="col-span-full rounded-3xl border border-red-100 bg-red-50 p-6 text-sm font-bold text-red-600 shadow-xs">
                    ${escapeHtml(error.message || 'Không tải được dữ liệu so sánh')}
                </div>
            `;
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function renderVariantCard(item) {
        const productName = escapeHtml(item.ten_san_pham || 'Sản phẩm');
        const variantName = escapeHtml(item.ten_bien_the || 'Bản mặc định');
        const categoryName = escapeHtml(item.ten_danh_muc || 'Chưa phân loại');
        const imageUrl = escapeHtml(item.link_anh || 'https://via.placeholder.com/300x300?text=VNTech');
        const productUrl = item.url ? escapeHtml(item.url) : '#';
        const variantId = escapeHtml(item.ma_bien_the);

        return `
            <article class="relative rounded-3xl border border-slate-200 bg-white p-4 shadow-xs flex gap-4 items-center hover:shadow-md transition-all duration-300">
                <!-- Left: Image -->
                <a href="${productUrl}"
                   title="Xem chi tiết sản phẩm"
                   class="flex h-20 w-20 sm:h-24 sm:w-24 shrink-0 items-center justify-center rounded-2xl bg-slate-50 p-2 transition hover:border-brand-500 hover:bg-orange-50/30 hover:ring-2 hover:ring-orange-100">
                    <img src="${imageUrl}" alt="${productName}" class="h-full w-full object-contain">
                </a>

                <!-- Right: Information -->
                <div class="flex-grow min-w-0 pr-6">
                    <p class="text-[9px] font-black uppercase tracking-widest text-brand-500">${categoryName}</p>
                    <a href="${productUrl}" class="mt-0.5 block font-display text-sm font-black leading-snug text-slate-900 hover:text-brand-500 line-clamp-2" title="${productName}">
                        ${productName}
                    </a>
                    <p class="mt-0.5 text-[11px] font-bold text-slate-500 truncate">${variantName}</p>
                    <p class="mt-1.5 font-display text-base font-black text-[#E04F2A]">${formatCurrency(item.gia_ban)}</p>
                </div>

                <!-- Absolute Close Button on Top-Right -->
                <button type="button"
                        data-compare-remove="${variantId}"
                        class="absolute top-3 right-3 rounded-xl p-1.5 text-slate-400 transition hover:bg-red-50 hover:text-red-500"
                        title="Xóa khỏi danh sách">
                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                </button>
            </article>
        `;
    }

    // Persist compare IDs and keep the global header badge in sync.
    function saveCompareVariantIds(ids) {
        localStorage.setItem(window.VNTECH_COMPARE_STORAGE_KEY, JSON.stringify(ids));
        window.updateCompareCount?.();
    }

    // Remove one variant from the client-side compare list.
    function removeCompareVariant(id) {
        const ids = (window.getCompareVariantIds ? window.getCompareVariantIds() : []).filter((item) => item !== id);
        saveCompareVariantIds(ids);
        renderCompareView();
    }

    function setAiLoading(isLoading) {
        document.getElementById('compare-ai-panel')?.classList.toggle('is-loading', isLoading);
    }

    // Ask the backend AI route to compare selected variants and render markdown safely.
    async function runAiComparison(button) {
        const ids = window.getCompareVariantIds ? window.getCompareVariantIds() : [];
        const result = document.getElementById('compare-ai-result');

        if (ids.length < 2 || !result) return;

        try {
            button.disabled = true;
            setAiLoading(true);
            result.textContent = 'AI đang phân tích dữ liệu sản phẩm...';

            const response = await fetch(compareAiUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ variant_ids: ids }),
            });

            const payload = await response.json();
            const message = payload.message || 'Không có nội dung so sánh.';

            if (payload.success && window.marked && window.DOMPurify) {
                result.innerHTML = window.DOMPurify.sanitize(window.marked.parse(message), {
                    USE_PROFILES: { html: true },
                    ALLOWED_ATTR: ['href', 'title', 'target', 'rel'],
                });
            } else {
                result.textContent = message;
            }
        } catch (error) {
            result.textContent = 'Không gọi được route so sánh AI.';
        } finally {
            setAiLoading(false);
            button.disabled = ids.length < 2;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderCompareView();

        // Clear all selected variants.
        document.getElementById('compare-clear-btn')?.addEventListener('click', function () {
            saveCompareVariantIds([]);
            renderCompareView();
        });

        document.getElementById('compare-ai-btn')?.addEventListener('click', function () {
            runAiComparison(this);
        });

        // Event delegation for remove buttons generated inside #compare-list.
        document.getElementById('compare-list')?.addEventListener('click', function (event) {
            const removeButton = event.target.closest('[data-compare-remove]');
            if (!removeButton) return;

            removeCompareVariant(removeButton.dataset.compareRemove);
        });
    });
</script>
@endsection
