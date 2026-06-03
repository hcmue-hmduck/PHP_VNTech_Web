@extends('layouts.app')
@section('title', 'Trung tâm hỗ trợ và Chính sách VNTech')

@section('content')
<!-- Link Google Fonts & Google Material Symbols Icons -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<style>
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
</style>

<div class="max-w-6xl mx-auto px-6 md:px-12 py-12 bg-[#fcf9f8] rounded-3xl my-8 border border-[#e4beb1]/35 shadow-xs text-[#1c1b1b]"
     x-data="{ currentTab: 'faqs', activeFaq: null }">
    <div class="space-y-10">
        <!-- Hero Section -->
        <div class="text-center space-y-3">
            <h1 class="font-display text-3xl md:text-5xl font-black tracking-tight uppercase text-gray-900">
                HỖ TRỢ & <span class="text-[#ff5c00] drop-shadow-[0_0_15px_rgba(255,92,0,0.1)]">CHÍNH SÁCH</span>
            </h1>
            <p class="font-sans text-[#5b4137]/80 max-w-xl mx-auto text-sm md:text-base leading-relaxed">
                Giải đáp nhanh chóng các câu hỏi thường gặp và tham khảo chính sách mua sắm tại VNTech.
            </p>
        </div>

        <!-- Navigation Tab Selection Bar -->
        <div class="flex flex-wrap items-center justify-center gap-2 border-b border-[#e4beb1]/20 pb-4">
            <button @click="currentTab = 'faqs'"
                    :class="currentTab === 'faqs'
                        ? 'bg-[#ff5c00] text-white shadow-md shadow-[#ff5c00]/20'
                        : 'bg-white border border-[#e4beb1]/30 text-gray-600 hover:text-[#a73a00]'"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">help</span>
                Hỏi đáp & Hướng dẫn
            </button>

            <button @click="currentTab = 'warranty'"
                    :class="currentTab === 'warranty'
                        ? 'bg-[#ff5c00] text-white shadow-md shadow-[#ff5c00]/20'
                        : 'bg-white border border-[#e4beb1]/30 text-gray-600 hover:text-[#a73a00]'"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">verified_user</span>
                Chính sách bảo hành
            </button>

            <button @click="currentTab = 'return'"
                    :class="currentTab === 'return'
                        ? 'bg-[#ff5c00] text-white shadow-md shadow-[#ff5c00]/20'
                        : 'bg-white border border-[#e4beb1]/30 text-gray-600 hover:text-[#a73a00]'"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">sync</span>
                Chính sách đổi trả
            </button>

            <button @click="currentTab = 'privacy'"
                    :class="currentTab === 'privacy'
                        ? 'bg-[#ff5c00] text-white shadow-md shadow-[#ff5c00]/20'
                        : 'bg-white border border-[#e4beb1]/30 text-gray-600 hover:text-[#a73a00]'"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">lock</span>
                Bảo mật thông tin
            </button>

            <button @click="currentTab = 'shipping'"
                    :class="currentTab === 'shipping'
                        ? 'bg-[#ff5c00] text-white shadow-md shadow-[#ff5c00]/20'
                        : 'bg-white border border-[#e4beb1]/30 text-gray-600 hover:text-[#a73a00]'"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">local_shipping</span>
                Vận chuyển giao nhận
            </button>
        </div>

        <!-- MAIN DYNAMIC CONTENT CONTAINER -->
        <div>
            <!-- 1. TAB CONTENT: FAQS & QUICK CATEGORIES -->
            <div x-show="currentTab === 'faqs'" class="space-y-12" x-transition:enter="transition ease-out duration-250">
                <!-- Support Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div @click="currentTab = 'shipping'"
                         class="bg-white border border-[#e4beb1]/35 p-6 rounded-3xl hover:border-[#ff5c00]/40 transition-all duration-300 group flex flex-col justify-between cursor-pointer text-left shadow-xs">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-xl">local_shipping</span>
                            </div>
                            <h3 class="text-base font-bold uppercase tracking-wider text-gray-900">Vận chuyển & Giao nhận</h3>
                            <p class="text-gray-600 text-xs md:text-sm leading-relaxed">Theo dõi đơn hàng của bạn, thời gian vận chuyển dự kiến và các chính sách giao hàng hỏa tốc.</p>
                        </div>
                        <span class="text-[#a73a00] font-bold text-xs uppercase tracking-widest mt-6 inline-flex items-center gap-1 group-hover:translate-x-1 transition-all">
                            Xem chính sách <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
                        </span>
                    </div>

                    <div @click="currentTab = 'warranty'"
                         class="bg-white border border-[#e4beb1]/35 p-6 rounded-3xl hover:border-[#ff5c00]/40 transition-all duration-300 group flex flex-col justify-between cursor-pointer text-left shadow-xs">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-xl">verified_user</span>
                            </div>
                            <h3 class="text-base font-bold uppercase tracking-wider text-gray-900">Bảo hành & Sửa chữa</h3>
                            <p class="text-gray-600 text-xs md:text-sm leading-relaxed">Quy trình đăng ký bảo hành phần cứng, thời hạn bảo hành cho phím cơ, chuột Gaming và linh kiện.</p>
                        </div>
                        <span class="text-[#a73a00] font-bold text-xs uppercase tracking-widest mt-6 inline-flex items-center gap-1 group-hover:translate-x-1 transition-all">
                            Xem chính sách <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
                        </span>
                    </div>

                    <div @click="activeFaq = 4"
                         class="bg-white border border-[#e4beb1]/35 p-6 rounded-3xl hover:border-[#ff5c00]/40 transition-all duration-300 group flex flex-col justify-between cursor-pointer text-left shadow-xs">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-xl">settings</span>
                            </div>
                            <h3 class="text-base font-bold uppercase tracking-wider text-gray-900">Cài đặt & Driver</h3>
                            <p class="text-gray-600 text-xs md:text-sm leading-relaxed">Tải về driver, phần mềm điều chỉnh LED RGB, hướng dẫn cấu hình macro cho chuột và phím cơ.</p>
                        </div>
                        <span class="text-[#a73a00] font-bold text-xs uppercase tracking-widest mt-6 inline-flex items-center gap-1 group-hover:translate-x-1 transition-all">
                            Xem hướng dẫn <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
                        </span>
                    </div>
                </div>

                <!-- FAQs Accordion -->
                <div class="space-y-6">
                    <h2 class="font-display text-lg md:text-xl font-bold uppercase tracking-wide text-gray-950 flex items-center gap-2 justify-start">
                        <span class="material-symbols-outlined text-[#a73a00]">help_outline</span> 
                        Câu hỏi thường gặp (FAQs)
                    </h2>

                    <div class="space-y-4">
                        <!-- FAQ 1 -->
                        <div class="border border-[#e4beb1]/30 bg-white rounded-2xl overflow-hidden shadow-2xs">
                            <button @click="activeFaq = activeFaq === 1 ? null : 1" 
                                    class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50/50 transition-colors">
                                <span class="font-bold text-sm md:text-base text-gray-900">Làm thế nào để tôi có thể kích hoạt bảo hành điện tử?</span>
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                      :class="activeFaq === 1 ? 'rotate-180 text-[#ff5c00]' : ''">keyboard_arrow_down</span>
                            </button>
                            <div x-show="activeFaq === 1" 
                                 class="px-5 pb-5 text-gray-650 text-xs md:text-sm leading-relaxed border-t border-neutral-100 pt-4"
                                 style="display: none;">
                                Tất cả các sản phẩm mua tại VNTech đều được kích hoạt bảo hành điện tử tự động qua số điện thoại mua hàng. Bạn không cần giữ hóa đơn giấy, chỉ cần cung cấp số điện thoại hoặc mã đơn hàng khi liên hệ bộ phận hỗ trợ kỹ thuật để được kiểm tra hạn bảo hành.
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="border border-[#e4beb1]/30 bg-white rounded-2xl overflow-hidden shadow-2xs">
                            <button @click="activeFaq = activeFaq === 2 ? null : 2" 
                                    class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50/50 transition-colors">
                                <span class="font-bold text-sm md:text-base text-gray-900">Thời gian giao hàng mất bao lâu?</span>
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                      :class="activeFaq === 2 ? 'rotate-180 text-[#ff5c00]' : ''">keyboard_arrow_down</span>
                            </button>
                            <div x-show="activeFaq === 2" 
                                 class="px-5 pb-5 text-gray-650 text-xs md:text-sm leading-relaxed border-t border-neutral-100 pt-4"
                                 style="display: none;">
                                Đối với khu vực nội thành, VNTech hỗ trợ giao hàng hỏa tốc trong vòng 2 - 4 giờ. Đối với các tỉnh thành khác trên toàn quốc, thời gian vận chuyển dao động từ 2 - 4 ngày làm việc tùy thuộc vào đơn vị vận chuyển đối tác.
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="border border-[#e4beb1]/30 bg-white rounded-2xl overflow-hidden shadow-2xs">
                            <button @click="activeFaq = activeFaq === 3 ? null : 3" 
                                    class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50/50 transition-colors">
                                <span class="font-bold text-sm md:text-base text-gray-900">Tôi có thể trả lại hàng nếu không ưng ý không?</span>
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                      :class="activeFaq === 3 ? 'rotate-180 text-[#ff5c00]' : ''">keyboard_arrow_down</span>
                            </button>
                            <div x-show="activeFaq === 3" 
                                 class="px-5 pb-5 text-gray-650 text-xs md:text-sm leading-relaxed border-t border-neutral-100 pt-4"
                                 style="display: none;">
                                Có, VNTech hỗ trợ đổi trả sản phẩm trong vòng 7 ngày đầu kể từ ngày nhận hàng đối với các sản phẩm còn nguyên seal hộp, chưa qua sử dụng và không có dấu hiệu hư hại vật lý. Vui lòng tham khảo kỹ Chính Sách Đổi Trả để biết chi tiết quy trình.
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="border border-[#e4beb1]/30 bg-white rounded-2xl overflow-hidden shadow-2xs">
                            <button @click="activeFaq = activeFaq === 4 ? null : 4" 
                                    class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50/50 transition-colors">
                                <span class="font-bold text-sm md:text-base text-gray-900">Làm thế nào để tải phần mềm Driver cho thiết bị?</span>
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                      :class="activeFaq === 4 ? 'rotate-180 text-[#ff5c00]' : ''">keyboard_arrow_down</span>
                            </button>
                            <div x-show="activeFaq === 4" 
                                 class="px-5 pb-5 text-gray-650 text-xs md:text-sm leading-relaxed border-t border-neutral-100 pt-4"
                                 style="display: none;">
                                Bạn có thể tải về driver, phần mềm điều khiển driver mới nhất bằng cách liên hệ tổng đài hỗ trợ kỹ thuật hoặc truy cập trực tiếp website chính thức của các đối tác thương hiệu lớn (như Logitech, Razer, Akko, Keychron, v.v.).
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support CTA -->
                <div class="bg-gradient-to-r from-[#ffdbce]/40 to-[#ffdbce]/10 border border-[#e4beb1]/35 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-6 text-left shadow-xs">
                    <div class="space-y-1.5">
                        <h3 class="font-display text-lg font-bold text-gray-905 uppercase tracking-wide">Vẫn không tìm thấy câu trả lời?</h3>
                        <p class="text-gray-600 text-xs md:text-sm max-w-lg leading-relaxed">Liên hệ trực tiếp với bộ phận chăm sóc độc giả của chúng tôi để được giải đáp mọi vướng mắc 24/7.</p>
                    </div>
                    <a href="{{ route('contact') }}" 
                       class="px-8 py-3.5 bg-[#a73a00] hover:bg-[#ff5c00] text-white font-bold uppercase text-xs tracking-widest transition-all duration-300 rounded-xl shrink-0 shadow-md shadow-[#a73a00]/10 cursor-pointer">
                        Gửi yêu cầu hỗ trợ
                    </a>
                </div>
            </div>

            <!-- 2. TAB CONTENT: WARRANTY POLICY -->
            <div x-show="currentTab === 'warranty'" 
                 class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl shadow-xs text-left space-y-6" 
                 style="display: none;" 
                 x-transition:enter="transition ease-out duration-250">
                <h2 class="font-display text-lg font-bold uppercase tracking-wider text-[#a73a00] flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="material-symbols-outlined text-xl">verified_user</span>
                    Chính sách bảo hành thiết bị
                </h2>
                <div class="space-y-4 text-gray-650 text-sm leading-relaxed">
                    <p class="font-bold text-gray-900">1. Thời gian bảo hành tiêu chuẩn:</p>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li>Bàn phím cơ cơ học: Bảo hành 24 tháng (lỗi switch, mạch điện tử).</li>
                        <li>Chuột Gaming & Tai nghe: Bảo hành 12 tháng (lỗi mắt đọc, double click, âm thanh).</li>
                        <li>Tay cầm chơi game & Phụ kiện khác: Bảo hành 6 tháng.</li>
                    </ul>
                    <p class="font-bold text-gray-900 mt-4">2. Điều kiện được bảo hành:</p>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li>Sản phẩm còn trong thời hạn bảo hành điện tử đăng ký trên hệ thống.</li>
                        <li>Sản phẩm gặp lỗi kỹ thuật từ nhà sản xuất phát sinh trong quá trình sử dụng bình thường.</li>
                        <li>Tem bảo hành (nếu có) phải còn nguyên vẹn, không có dấu hiệu bị cạy mở, rách nát.</li>
                    </ul>
                    <p class="font-bold text-gray-900 mt-4">3. Trường hợp bị từ chối bảo hành:</p>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li>Sản phẩm bị dính nước, chất lỏng hoặc có vết ẩm mốc bên trong mạch.</li>
                        <li>Sản phẩm bị va đập, nứt vỡ, trầy xước nặng, biến dạng do lỗi của người dùng.</li>
                        <li>Đã tự ý mở sản phẩm để sửa chữa trước khi mang đến VNTech.</li>
                    </ul>
                </div>
            </div>

            <!-- 3. TAB CONTENT: RETURN POLICY -->
            <div x-show="currentTab === 'return'" 
                 class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl shadow-xs text-left space-y-6" 
                 style="display: none;" 
                 x-transition:enter="transition ease-out duration-250">
                <h2 class="font-display text-lg font-bold uppercase tracking-wider text-[#a73a00] flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="material-symbols-outlined text-xl">sync</span>
                    Chính sách đổi trả & hoàn tiền
                </h2>
                <div class="space-y-4 text-gray-650 text-sm leading-relaxed">
                    <p class="font-bold text-gray-900">1. Đổi trả miễn phí trong 7 ngày đầu:</p>
                    <p>VNTech hỗ trợ đổi mới sản phẩm cùng loại hoàn toàn miễn phí hoặc hoàn tiền 100% trong vòng 7 ngày đầu tiên nếu sản phẩm gặp lỗi phần cứng do nhà sản xuất.</p>
                    
                    <p class="font-bold text-gray-900 mt-4">2. Đổi trả theo nhu cầu (Sản phẩm không lỗi):</p>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li>Sản phẩm phải còn nguyên seal hộp nguyên vẹn, chưa khui mở hộp.</li>
                        <li>Khách hàng chịu chi phí vận chuyển phát sinh khi gửi hàng đổi trả về trung tâm điều phối của VNTech.</li>
                    </ul>

                    <p class="font-bold text-gray-900 mt-4">3. Quy trình thực hiện đổi trả:</p>
                    <ol class="list-decimal pl-5 space-y-1.5">
                        <li>Liên hệ hotline chăm sóc khách hàng hoặc gửi thông tin qua form Liên hệ.</li>
                        <li>Đóng gói sản phẩm cẩn thận kèm phụ kiện ban đầu và gửi về địa chỉ tiếp nhận bảo hành của VNTech.</li>
                        <li>Sau khi kiểm định trạng thái hàng hóa, VNTech tiến hành gửi sản phẩm mới hoặc hoàn tiền trong 3 ngày làm việc.</li>
                    </ol>
                </div>
            </div>

            <!-- 4. TAB CONTENT: PRIVACY POLICY -->
            <div x-show="currentTab === 'privacy'" 
                 class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl shadow-xs text-left space-y-6" 
                 style="display: none;" 
                 x-transition:enter="transition ease-out duration-250">
                <h2 class="font-display text-lg font-bold uppercase tracking-wider text-[#a73a00] flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="material-symbols-outlined text-xl">lock</span>
                    Chính sách bảo mật thông tin
                </h2>
                <div class="space-y-4 text-gray-650 text-sm leading-relaxed">
                    <p class="font-bold text-gray-900">1. Mục đích thu thập thông tin khách hàng:</p>
                    <p>VNTech thu thập các thông tin cá nhân bao gồm: Họ tên, Email, Số điện thoại và Địa chỉ giao hàng. Các thông tin này chỉ sử dụng cho mục đích xác nhận đơn hàng, liên hệ giao hàng và bảo hành điện tử.</p>

                    <p class="font-bold text-gray-900 mt-4">2. Cam kết bảo mật dữ liệu khách hàng:</p>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li>Chúng tôi sử dụng giao thức bảo mật SSL mã hóa toàn bộ dữ liệu giao dịch tài chính trực tuyến.</li>
                        <li>Tuyệt đối không chia sẻ, mua bán dữ liệu cá nhân của khách hàng cho bất kỳ bên thứ ba nào khi chưa có sự đồng ý bằng văn bản của khách hàng.</li>
                    </ul>

                    <p class="font-bold text-gray-900 mt-4">3. Quyền hạn của khách hàng:</p>
                    <p>Khách hàng có quyền truy cập vào trang cá nhân để sửa đổi thông tin hoặc gửi yêu cầu xóa bỏ tài khoản hoàn toàn khỏi hệ thống của VNTech bất cứ lúc nào.</p>
                </div>
            </div>

            <!-- 5. TAB CONTENT: SHIPPING POLICY -->
            <div x-show="currentTab === 'shipping'" 
                 class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl shadow-xs text-left space-y-6" 
                 style="display: none;" 
                 x-transition:enter="transition ease-out duration-250">
                <h2 class="font-display text-lg font-bold uppercase tracking-wider text-[#a73a00] flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="material-symbols-outlined text-xl">local_shipping</span>
                    Chính sách vận chuyển giao nhận
                </h2>
                <div class="space-y-4 text-gray-650 text-sm leading-relaxed">
                    <p class="font-bold text-gray-900">1. Khu vực giao hàng:</p>
                    <p>VNTech hỗ trợ giao hàng toàn quốc trên khắp 63 tỉnh thành, kết hợp cùng các đơn vị vận chuyển hàng đầu như Giao Hàng Nhanh, Viettel Post, v.v.</p>

                    <p class="font-bold text-gray-900 mt-4">2. Chi phí giao hàng:</p>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li>Nội thành Hồ Chí Minh & Hà Nội: Đồng giá 25,000 VNĐ. Miễn phí cho đơn hàng từ 1,000,000 VNĐ.</li>
                        <li>Tỉnh thành khác: Tính theo bảng giá thực tế của đơn vị vận chuyển.</li>
                    </ul>

                    <p class="font-bold text-gray-900 mt-4">3. Đồng kiểm khi nhận hàng:</p>
                    <p>Khách hàng hoàn toàn có quyền mở hộp kiểm tra ngoại quan sản phẩm trước khi thanh toán cho nhân viên giao hàng (Không hỗ trợ cắm điện dùng thử sản phẩm ngay tại chỗ). Nếu phát hiện móp méo hộp hoặc hư hại ngoại quan, hãy từ chối nhận hàng và báo lại ngay cho VNTech.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
