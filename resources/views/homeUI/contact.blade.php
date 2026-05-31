@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#121414] text-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-16">
        <!-- Hero Section -->
        <div class="text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tight uppercase text-white">
                LIÊN HỆ <span class="text-lime-400 drop-shadow-[0_0_15px_rgba(163,230,53,0.3)]">VNTECH</span>
            </h1>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base">
                Có câu hỏi hoặc muốn đóng góp ý kiến? Hãy gửi lời nhắn cho chúng tôi, đội ngũ VNTech luôn sẵn lòng trợ giúp.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Column: Contact info -->
            <div class="lg:col-span-5 space-y-8">
                <div class="bg-slate-950/40 border border-white/5 p-8 rounded-3xl space-y-6">
                    <h2 class="text-xl font-bold uppercase tracking-wider text-lime-400 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5"></i> THÔNG TIN LIÊN HỆ
                    </h2>
                    
                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-lime-400/10 flex items-center justify-center text-lime-400 shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Địa chỉ văn phòng</h4>
                                <p class="text-white text-sm mt-1">280 An Dương Vương, Phường 4, Quận 5, TP. Hồ Chí Minh</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-lime-400/10 flex items-center justify-center text-lime-400 shrink-0">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Hotline hỗ trợ</h4>
                                <p class="text-white text-sm mt-1">1900 8198 (8:00 - 22:00 hàng ngày)</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-lime-400/10 flex items-center justify-center text-lime-400 shrink-0">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Hòm thư điện tử</h4>
                                <p class="text-white text-sm mt-1">support@vntechstore.vn</p>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-lime-400/10 flex items-center justify-center text-lime-400 shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Thời gian làm việc</h4>
                                <p class="text-white text-sm mt-1">Thứ 2 - Chủ Nhật (8:00 AM - 10:00 PM)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connect Socials -->
                <div class="bg-slate-950/40 border border-white/5 p-8 rounded-3xl space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Kết nối với cộng đồng VNTech</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 hover:bg-lime-400 hover:text-black border border-white/10 flex items-center justify-center transition-all duration-300">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 hover:bg-lime-400 hover:text-black border border-white/10 flex items-center justify-center transition-all duration-300">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 hover:bg-lime-400 hover:text-black border border-white/10 flex items-center justify-center transition-all duration-300">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 hover:bg-lime-400 hover:text-black border border-white/10 flex items-center justify-center transition-all duration-300">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Contact form -->
            <div class="lg:col-span-7">
                <form action="#" method="POST" class="bg-slate-950/40 border border-white/5 p-8 md:p-10 rounded-3xl space-y-6">
                    @csrf
                    <h2 class="text-xl font-bold uppercase tracking-wider text-lime-400 flex items-center gap-2">
                        <i data-lucide="send" class="w-5 h-5"></i> GỬI YÊU CẦU LIÊN HỆ
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Họ và tên</label>
                            <input type="text" name="name" required 
                                   class="w-full bg-white/[0.03] border border-white/10 focus:border-lime-400/50 p-4 text-white text-sm rounded-2xl outline-none transition-all duration-300 focus:bg-lime-400/5" 
                                   placeholder="Họ tên của bạn..." />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Địa chỉ Email</label>
                            <input type="email" name="email" required 
                                   class="w-full bg-white/[0.03] border border-white/10 focus:border-lime-400/50 p-4 text-white text-sm rounded-2xl outline-none transition-all duration-300 focus:bg-lime-400/5" 
                                   placeholder="example@gmail.com" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Chủ đề liên hệ</label>
                        <input type="text" name="subject" required 
                               class="w-full bg-white/[0.03] border border-white/10 focus:border-lime-400/50 p-4 text-white text-sm rounded-2xl outline-none transition-all duration-300 focus:bg-lime-400/5" 
                               placeholder="Hỏi về bảo hành, tư vấn sản phẩm, v.v..." />
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Nội dung tin nhắn</label>
                        <textarea name="message" rows="5" required 
                                  class="w-full bg-white/[0.03] border border-white/10 focus:border-lime-400/50 p-4 text-white text-sm rounded-2xl outline-none transition-all duration-300 focus:bg-lime-400/5 resize-none" 
                                  placeholder="Nhập nội dung chi tiết ở đây..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-lime-400 hover:bg-lime-500 text-black font-black uppercase text-xs tracking-widest transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-[0_0_30px_rgba(163,230,53,0.2)] rounded-2xl">
                        Gửi lời nhắn
                    </button>
                </form>
            </div>
        </div>

        <!-- Sleek Dark Themed Google Map Mockup -->
        <div class="border border-white/5 rounded-3xl overflow-hidden aspect-video max-h-[400px] w-full relative group">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.6697269725916!2d106.6796836113576!3d10.760049289343716!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1m3!1d3919.6697269725916!2d106.6796836113576!3d10.760049289343716!2m2!1d106.6796836113576!2d10.760049289343716!5e0!3m2!1svi!2s!4v1716945892556!5m2!1svi!2s" 
                width="100%" 
                height="100%" 
                style="border:0; filter: invert(90%) hue-rotate(180deg) grayscale(40%) contrast(90%);" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <div class="absolute inset-0 pointer-events-none border border-lime-400/20 group-hover:border-lime-400/40 rounded-3xl transition-all duration-300"></div>
        </div>
    </div>
</div>
@endsection
