<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->ma_don_hang }} - VNTech</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* General page setup optimized for A4 paper print */
        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                margin-left: 1.2cm !important;
                margin-right: 1.2cm !important;
                width: auto !important;
                max-width: none !important;
            }
            .no-print {
                display: none !important;
            }
            body.print-padding {
                padding-top: 1.2cm !important;
                padding-bottom: 1.2cm !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            @page {
                size: A4;
                margin: 0;
            }
        }
        body {
            font-family: 'Times New Roman', Times, serif !important;
            background-color: #ffffff;
            color: #000000 !important;
        }
        /* Unify font family, size and color across all print elements */
        body, table, th, td, p, span, div, strong, td * {
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 14px !important;
            color: #000000 !important;
        }
        h1, h1 * {
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 18px !important;
            font-weight: bold !important;
            color: #000000 !important;
        }
        h2, h2 * {
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 22px !important;
            font-weight: bold !important;
            color: #000000 !important;
        }
        h3, h3 *, .section-title {
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 15px !important;
            font-weight: bold !important;
            color: #000000 !important;
        }
        /* Custom print receipt borders */
        .invoice-table th, .invoice-table td {
            border: 1px solid #000000 !important;
            padding: 8px 10px;
        }
        .section-title {
            border-bottom: 1.5px solid #000000 !important;
            padding-bottom: 4px;
            margin-bottom: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .small-note, .small-note * {
            font-size: 11px !important;
            color: #475569 !important; /* Slightly muted for warning note */
        }
    </style>
</head>
<body class="p-6 print-padding max-w-[800px] mx-auto bg-white text-[13px] leading-relaxed text-slate-900">

    <!-- Print Action Header (Only visible on screen) -->
    <div class="no-print mb-6 p-4 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-between">
        <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Xem trước hóa đơn</span>
        <div class="flex gap-2">
            <button onclick="window.close()" class="px-4 py-2 border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 font-bold text-xs uppercase tracking-wider rounded-lg transition-all">
                Đóng
            </button>
            <button onclick="window.print()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider rounded-lg transition-all shadow-sm">
                In hóa đơn
            </button>
        </div>
    </div>

    <!-- Main Store Invoice (A4 Layout) -->
    <div class="w-full">
        
        <!-- Upper Header Section: Company Info (Centered) -->
        <div class="text-center border-b-2 border-double border-slate-800 pb-4 mb-6 space-y-1">
            <h1 class="text-xl font-bold uppercase tracking-tight text-slate-900">CÔNG TY TNHH CÔNG NGHỆ VNTECH</h1>
            <p class="text-xs text-slate-700">
                <strong>Địa chỉ:</strong> Số 280 An Dương Vương, Phường 4, Quận 5, TP. Hồ Chí Minh<br>
                <strong>Hotline:</strong> 090 123 4567 &nbsp;|&nbsp; <strong>Email:</strong> support@vntech.vn &nbsp;|&nbsp; <strong>Website:</strong> https://php-vntech-web.onrender.com/
            </p>
        </div>

        <!-- Center Header: Invoice Title & Metadata -->
        <div class="text-center my-6 space-y-2">
            <h2 class="text-2xl font-bold uppercase text-slate-950 tracking-wider">HÓA ĐƠN BÁN HÀNG</h2>
            <div class="flex justify-center flex-wrap gap-x-6 gap-y-1 text-xs text-slate-700">
                <div><strong>Mã hóa đơn:</strong> <span class="text-red-600 font-bold">#{{ substr($order->ma_don_hang, -8) }}</span></div>
                <div><strong>Ngày lập:</strong> {{ $order->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y - H:i') }}</div>
                @if(Auth::check() && Auth::user()->vai_tro === 'admin')
                    <div><strong>Người in:</strong> {{ Auth::user()->ho_ten }}</div>
                @else
                    <div><strong>Kênh bán:</strong> Website Online</div>
                @endif
                <div><strong>Thời gian in:</strong> {{ now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <!-- 1. THÔNG TIN KHÁCH HÀNG -->
        <div class="mb-6">
            <h3 class="section-title">1. Thông tin khách hàng</h3>
            <div class="border border-slate-800 p-4 rounded-md bg-slate-50/50 space-y-2.5 text-[13px]">
                <div>
                    <strong>Khách hàng:</strong> <span class="text-slate-950 uppercase font-semibold">{{ $order->ho_ten_nguoi_nhan }}</span>
                </div>
                <div>
                    <strong>Số điện thoại:</strong> <span class="font-mono text-slate-700">{{ $order->so_dien_thoai_nhan }}</span>
                </div>
                <div>
                    <strong>Địa chỉ giao nhận hàng:</strong> <span class="text-slate-800">{{ $order->dia_chi_giao_hang }}</span>
                </div>
                <div>
                    <strong>Phương thức thanh toán:</strong> 
                    <span class="font-semibold text-slate-800">
                        @if($order->phuong_thuc_thanh_toan === 'momo')
                            Chuyển khoản online (MoMo)
                        @else
                            Thanh toán khi nhận hàng (COD)
                        @endif
                    </span>
                </div>
                @if($order->ghi_chu)
                    <div>
                        <strong>Ghi chú đơn hàng:</strong> <span class="text-slate-600 italic">"{{ $order->ghi_chu }}"</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. THÔNG TIN HÀNG HÓA -->
        <div class="mb-6">
            <h3 class="section-title">2. Thông tin hàng hóa</h3>
            <table class="w-full border-collapse invoice-table mb-6">
            <thead>
                <tr class="bg-slate-100/80 text-left font-bold text-slate-900">
                    <th class="w-12 text-center">STT</th>
                    <th>Tên hàng hóa, dịch vụ</th>
                    <th class="w-20 text-center">Số lượng</th>
                    <th class="w-28 text-right">Đơn giá</th>
                    <th class="w-32 text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $index => $item)
                    @php
                        $displayName = $item->variant?->ten_hien_thi ?? $item->ten_bien_the;
                    @endphp
                    <tr>
                        <td class="text-center font-mono">{{ $index + 1 }}</td>
                        <td>
                            <div class="font-bold text-slate-900 uppercase text-xs">
                                {{ $displayName }}
                            </div>
                        </td>
                        <td class="text-center font-bold font-mono">{{ $item->so_luong }}</td>
                        <td class="text-right font-mono">{{ number_format($item->gia_ban, 0, ',', '.') }}đ</td>
                        <td class="text-right font-bold font-mono text-slate-950">
                            {{ number_format($item->gia_ban * $item->so_luong, 0, ',', '.') }}đ
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

        <!-- Summary section -->
        <div class="flex justify-end mb-6">
            <div class="w-80 space-y-2 text-xs">
                <div class="flex justify-between text-slate-700">
                    <span>Tổng hoá đơn:</span>
                    <span class="font-mono font-medium">{{ number_format($order->tong_tien_hang, 0, ',', '.') }}đ</span>
                </div>
                
                @if(($order->gia_tri_giam_voucher ?? 0) > 0)
                    <div class="flex justify-between text-emerald-700 font-medium">
                        <span>Khuyến mãi:</span>
                        <span class="font-mono">-{{ number_format($order->gia_tri_giam_voucher, 0, ',', '.') }}đ</span>
                    </div>
                @endif
                
                <div class="flex justify-between text-slate-700">
                    <span>Phí vận chuyển:</span>
                    <span class="font-mono">{{ number_format($order->phi_van_chuyen ?? 0, 0, ',', '.') }}đ</span>
                </div>

                <div class="flex justify-between text-slate-950 font-bold border-t border-slate-350 pt-2 text-sm">
                    <span>Tổng thanh toán:</span>
                    <span class="font-mono text-red-600">{{ number_format($order->tong_thanh_toan, 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>

        <!-- Total amount in words -->
        @php
        function numberToWords($number) {
            $dictionary  = array(
                0                   => 'không',
                1                   => 'một',
                2                   => 'hai',
                3                   => 'ba',
                4                   => 'bốn',
                5                   => 'năm',
                6                   => 'sáu',
                7                   => 'bảy',
                8                   => 'tám',
                9                   => 'chín',
                10                  => 'mười',
                11                  => 'mười một',
                12                  => 'mười hai',
                13                  => 'mười ba',
                14                  => 'mười bốn',
                15                  => 'mười lăm',
                16                  => 'mười sáu',
                17                  => 'mười bảy',
                18                  => 'mười tám',
                19                  => 'mười chín',
                20                  => 'hai mươi',
                30                  => 'ba mươi',
                40                  => 'bốn mươi',
                50                  => 'năm mươi',
                60                  => 'sáu mươi',
                70                  => 'bảy mươi',
                80                  => 'tám mươi',
                90                  => 'chín mươi',
                100                 => 'trăm',
                1000                => 'nghìn',
                1000000             => 'triệu',
                1000000000          => 'tỷ'
            );

            if (!is_numeric($number)) {
                return false;
            }

            if ($number < 0) {
                return 'âm ' . numberToWords(abs($number));
            }

            $string = null;

            switch (true) {
                case $number < 21:
                    $string = $dictionary[$number];
                    break;
                case $number < 100:
                    $tens   = ((int) ($number / 10)) * 10;
                    $units  = $number % 10;
                    $string = $dictionary[$tens];
                    if ($units) {
                        $string .= ' ' . ($units == 1 ? 'mốt' : ($units == 5 ? 'lăm' : $dictionary[$units]));
                    }
                    break;
                case $number < 1000:
                    $hundreds  = $number / 100;
                    $remainder = $number % 100;
                    $string = $dictionary[(int)$hundreds] . ' trăm';
                    if ($remainder) {
                        $string .= ' ' . ($remainder < 10 ? 'lẻ ' : '') . numberToWords($remainder);
                    }
                    break;
                default:
                    $baseUnit = pow(1000, floor(log($number, 1000)));
                    $numBaseUnits = (int) ($number / $baseUnit);
                    $remainder = $number % $baseUnit;
                    $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if ($remainder) {
                        $string .= $remainder < 100 ? ' lẻ ' . numberToWords($remainder) : ' ' . numberToWords($remainder);
                    }
                    break;
            }

            return $string;
        }
        
        $totalWords = numberToWords($order->tong_thanh_toan) . ' đồng chẵn';
        $totalWords = ucfirst(trim($totalWords));
        @endphp
        
        <div class="border-t border-b border-slate-800 py-3 mb-8">
            <strong>Số tiền viết bằng chữ:</strong> <span class="italic text-slate-800">{{ $totalWords }}.</span>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-4 gap-4 text-center text-xs mt-10 mb-20">
            <div class="space-y-12">
                <div>
                    <p class="font-bold uppercase">Người mua hàng</p>
                    <p class="small-note italic">(Ký, ghi rõ họ tên)</p>
                </div>
                <p class="text-slate-300">........................</p>
            </div>
            <div class="space-y-12">
                <div>
                    <p class="font-bold uppercase">Người giao hàng</p>
                    <p class="small-note italic">(Ký, ghi rõ họ tên)</p>
                </div>
                <p class="text-slate-300">........................</p>
            </div>
            <div class="space-y-12">
                <div>
                    <p class="font-bold uppercase">Thủ kho</p>
                    <p class="small-note italic">(Ký, ghi rõ họ tên)</p>
                </div>
                <p class="text-slate-300">........................</p>
            </div>
            <div class="space-y-12">
                <div>
                    <p class="font-bold uppercase">Người in hoá đơn</p>
                    <p class="small-note italic">(Ký, đóng dấu)</p>
                </div>
                <p class="text-slate-900 font-semibold uppercase">
                    @if(Auth::check() && Auth::user()->vai_tro === 'admin')
                        {{ Auth::user()->ho_ten }}
                    @else
                        Nhân viên VNTech
                    @endif
                </p>
            </div>
        </div>

        <!-- Bottom Warning/Info -->
        <div class="text-center small-note leading-relaxed border-t border-slate-205 pt-4">
            * Cảm ơn quý khách đã tin dùng sản phẩm của chúng tôi!<br>
            * Quý khách vui lòng kiểm tra kỹ hàng hóa trước khi nhận hàng và ký tên biên nhận.
        </div>

    </div>

</body>
</html>
