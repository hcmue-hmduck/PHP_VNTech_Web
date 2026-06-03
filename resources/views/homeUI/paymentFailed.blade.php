@extends('layouts.app')

@section('title', 'Thanh toán không thành công | VNTech')

@section('content')
<section class="relative min-h-[calc(100vh-8rem)] overflow-hidden bg-[#FAF8F2] px-6 py-20 flex items-center justify-center">
    {{-- Background decorations --}}
    <div class="absolute inset-0 opacity-40 pointer-events-none">
        <div class="absolute -top-40 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-red-200/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-brand-200/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto flex max-w-xl flex-col items-center w-full z-10">
        <div class="w-full bg-white border border-slate-150 rounded-3xl p-8 md:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.035)] text-center">
            
            {{-- Icon --}}
            <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-red-200 bg-red-50 text-red-500 mx-auto shadow-xs">
                <i data-lucide="circle-x" class="h-10 w-10"></i>
            </div>

            {{-- Label --}}
            <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.3em] text-red-500">
                Giao dịch thất bại
            </p>

            {{-- Title --}}
            <h1 class="font-space text-2xl font-black uppercase tracking-tight text-slate-800">
                Thanh toán không thành công
            </h1>

            {{-- Description --}}
            <p class="mt-4 text-xs leading-relaxed text-slate-500 max-w-md mx-auto">
                Giao dịch của bạn chưa được hoàn tất. Đơn hàng vẫn được lưu giữ trong hệ thống — bạn có thể kiểm tra và tiến hành thanh toán lại từ trang lịch sử đơn hàng.
            </p>

            {{-- Error info card --}}
            <div class="mt-8 rounded-2xl bg-slate-50 border border-slate-100 p-5 text-left">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <i data-lucide="message-square-warning" class="h-4.5 w-4.5"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Chi tiết phản hồi</p>
                        <p class="mt-1 text-xs font-bold text-slate-700 leading-snug">
                            {{ $message ?? 'Không có thông tin lỗi từ cổng giao dịch.' }}
                        </p>
                    </div>
                </div>

                {{-- Details grid --}}
                @if(!empty($orderId) || isset($resultCode))
                <div class="mt-4 pt-4 border-t border-slate-200/60 grid gap-3 sm:grid-cols-2">
                    @if(!empty($orderId))
                    <div class="rounded-xl border border-slate-200/50 bg-white p-3">
                        <p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Mã đơn hàng</p>
                        <p class="mt-1 break-all font-mono text-[11px] font-bold text-accent-600">{{ $orderId }}</p>
                    </div>
                    @endif
                    @if(isset($resultCode))
                    <div class="rounded-xl border border-slate-200/50 bg-white p-3">
                        <p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Mã lỗi (Code)</p>
                        <p class="mt-1 font-mono text-[11px] font-bold text-red-600">{{ $resultCode }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col gap-3 sm:flex-row justify-center">
                @if(!empty($orderId))
                <a href="{{ route('order_detail.view', ['ma_don_hang' => $orderId]) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 hover:shadow-[0_4px_15px_rgba(255,79,0,0.25)] text-white px-6 py-3.5 text-xs font-black uppercase tracking-wider transition-all duration-300">
                    <i data-lucide="package" class="h-4 w-4"></i>
                    Xem đơn hàng
                </a>
                @endif

                <a href="{{ route('home.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 px-6 py-3.5 text-xs font-black uppercase tracking-wider transition-all duration-300">
                    <i data-lucide="house" class="h-4 w-4"></i>
                    Quay lại trang chủ
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
