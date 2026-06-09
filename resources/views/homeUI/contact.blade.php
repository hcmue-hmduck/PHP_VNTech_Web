@extends('layouts.app')
@section('title', 'Liên hệ VNTech')

@section('content')
@php
    $contact = config('support_policies.contact');
    $contactItems = [
        $contact['address'],
        $contact['hotline'],
        $contact['email'],
        $contact['working_hours'],
    ];
@endphp

<!-- Link Google Fonts & Google Material Symbols Icons -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<style>
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
</style>

<div class="max-w-7xl mx-auto px-6 md:px-12 py-12 bg-[#fcf9f8] rounded-3xl my-8 border border-[#e4beb1]/35 shadow-xs text-[#1c1b1b]">
    <div class="space-y-12">
        <!-- Hero Section -->
        <div class="text-center space-y-3">
            <h1 class="font-display text-3xl md:text-5xl font-black tracking-tight uppercase text-gray-900">
                LIÊN HỆ <span class="text-[#ff5c00] drop-shadow-[0_0_15px_rgba(255,92,0,0.1)]">VNTECH</span>
            </h1>
            <p class="font-sans text-[#5b4137]/80 max-w-xl mx-auto text-sm md:text-base leading-relaxed">
                Có câu hỏi hoặc muốn đóng góp ý kiến? Hãy gửi lời nhắn cho chúng tôi, đội ngũ VNTech luôn sẵn lòng trợ giúp.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Contact info -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl space-y-6">
                    <h2 class="font-display text-lg font-bold uppercase tracking-wider text-[#a73a00] flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">info</span> 
                        Thông tin liên hệ
                    </h2>
                    
                    <div class="space-y-6">
                        @foreach($contactItems as $item)
                            <div class="flex items-start gap-4 text-left">
                                <div class="w-10 h-10 rounded-xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] shrink-0">
                                    <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ $item['label'] }}</h4>
                                    <p class="text-gray-900 text-sm font-medium mt-1">{{ $item['display'] ?? $item['value'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Connect Socials -->
                <div class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl space-y-4 text-left">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Kết nối với cộng đồng VNTech</h3>
                    <div class="flex gap-3">
                        @foreach($contact['socials'] as $social)
                            <a href="{{ $social['url'] }}" class="w-11 h-11 rounded-xl bg-gray-50 hover:bg-[#ffdbce]/45 text-gray-600 hover:text-[#a73a00] border border-gray-150 flex items-center justify-center transition-all duration-300 shadow-xs" title="{{ $social['name'] }}">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="{{ $social['svg_path'] }}"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Contact form -->
            <div class="lg:col-span-7">
                <form action="#" method="POST" class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl space-y-5 text-left shadow-xs">
                    @csrf
                    <h2 class="font-display text-lg font-bold uppercase tracking-wider text-[#a73a00] flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">send</span>
                        Gửi yêu cầu liên hệ
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-550">Họ và tên</label>
                            <input type="text" name="name" required 
                                   class="w-full bg-gray-50/50 border border-gray-200 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] p-3 text-gray-900 text-sm rounded-xl outline-none transition-all duration-300" 
                                   placeholder="Họ tên của bạn..." />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-550">Địa chỉ Email</label>
                            <input type="email" name="email" required 
                                   class="w-full bg-gray-50/50 border border-gray-200 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] p-3 text-gray-900 text-sm rounded-xl outline-none transition-all duration-300" 
                                   placeholder="example@gmail.com" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-550">Chủ đề liên hệ</label>
                        <input type="text" name="subject" required 
                               class="w-full bg-gray-50/50 border border-gray-200 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] p-3 text-gray-900 text-sm rounded-xl outline-none transition-all duration-300" 
                               placeholder="Hỏi về bảo hành, tư vấn sản phẩm, v.v..." />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-550">Nội dung tin nhắn</label>
                        <textarea name="message" rows="5" required 
                                  class="w-full bg-gray-50/50 border border-gray-200 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] p-3 text-gray-900 text-sm rounded-xl outline-none transition-all duration-300 resize-none leading-relaxed" 
                                  placeholder="Nhập nội dung chi tiết ở đây..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full py-3.5 bg-[#a73a00] hover:bg-[#ff5c00] text-white font-bold uppercase text-xs tracking-widest transition-all duration-300 hover:scale-[1.01] active:scale-[0.99] rounded-xl shadow-md shadow-[#a73a00]/10 cursor-pointer">
                        Gửi lời nhắn
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    });
</script>
@endsection
