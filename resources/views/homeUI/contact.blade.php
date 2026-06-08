@extends('layouts.app')
@section('title', 'Liên hệ VNTech')

@section('content')
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
                        <!-- Address -->
                        <div class="flex items-start gap-4 text-left">
                            <div class="w-10 h-10 rounded-xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] shrink-0">
                                <span class="material-symbols-outlined">map</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Địa chỉ văn phòng</h4>
                                <p class="text-gray-900 text-sm font-medium mt-1">280 An Dương Vương, Phường 4, Quận 5, TP. Hồ Chí Minh</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-4 text-left">
                            <div class="w-10 h-10 rounded-xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] shrink-0">
                                <span class="material-symbols-outlined">call</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Hotline hỗ trợ</h4>
                                <p class="text-gray-900 text-sm font-medium mt-1">1900 8198 (8:00 - 22:00 hàng ngày)</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4 text-left">
                            <div class="w-10 h-10 rounded-xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] shrink-0">
                                <span class="material-symbols-outlined">mail</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Hòm thư điện tử</h4>
                                <p class="text-gray-900 text-sm font-medium mt-1">support@vntechstore.vn</p>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="flex items-start gap-4 text-left">
                            <div class="w-10 h-10 rounded-xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] shrink-0">
                                <span class="material-symbols-outlined">schedule</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">Thời gian làm việc</h4>
                                <p class="text-gray-900 text-sm font-medium mt-1">Thứ 2 - Chủ Nhật (8:00 AM - 10:00 PM)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connect Socials -->
                <div class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl space-y-4 text-left">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Kết nối với cộng đồng VNTech</h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-11 h-11 rounded-xl bg-gray-50 hover:bg-[#ffdbce]/45 text-gray-600 hover:text-[#a73a00] border border-gray-150 flex items-center justify-center transition-all duration-300 shadow-xs" title="Facebook">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-xl bg-gray-50 hover:bg-[#ffdbce]/45 text-gray-600 hover:text-[#a73a00] border border-gray-150 flex items-center justify-center transition-all duration-300 shadow-xs" title="Instagram">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-xl bg-gray-50 hover:bg-[#ffdbce]/45 text-gray-600 hover:text-[#a73a00] border border-gray-150 flex items-center justify-center transition-all duration-300 shadow-xs" title="Youtube">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.11C19.517 3.545 12 3.545 12 3.545s-7.517 0-9.388.507a3.003 3.003 0 0 0-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 0 0 2.11 2.11c1.871.507 9.388.507 9.388.507s7.517 0 9.388-.507a3.003 3.003 0 0 0 2.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 rounded-xl bg-gray-50 hover:bg-[#ffdbce]/45 text-gray-600 hover:text-[#a73a00] border border-gray-150 flex items-center justify-center transition-all duration-300 shadow-xs" title="Twitter">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
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
