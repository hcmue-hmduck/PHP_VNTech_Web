<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mã xác thực OTP - VN Tech</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f6f9fc; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Wrapper Table -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); border: 1px solid #eef2f5;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 40px 20px; border-bottom: 3px solid #00e55b;">
                            <h1 style="color: #00e55b; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;">
                                VN Tech
                            </h1>
                            <p style="color: #94a3b8; margin: 5px 0 0 0; font-size: 14px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">
                                Mã xác thực OTP
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin-top: 0; font-size: 16px; line-height: 1.6; color: #475569;">
                                Xin chào,
                            </p>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Bạn đã yêu cầu xác thực tài khoản tại <strong>VN Tech</strong>. Vui lòng sử dụng mã OTP dưới đây để hoàn tất quá trình đăng ký:
                            </p>

                            <!-- OTP Code Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 35px 0;">
                                <tr>
                                    <td align="center">
                                        <div style="background: linear-gradient(135deg, #00e55b, #10b981); border-radius: 12px; padding: 30px; box-shadow: 0 8px 24px rgba(0, 229, 91, 0.15);">
                                            <p style="margin: 0 0 15px 0; color: #ffffff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">
                                                Mã xác thực của bạn
                                            </p>
                                            <p style="margin: 0; color: #ffffff; font-size: 48px; font-weight: 900; letter-spacing: 0.15em; font-family: 'Courier New', monospace;">
                                                {{ $otp }}
                                            </p>
                                            <p style="margin: 15px 0 0 0; color: rgba(255, 255, 255, 0.8); font-size: 12px;">
                                                Mã này sẽ hết hạn trong 5 phút
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Email Display -->
                            <table border="0" cellpadding="12" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; margin: 25px 0; border: 1px solid #e2e8f0; font-size: 14px;">
                                <tr>
                                    <td width="35%" style="color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Email xác thực:</td>
                                    <td width="65%" style="color: #0f172a; font-family: monospace; font-size: 14px; border-bottom: 1px solid #e2e8f0;">
                                        {{ $email }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">Thời hạn:</td>
                                    <td style="color: #ef4444; font-weight: 600;">
                                        5 phút từ lúc nhận email
                                    </td>
                                </tr>
                            </table>

                            <!-- Instructions -->
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 4px; margin: 25px 0;">
                                <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.5;">
                                    <strong>⚠️ Lưu ý quan trọng:</strong> Không chia sẻ mã OTP này với bất kỳ ai. VN Tech sẽ không bao giờ yêu cầu bạn cung cấp mã OTP qua email hoặc tin nhắn.
                                </p>
                            </div>

                            <p style="font-size: 14px; line-height: 1.6; color: #64748b;">
                                Nếu bạn không yêu cầu xác thực này, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi ngay lập tức.
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
