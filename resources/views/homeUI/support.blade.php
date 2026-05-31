@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#121414] text-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-16">
        <!-- Hero Section -->
        <div class="text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tight uppercase text-white">
                TRUNG TÂM <span class="text-lime-400 drop-shadow-[0_0_15px_rgba(163,230,53,0.3)]">HỖ TRỢ</span>
            </h1>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base">
                Tìm kiếm giải pháp nhanh chóng cho tất cả các câu hỏi của bạn về thiết bị và đơn hàng tại VNTech.
            </p>
        </div>

        <!-- Support Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-950/40 border border-white/5 p-8 rounded-3xl hover:border-lime-400/30 transition-all duration-300 group flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-lime-400/10 flex items-center justify-center text-lime-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="truck" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold uppercase tracking-wider text-white">Vận chuyển & Giao hàng</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Theo dõi đơn hàng của bạn, thời gian vận chuyển dự kiến và các chính sách giao hàng hỏa tốc.</p>
                </div>
                <a href="#faq-delivery" class="text-lime-400 hover:text-white font-bold text-xs uppercase tracking-widest mt-6 inline-flex items-center gap-2 group-hover:translate-x-1 transition-all">
                    Xem chi tiết <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="bg-slate-950/40 border border-white/5 p-8 rounded-3xl hover:border-lime-400/30 transition-all duration-300 group flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-lime-400/10 flex items-center justify-center text-lime-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold uppercase tracking-wider text-white">Bảo hành & Sửa chữa</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Quy trình đăng ký bảo hành phần cứng, thời hạn bảo hành cho phím cơ, chuột Gaming và linh kiện.</p>
                </div>
                <a href="#faq-warranty" class="text-lime-400 hover:text-white font-bold text-xs uppercase tracking-widest mt-6 inline-flex items-center gap-2 group-hover:translate-x-1 transition-all">
                    Xem chi tiết <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="bg-slate-950/40 border border-white/5 p-8 rounded-3xl hover:border-lime-400/30 transition-all duration-300 group flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-lime-400/10 flex items-center justify-center text-lime-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="settings" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold uppercase tracking-wider text-white">Cài đặt & Driver</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Tải về driver, phần mềm điều chỉnh LED RGB, hướng dẫn cấu hình macro cho chuột và phím cơ.</p>
                </div>
                <a href="#faq-software" class="text-lime-400 hover:text-white font-bold text-xs uppercase tracking-widest mt-6 inline-flex items-center gap-2 group-hover:translate-x-1 transition-all">
                    Xem chi tiết <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- FAQs Accordion -->
        <div class="space-y-6">
            <h2 class="text-2xl font-black uppercase tracking-tight text-white mb-8 inline-flex items-center gap-3">
                <i data-lucide="help-circle" class="text-lime-400 w-6 h-6"></i> Câu hỏi thường gặp (FAQs)
            </h2>

            <div x-data="{ activeFaq: null }" class="space-y-4">
                <!-- FAQ 1 -->
                <div class="border border-white/5 bg-slate-950/20 rounded-2xl overflow-hidden transition-colors" :class="activeFaq === 1 ? 'border-lime-400/20' : ''">
                    <button @click="activeFaq = activeFaq === 1 ? null : 1" class="w-full flex justify-between items-center p-6 text-left hover:bg-white/[0.02] transition-colors">
                        <span class="font-bold text-sm md:text-base uppercase tracking-wider">Làm thế nào để tôi có thể kích hoạt bảo hành điện tử?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeFaq === 1 ? 'rotate-180 text-lime-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="px-6 pb-6 text-slate-400 text-sm leading-relaxed border-t border-white/5 pt-4">
                        Tất cả các sản phẩm mua tại VNTech đều được kích hoạt bảo hành điện tử tự động qua số điện thoại mua hàng. Bạn không cần giữ hóa đơn giấy, chỉ cần cung cấp số điện thoại hoặc mã đơn hàng khi liên hệ bộ phận hỗ trợ kỹ thuật để được kiểm tra hạn bảo hành.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-white/5 bg-slate-950/20 rounded-2xl overflow-hidden transition-colors" :class="activeFaq === 2 ? 'border-lime-400/20' : ''">
                    <button @click="activeFaq = activeFaq === 2 ? null : 2" class="w-full flex justify-between items-center p-6 text-left hover:bg-white/[0.02] transition-colors">
                        <span class="font-bold text-sm md:text-base uppercase tracking-wider">Thời gian giao hàng mất bao lâu?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeFaq === 2 ? 'rotate-180 text-lime-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="px-6 pb-6 text-slate-400 text-sm leading-relaxed border-t border-white/5 pt-4">
                        Đối với khu vực nội thành, VNTech hỗ trợ giao hàng hỏa tốc trong vòng 2 - 4 giờ. Đối với các tỉnh thành khác trên toàn quốc, thời gian vận chuyển dao động từ 2 - 4 ngày làm việc tùy thuộc vào đơn vị vận chuyển đối tác.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-white/5 bg-slate-950/20 rounded-2xl overflow-hidden transition-colors" :class="activeFaq === 3 ? 'border-lime-400/20' : ''">
                    <button @click="activeFaq = activeFaq === 3 ? null : 3" class="w-full flex justify-between items-center p-6 text-left hover:bg-white/[0.02] transition-colors">
                        <span class="font-bold text-sm md:text-base uppercase tracking-wider">Tôi có thể trả lại hàng nếu không ưng ý không?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeFaq === 3 ? 'rotate-180 text-lime-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="px-6 pb-6 text-slate-400 text-sm leading-relaxed border-t border-white/5 pt-4">
                        Có, VNTech hỗ trợ đổi trả sản phẩm trong vòng 7 ngày đầu kể từ ngày nhận hàng đối với các sản phẩm còn nguyên seal hộp, chưa qua sử dụng và không có dấu hiệu hư hại vật lý. Vui lòng tham khảo kỹ Chính Sách Đổi Trả để biết chi tiết quy trình.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border border-white/5 bg-slate-950/20 rounded-2xl overflow-hidden transition-colors" :class="activeFaq === 4 ? 'border-lime-400/20' : ''">
                    <button @click="activeFaq = activeFaq === 4 ? null : 4" class="w-full flex justify-between items-center p-6 text-left hover:bg-white/[0.02] transition-colors">
                        <span class="font-bold text-sm md:text-base uppercase tracking-wider">Làm thế nào để tải phần mềm Driver cho thiết bị?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeFaq === 4 ? 'rotate-180 text-lime-400' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse class="px-6 pb-6 text-slate-400 text-sm leading-relaxed border-t border-white/5 pt-4">
                        Bạn có thể truy cập trang hỗ trợ kỹ thuật của VNTech hoặc truy cập trực tiếp website chính thức của thương hiệu sản phẩm đó (như Razer, Logitech, Keychron, v.v.) để tải về phiên bản phần mềm điều khiển driver mới nhất.
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="bg-gradient-to-r from-lime-400/10 to-transparent border border-lime-400/20 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-2 text-center md:text-left">
                <h3 class="text-xl font-bold uppercase tracking-wider text-white">Vẫn không tìm thấy câu trả lời?</h3>
                <p class="text-slate-400 text-sm max-w-lg">Liên hệ trực tiếp với bộ phận chăm sóc khách hàng của chúng tôi để được giải đáp mọi vướng mắc 24/7.</p>
            </div>
            <a href="/lien-he" class="px-8 py-4 bg-lime-400 hover:bg-lime-500 text-black font-black uppercase text-xs tracking-widest transition-all duration-300 hover:scale-105 active:scale-95 shadow-[0_0_30px_rgba(163,230,53,0.2)] rounded-xl shrink-0">
                Gửi yêu cầu hỗ trợ
            </a>
        </div>
    </div>
</div>
@endsection
