@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#121414] text-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-16">
        <!-- Hero Section -->
        <div class="text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tight uppercase text-white">
                CHÌNH SÁCH <span class="text-lime-400 drop-shadow-[0_0_15px_rgba(163,230,53,0.3)]">DỊCH VỤ</span>
            </h1>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base">
                Đọc kỹ các điều khoản và chính sách dưới đây để đảm bảo quyền lợi mua sắm tốt nhất của bạn tại VNTech.
            </p>
        </div>

        <!-- Policy Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ currentTab: 'warranty' }">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-4 space-y-3">
                <button @click="currentTab = 'warranty'" 
                        :class="currentTab === 'warranty' ? 'bg-lime-400/10 border-lime-400 text-lime-400' : 'bg-slate-950/20 border-white/5 text-slate-400 hover:text-white'"
                        class="w-full flex items-center gap-3 px-6 py-4 border rounded-2xl text-left font-bold uppercase text-xs tracking-wider transition-all duration-300">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>Chính sách bảo hành</span>
                </button>

                <button @click="currentTab = 'return'" 
                        :class="currentTab === 'return' ? 'bg-lime-400/10 border-lime-400 text-lime-400' : 'bg-slate-950/20 border-white/5 text-slate-400 hover:text-white'"
                        class="w-full flex items-center gap-3 px-6 py-4 border rounded-2xl text-left font-bold uppercase text-xs tracking-wider transition-all duration-300">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    <span>Chính sách đổi trả</span>
                </button>

                <button @click="currentTab = 'privacy'" 
                        :class="currentTab === 'privacy' ? 'bg-lime-400/10 border-lime-400 text-lime-400' : 'bg-slate-950/20 border-white/5 text-slate-400 hover:text-white'"
                        class="w-full flex items-center gap-3 px-6 py-4 border rounded-2xl text-left font-bold uppercase text-xs tracking-wider transition-all duration-300">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>Bảo mật thông tin</span>
                </button>

                <button @click="currentTab = 'shipping'" 
                        :class="currentTab === 'shipping' ? 'bg-lime-400/10 border-lime-400 text-lime-400' : 'bg-slate-950/20 border-white/5 text-slate-400 hover:text-white'"
                        class="w-full flex items-center gap-3 px-6 py-4 border rounded-2xl text-left font-bold uppercase text-xs tracking-wider transition-all duration-300">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    <span>Vận chuyển & Giao nhận</span>
                </button>
            </div>

            <!-- Policy Content Area -->
            <div class="lg:col-span-8 bg-slate-950/20 border border-white/5 p-8 rounded-3xl min-h-[400px]">
                
                <!-- Tab: Warranty Policy -->
                <div x-show="currentTab === 'warranty'" class="space-y-6">
                    <h2 class="text-xl font-bold uppercase tracking-wider text-lime-400 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-5 h-5"></i> CHÍNH SÁCH BẢO HÀNH THIẾT BỊ
                    </h2>
                    <div class="space-y-4 text-slate-400 text-sm leading-relaxed">
                        <p class="font-bold text-white">1. Thời gian bảo hành tiêu chuẩn:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Bàn phím cơ cơ học: Bảo hành 24 tháng (lỗi switch, mạch điện tử).</li>
                            <li>Chuột Gaming & Tai nghe: Bảo hành 12 tháng (lỗi mắt đọc, double click, âm thanh).</li>
                            <li>Tay cầm chơi game & Phụ kiện khác: Bảo hành 6 tháng.</li>
                        </ul>
                        <p class="font-bold text-white mt-4">2. Điều kiện được bảo hành:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Sản phẩm còn trong thời hạn bảo hành điện tử đăng ký trên hệ thống.</li>
                            <li>Sản phẩm gặp lỗi kỹ thuật từ nhà sản xuất phát sinh trong quá trình sử dụng bình thường.</li>
                            <li>Tem bảo hành (nếu có) phải còn nguyên vẹn, không có dấu hiệu bị cạy mở, rách nát.</li>
                        </ul>
                        <p class="font-bold text-white mt-4">3. Trường hợp bị từ chối bảo hành:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Sản phẩm bị dính nước, chất lỏng hoặc có vết ẩm mốc bên trong mạch.</li>
                            <li>Sản phẩm bị va đập, nứt vỡ, trầy xước nặng, biến dạng do lỗi của người dùng.</li>
                            <li>Đã tự ý mở sản phẩm để sửa chữa trước khi mang đến VNTech.</li>
                        </ul>
                    </div>
                </div>

                <!-- Tab: Return Policy -->
                <div x-show="currentTab === 'return'" class="space-y-6" style="display: none;">
                    <h2 class="text-xl font-bold uppercase tracking-wider text-lime-400 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i> CHÍNH SÁCH ĐỔI TRẢ & HOÀN TIỀN
                    </h2>
                    <div class="space-y-4 text-slate-400 text-sm leading-relaxed">
                        <p class="font-bold text-white">1. Đổi trả miễn phí trong 7 ngày đầu:</p>
                        <p>VNTech hỗ trợ đổi mới sản phẩm cùng loại hoàn toàn miễn phí hoặc hoàn tiền 100% trong vòng 7 ngày đầu tiên nếu sản phẩm gặp lỗi phần cứng do nhà sản xuất.</p>
                        
                        <p class="font-bold text-white mt-4">2. Đổi trả theo nhu cầu (Sản phẩm không lỗi):</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Sản phẩm phải còn nguyên seal hộp nguyên vẹn, chưa khui mở hộp.</li>
                            <li>Khách hàng chịu chi phí vận chuyển phát sinh khi gửi hàng đổi trả về trung tâm điều phối của VNTech.</li>
                        </ul>

                        <p class="font-bold text-white mt-4">3. Quy trình thực hiện đổi trả:</p>
                        <ol class="list-decimal pl-5 space-y-1">
                            <li>Liên hệ hotline chăm sóc khách hàng hoặc gửi thông tin qua form Liên hệ.</li>
                            <li>Đóng gói sản phẩm cẩn thận kèm phụ kiện ban đầu và gửi về địa chỉ tiếp nhận bảo hành của VNTech.</li>
                            <li>Sau khi kiểm định trạng thái hàng hóa, VNTech tiến hành gửi sản phẩm mới hoặc hoàn tiền trong 3 ngày làm việc.</li>
                        </ol>
                    </div>
                </div>

                <!-- Tab: Privacy Policy -->
                <div x-show="currentTab === 'privacy'" class="space-y-6" style="display: none;">
                    <h2 class="text-xl font-bold uppercase tracking-wider text-lime-400 flex items-center gap-2">
                        <i data-lucide="lock" class="w-5 h-5"></i> CHÍNH SÁCH BẢO MẬT THÔNG TIN
                    </h2>
                    <div class="space-y-4 text-slate-400 text-sm leading-relaxed">
                        <p class="font-bold text-white">1. Mục đích thu thập thông tin khách hàng:</p>
                        <p>VNTech thu thập các thông tin cá nhân bao gồm: Họ tên, Email, Số điện thoại và Địa chỉ giao hàng. Các thông tin này chỉ sử dụng cho mục đích xác nhận đơn hàng, liên hệ giao hàng và bảo hành điện tử.</p>

                        <p class="font-bold text-white mt-4">2. Cam kết bảo mật dữ liệu khách hàng:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Chúng tôi sử dụng giao thức bảo mật SSL mã hóa toàn bộ dữ liệu giao dịch tài chính trực tuyến.</li>
                            <li>Tuyệt đối không chia sẻ, mua bán dữ liệu cá nhân của khách hàng cho bất kỳ bên thứ ba nào khi chưa có sự đồng ý bằng văn bản của khách hàng.</li>
                        </ul>

                        <p class="font-bold text-white mt-4">3. Quyền hạn của khách hàng:</p>
                        <p>Khách hàng có quyền truy cập vào trang cá nhân để sửa đổi thông tin hoặc gửi yêu cầu xóa bỏ tài khoản hoàn toàn khỏi hệ thống của VNTech bất cứ lúc nào.</p>
                    </div>
                </div>

                <!-- Tab: Shipping Policy -->
                <div x-show="currentTab === 'shipping'" class="space-y-6" style="display: none;">
                    <h2 class="text-xl font-bold uppercase tracking-wider text-lime-400 flex items-center gap-2">
                        <i data-lucide="truck" class="w-5 h-5"></i> CHÍNH SÁCH VẬN CHUYỂN GIAO NHẬN
                    </h2>
                    <div class="space-y-4 text-slate-400 text-sm leading-relaxed">
                        <p class="font-bold text-white">1. Khu vực giao hàng:</p>
                        <p>VNTech hỗ trợ giao hàng toàn quốc trên khắp 63 tỉnh thành, kết hợp cùng các đơn vị vận chuyển hàng đầu như Giao Hàng Nhanh, Viettel Post, v.v.</p>

                        <p class="font-bold text-white mt-4">2. Chi phí giao hàng:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Nội thành Hồ Chí Minh & Hà Nội: Đồng giá 25,000 VNĐ. Miễn phí cho đơn hàng từ 1,000,000 VNĐ.</li>
                            <li>Tỉnh thành khác: Tính theo bảng giá thực tế của đơn vị vận chuyển.</li>
                        </ul>

                        <p class="font-bold text-white mt-4">3. Đồng kiểm khi nhận hàng:</p>
                        <p>Khách hàng hoàn toàn có quyền mở hộp kiểm tra ngoại quan sản phẩm trước khi thanh toán cho nhân viên giao hàng (Không hỗ trợ cắm điện dùng thử sản phẩm ngay tại chỗ). Nếu phát hiện móp méo hộp hoặc hư hại ngoại quan, hãy từ chối nhận hàng và báo lại ngay cho VNTech.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
