<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Phản hồi đánh giá VN Tech</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333;">
    @php
        $customerName = $review->user?->ho_ten ?? 'Quý khách';
        $productName = trim((string) ($review->product?->ten_san_pham ?? '') . ' ' . (string) ($review->ten_bien_the ?? ''));
        $productName = $productName !== '' ? $productName : 'sản phẩm bạn đã mua';
    @endphp

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f6f9fc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); border: 1px solid #eef2f5;">
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 40px 20px; border-bottom: 3px solid #00e55b;">
                            <h1 style="color: #00e55b; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;">
                                VN Tech
                            </h1>
                            <p style="color: #94a3b8; margin: 5px 0 0 0; font-size: 14px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">
                                {{ $isUpdated ? 'Cập nhật phản hồi đánh giá' : 'Phản hồi đánh giá của bạn' }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin-top: 0; font-size: 16px; line-height: 1.6; color: #475569;">
                                Xin chào <strong>{{ $customerName }}</strong>,
                            </p>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                VN Tech {{ $isUpdated ? 'đã cập nhật phản hồi cho' : 'đã phản hồi' }} đánh giá của bạn về <strong>{{ $productName }}</strong>.
                            </p>

                            <table border="0" cellpadding="12" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; margin: 25px 0; border: 1px solid #e2e8f0; font-size: 14px;">
                                <tr>
                                    <td width="35%" style="color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Số sao của bạn:</td>
                                    <td width="65%" style="color: #f59e0b; font-size: 15px; font-weight: 700; border-bottom: 1px solid #e2e8f0;">
                                        {{ str_repeat('★', (int) $review->so_sao) }}{{ str_repeat('☆', 5 - (int) $review->so_sao) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Nội dung đánh giá:</td>
                                    <td style="color: #334155; border-bottom: 1px solid #e2e8f0; line-height: 1.5;">
                                        {{ $review->noi_dung ?: 'Không có nội dung' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">Phản hồi VN Tech:</td>
                                    <td style="color: #0f172a; font-weight: 600; line-height: 1.5;">
                                        {{ $reply->noi_dung }}
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; line-height: 1.6; color: #64748b;">
                                Cảm ơn bạn đã dành thời gian chia sẻ trải nghiệm. Phản hồi của bạn giúp VN Tech cải thiện chất lượng sản phẩm và dịch vụ.
                            </p>
                        </td>
                    </tr>

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
