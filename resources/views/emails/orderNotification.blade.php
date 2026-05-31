<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thông báo đơn hàng VN Tech</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333;">
    @php
        // Bản đồ hóa các nhãn trạng thái từ enum OrderStatus
        $trangThaiText = match($order->trang_thai) {
            'cho_thanh_toan' => 'Chờ thanh toán (MoMo)',
            'cho_xac_nhan'   => 'Chờ xác nhận',
            'da_xac_nhan'    => 'Đã xác nhận & Chờ lấy hàng',
            'dang_giao_hang' => 'Đang giao hàng',
            'da_nhan_hang'   => 'Hoàn thành (Đã nhận hàng)',
            'da_huy'         => 'Đã hủy',
            default          => 'Chờ xác nhận',
        };

        // Màu sắc tương ứng với trạng thái
        $badgeColor = match($order->trang_thai) {
            'da_nhan_hang'   => '#10b981', // Xanh lục
            'dang_giao_hang' => '#0284c7', // Xanh dương
            'da_xac_nhan'    => '#6366f1', // Indigo
            'cho_thanh_toan' => '#f59e0b', // Cam
            'da_huy'         => '#ef4444', // Đỏ
            default          => '#6b7280', // Xám
        };
    @endphp

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f6f9fc; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Wrapper Table -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); border: 1px solid #eef2f5;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 40px 20px; border-bottom: 3px solid #00e55b;">
                            <h1 style="color: #00e55b; margin: 0; font-size: 28px; font-weight: 800; tracking-wide: 0.1em; text-transform: uppercase;">
                                VN Tech
                            </h1>
                            <p style="color: #94a3b8; margin: 5px 0 0 0; font-size: 14px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">
                                Cập nhật thông tin đơn hàng
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin-top: 0; font-size: 16px; line-height: 1.6; color: #475569;">
                                Xin chào <strong>{{ $order->ho_ten_nguoi_nhan ?? 'Quý khách' }}</strong>,
                            </p>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Trạng thái đơn hàng của bạn tại **VN Tech** đã được cập nhật. Dưới đây là thông tin chi tiết:
                            </p>

                            <!-- Order Detail Box -->
                            <table border="0" cellpadding="12" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; margin: 25px 0; border: 1px solid #e2e8f0; font-size: 14px;">
                                <tr>
                                    <td width="35%" style="color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Mã đơn hàng:</td>
                                    <td width="65%" style="color: #0f172a; font-family: monospace; font-size: 15px; font-weight: 700; border-bottom: 1px solid #e2e8f0;">
                                        #{{ $order->ma_don_hang ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Trạng thái hiện tại:</td>
                                    <td style="border-bottom: 1px solid #e2e8f0;">
                                        <span style="display: inline-block; background-color: {{ $badgeColor }}; color: #ffffff; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                                            {{ $trangThaiText }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Số điện thoại nhận:</td>
                                    <td style="color: #334155; border-bottom: 1px solid #e2e8f0;">
                                        {{ $order->so_dien_thoai_nhan ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Địa chỉ giao hàng:</td>
                                    <td style="color: #334155; border-bottom: 1px solid #e2e8f0; line-height: 1.4;">
                                        {{ $order->dia_chi_giao_hang ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">Tổng thanh toán:</td>
                                    <td style="color: #0f172a; font-size: 16px; font-weight: 700;">
                                        {{ number_format((float) ($order->tong_thanh_toan ?? 0), 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; line-height: 1.6; color: #64748b;">
                                Nếu bạn có bất kỳ câu hỏi hoặc phản hồi nào, vui lòng liên hệ ngay với chúng tôi bằng cách phản hồi lại email này.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 25px 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                            <p style="margin: 0 0 5px 0; font-weight: 600; color: #64748b;">Cửa hàng công nghệ VN Tech</p>
                            <p style="margin: 0;">Email gửi tự động, vui lòng không trả lời trực tiếp địa chỉ này.</p>
                            <p style="margin: 5px 0 0 0;">© 2026 VN Tech. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
