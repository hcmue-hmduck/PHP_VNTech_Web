<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yêu cầu liên hệ mới - VN Tech</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f6f9fc; padding: 40px 0;">
        <!-- <tr> -->
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #eef2f5;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #1c1b1b, #2c2a29); padding: 40px 20px; border-bottom: 4px solid #ff5c00;">
                            <h1 style="color: #ff5c00; margin: 0; font-size: 28px; font-weight: 950; letter-spacing: 0.1em; text-transform: uppercase;">
                                VN Tech
                            </h1>
                            <p style="color: #e4beb1; margin: 5px 0 0 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;">
                                Hệ Thống Nhận Tin Nhắn Liên Hệ
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin-top: 0; font-size: 16px; line-height: 1.6; color: #1c1b1b;">
                                Xin chào <strong>Ban quản trị VN Tech</strong>,
                            </p>
                            <p style="font-size: 15px; line-height: 1.6; color: #5b4137;">
                                Hệ thống vừa tiếp nhận một yêu cầu liên hệ mới từ khách hàng thông qua Form liên hệ trên Website. Chi tiết như sau:
                            </p>

                            <!-- Information Table -->
                            <table border="0" cellpadding="12" cellspacing="0" width="100%" style="background-color: #fcf9f8; border-radius: 12px; margin: 25px 0; border: 1px solid #e4beb1/40; font-size: 14px;">
                                <tr>
                                    <td width="30%" style="color: #8c6e61; font-weight: 700; border-bottom: 1px solid #e4beb1/20; padding: 12px;">Họ và tên:</td>
                                    <td width="70%" style="color: #1c1b1b; font-weight: 600; border-bottom: 1px solid #e4beb1/20; padding: 12px;">
                                        {{ $data['name'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #8c6e61; font-weight: 700; border-bottom: 1px solid #e4beb1/20; padding: 12px;">Địa chỉ Email:</td>
                                    <td style="color: #a73a00; font-weight: 600; border-bottom: 1px solid #e4beb1/20; padding: 12px;">
                                        <a href="mailto:{{ $data['email'] }}" style="color: #a73a00; text-decoration: none;">{{ $data['email'] }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #8c6e61; font-weight: 700; border-bottom: 1px solid #e4beb1/20; padding: 12px;">Chủ đề liên hệ:</td>
                                    <td style="color: #1c1b1b; font-weight: 600; border-bottom: 1px solid #e4beb1/20; padding: 12px;">
                                        {{ $data['subject'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #8c6e61; font-weight: 700; border-bottom: 1px solid #e4beb1/20; padding: 12px;">Thời gian gửi:</td>
                                    <td style="color: #1c1b1b; border-bottom: 1px solid #e4beb1/20; padding: 12px;">
                                        {{ now()->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" style="color: #8c6e61; font-weight: 700; padding: 12px;">Nội dung tin nhắn:</td>
                                    <td style="color: #1c1b1b; line-height: 1.6; padding: 12px; white-space: pre-line;">
                                        {{ $data['message'] }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Reply CTA Note -->
                            <table border="0" cellpadding="12" cellspacing="0" width="100%" style="background-color: #ffdbce/20; border-left: 4px solid #ff5c00; border-radius: 4px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 10px 15px;">
                                        <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #a73a00; font-weight: 500;">
                                            <strong>💡 Mẹo phản hồi nhanh:</strong> Bạn có thể nhấn trực tiếp nút <strong>"Reply" (Trả lời)</strong> trên hòm thư này để soạn email phản hồi ngay lập tức cho khách hàng.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #fcf9f8; padding: 25px 20px; border-top: 1px solid #e4beb1/30; font-size: 12px; color: #8c6e61; line-height: 1.5;">
                            <p style="margin: 0 0 5px 0; font-weight: 700; color: #a73a00;">Cửa hàng công nghệ VN Tech</p>
                            <p style="margin: 0;">280 An Dương Vương, Phường 4, Quận 5, TP. Hồ Chí Minh</p>
                            <p style="margin: 5px 0 0 0;">© 2026 VN Tech. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
