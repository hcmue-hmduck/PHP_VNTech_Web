@extends('layouts.app')

@section('title', 'Thanh toán không thành công | VNTech')

@section('content')
<section class="relative min-h-[calc(100vh-5rem)] overflow-hidden bg-[#0f1111] px-6 py-20">
    {{-- Background decorations --}}
    <div class="absolute inset-0 opacity-40 pointer-events-none">
        <div class="absolute -top-40 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-red-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-orange-500/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.05)_1px,transparent_1px)] [background-size:34px_34px]"></div>
    </div>

    <div class="relative mx-auto flex max-w-2xl flex-col items-center text-center">

        {{-- Icon --}}
        <div class="mb-8 flex h-24 w-24 items-center justify-center rounded-full border border-red-400/30 bg-red-500/10 shadow-[0_0_50px_rgba(248,113,113,0.2)]">
            <i data-lucide="circle-x" class="h-12 w-12 text-red-400"></i>
        </div>

        {{-- Label --}}
        <p class="mb-3 text-xs font-bold uppercase tracking-[0.35em] text-red-400/70">
            Giao dịch thất bại
        </p>

        {{-- Title --}}
        <h1 class="font-space text-4xl font-black uppercase tracking-tight text-white md:text-5xl">
            Thanh toán không<br>thành công
        </h1>

        {{-- Description --}}
        <p class="mt-5 max-w-lg text-base leading-7 text-slate-400">
            Giao dịch của bạn chưa được hoàn tất. Đơn hàng vẫn được giữ nguyên trong hệ thống — bạn có thể kiểm tra và thử lại trong mục đơn hàng.
        </p>

        {{-- Error info card --}}
        <div class="mt-10 w-full rounded-3xl border border-white/10 bg-white/[0.04] p-6 text-left shadow-2xl backdrop-blur-xl md:p-8">
            {{-- Reason --}}
            <div class="flex items-start gap-4">
                <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-red-500/10 text-red-300">
                    <i data-lucide="message-square-warning" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Lý do từ cổng thanh toán</p>
                    <p class="mt-2 text-base font-semibold text-white">
                        {{ $message ?? 'Không có thông tin lỗi.' }}
                    </p>
                </div>
            </div>

            {{-- Details grid --}}
            @if(!empty($orderId) || isset($resultCode))
            <div class="mt-6 grid gap-3 border-t border-white/10 pt-6 sm:grid-cols-2">
                @if(!empty($orderId))
                <div class="rounded-2xl border border-white/5 bg-black/20 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Mã đơn hàng</p>
                    <p class="mt-2 break-all font-mono text-sm text-lime-300">{{ $orderId }}</p>
                </div>
                @endif
                @if(isset($resultCode))
                <div class="rounded-2xl border border-white/5 bg-black/20 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Mã lỗi</p>
                    <p class="mt-2 font-mono text-sm text-red-300">{{ $resultCode }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="mt-10 flex flex-col gap-4 sm:flex-row">
            @if(!empty($orderId))
            <a href="{{ route('order_detail.view', ['ma_don_hang' => $orderId]) }}"
               class="inline-flex items-center justify-center gap-3 rounded-full bg-white/10 border border-white/15 px-8 py-4 text-sm font-black uppercase tracking-widest text-white transition hover:bg-white/15 hover:border-white/30">
                <i data-lucide="package" class="h-4 w-4"></i>
                Xem đơn hàng
            </a>
            @endif

            <a href="{{ route('viewHome') }}"
               class="inline-flex items-center justify-center gap-3 rounded-full border border-white/10 bg-white/5 px-8 py-4 text-sm font-black uppercase tracking-widest text-white/70 transition hover:border-white/20 hover:text-white">
                <i data-lucide="house" class="h-4 w-4"></i>
                Về trang chủ
            </a>
        </div>

    </div>
</section>
@endsection
