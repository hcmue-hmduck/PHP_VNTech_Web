@extends('layouts.admin')

@section('title', 'Thống kê - VNTech')

@section('content')
@php
    $completionRate = $completionRate ?? 0;
    $inStockRate = $inStockRate ?? 0;
    $growth = $growth ?? 0;
    $deliveredOrdersCount = $deliveredOrdersCount ?? 0;
    $revenues = $revenues ?? [];
    $labels = $labels ?? [];
    $latestOrders = $latestOrders ?? collect();
@endphp

<!-- STATS CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Doanh thu -->
    <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
        <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
        <div class="flex justify-between items-start z-10">
            <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Doanh thu</p>
            <i data-lucide="trending-up" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <div class="z-10">
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ format_vnd($total_sales) }}</h3>
        </div>
        <div class="mt-auto z-10">
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-display font-bold {{ $growth >= 0 ? 'text-neon-green neon-text-glow' : 'text-red-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.3)]' }} tracking-tight">
                    {{ $growth >= 0 ? '+' : '' }}{{ $growth }}%
                </span>
                <span class="text-[9px] text-gray-500 uppercase tracking-tighter whitespace-nowrap overflow-hidden text-ellipsis">so với tháng trước</span>
            </div>
        </div>
    </div>

    <!-- Sản phẩm điện tử -->
    <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
        <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
        <div class="flex justify-between items-start z-10">
            <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng sản phẩm</p>
            <i data-lucide="cpu" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <div class="z-10">
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $products }}</h3>
            <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">{{ $product_variants }} Phiên bản sản phẩm</p>
        </div>
        <div class="mt-auto z-10">
            <div class="pt-2">
                <div class="flex justify-between items-center text-[9px] text-gray-500 uppercase mb-1.5 font-bold">
                    <span class="tracking-widest">TỶ LỆ CÒN HÀNG</span>
                    <span>{{ $inStockRate }}%</span>
                </div>
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-neon-green neon-glow" data-width="{{ $inStockRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tổng đơn -->
    <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
        <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
        <div class="flex justify-between items-start z-10">
            <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng đơn</p>
            <i data-lucide="shopping-bag" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <div class="z-10">
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $totalOrdersCount }}</h3>
            <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">{{ $deliveredOrdersCount }} Đơn hoàn thành</p>
        </div>
        <div class="mt-auto z-10">
            <div class="pt-2">
                <div class="flex justify-between items-center text-[9px] text-gray-500 uppercase mb-1.5 font-bold">
                    <span class="tracking-widest">TỶ LỆ HOÀN THÀNH</span>
                    <span>{{ $completionRate }}%</span>
                </div>
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-neon-green neon-glow" data-width="{{ $completionRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chờ duyệt -->
    <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1 border-l-4 border-l-neon-green">
        <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
        <div class="flex justify-between items-start z-10">
            <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Chờ duyệt</p>
            <i data-lucide="clock" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <div class="z-10">
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">{{ $pendingOrdersCount }}</h3>
            <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Đơn chờ xác nhận</p>
        </div>
        <div class="mt-auto z-10">
            <div class="pt-2 flex items-center gap-2 text-[9px] text-red-500 font-mono tracking-widest font-bold animate-pulse">
                <i data-lucide="alert-circle" class="size-4"></i> CẦN XỬ LÝ NGAY
            </div>
        </div>
    </div>
</div>

<!-- REVENUE CHART -->
<div class="glass-panel p-8 rounded-2xl flex flex-col gap-6 transition-all duration-700">
    <div class="flex justify-between items-center">
        <div>
            <h4 class="text-lg font-bold text-white uppercase tracking-wider font-display">Thống Kê Doanh Thu (7 Ngày Gần nhất)</h4>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Biểu đồ xu hướng doanh thu hàng ngày</p>
        </div>
    </div>
    
    <div class="w-full h-[350px] mt-4" id="revenueChart" data-revenues="{{ json_encode($revenues) }}" data-labels="{{ json_encode($labels) }}"></div>
</div>

<!-- LIVE DATA TABLE -->
<div class="glass-panel rounded-2xl overflow-hidden border border-white/5">
    <div class="p-6 border-b border-white/5 bg-surface-high/30 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="size-2 rounded-full bg-red-500 animate-pulse"></div>
            <h4 class="font-bold text-white uppercase tracking-widest text-sm font-display">Đơn Hàng Gần Đây</h4>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="text-[10px] text-gray-500 uppercase tracking-[0.2em] border-b border-white/5">
                    <th class="px-6 py-4 font-bold border-b border-white/5 text-center">Mã Đơn</th>
                    <th class="px-6 py-4 font-bold border-b border-white/5">Khách Hàng</th>
                    <th class="px-6 py-4 font-bold border-b border-white/5">Giá Trị</th>
                    <th class="px-6 py-4 font-bold border-b border-white/5">Trạng Thái</th>
                    <th class="px-6 py-4 font-bold text-right border-b border-white/5">Thời Gian</th>
                </tr>
            </thead>
            <tbody class="text-xs font-mono">
                @forelse($latestOrders as $order)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4 text-neon-green text-center">#{{ $order->ma_don_hang ?? $order->id }}</td>
                    <td class="px-6 py-4 text-white font-medium">{{ $order->ho_ten_nguoi_nhan }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ format_vnd($order->tong_thanh_toan) }}</td>
                    <td class="px-6 py-4">
                        @if($order->trang_thai === 'da_nhan_hang')
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-neon-green/10 text-neon-green border-neon-green/20">
                                HOÀN TẤT
                            </span>
                        @elseif($order->trang_thai === 'cho_xac_nhan')
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-red-500/10 text-red-500 border-red-500/20 animate-pulse">
                                CHỜ XÁC NHẬN
                            </span>
                        @elseif($order->trang_thai === 'cho_thanh_toan')
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-yellow-500/10 text-yellow-400 border-yellow-500/20">
                                CHỜ THANH TOÁN
                            </span>
                        @elseif($order->trang_thai === 'da_huy')
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-gray-500/10 text-gray-400 border-gray-500/20">
                                ĐÃ HỦY
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-blue-500/10 text-blue-400 border-blue-500/20">
                                ĐANG XỬ LÝ
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-right group-hover:text-gray-300 transition-colors">
                        {{ $order->created_at ? $order->created_at->locale('vi')->diffForHumans() : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Chưa có đơn hàng nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set dynamic widths from data-width attributes to avoid CSS parser issues in IDEs
        document.querySelectorAll('[data-width]').forEach(el => {
            el.style.width = el.getAttribute('data-width');
        });

        // Retrieve revenues and labels from HTML data attributes to prevent IDE JS/TS syntax errors
        const chartEl = document.querySelector("#revenueChart");
        const chartRevenues = JSON.parse(chartEl.getAttribute('data-revenues') || '[]');
        const chartLabels = JSON.parse(chartEl.getAttribute('data-labels') || '[]');

        var options = {
            series: [{
                name: 'Doanh thu',
                data: chartRevenues
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                zoom: { enabled: false },
                background: 'transparent'
            },
            colors: ['#00e55b'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#00e55b']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0,
                    stops: [0, 95]
                }
            },
            grid: {
                show: true,
                borderColor: 'rgba(255, 255, 255, 0.05)',
                strokeDashArray: 4,
                yaxis: { lines: { show: false } }
            },
            xaxis: {
                categories: chartLabels,
                labels: {
                    style: {
                        colors: '#4b5563',
                        fontSize: '10px',
                        fontFamily: 'JetBrains Mono, monospace'
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { show: false },
            tooltip: {
                theme: 'dark',
                x: { show: false },
                y: {
                    formatter: function(val) {
                        return val.toLocaleString() + 'đ';
                    }
                },
                marker: { show: false },
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    return '<div class="px-3 py-2 bg-surface border border-neon-green rounded-lg shadow-xl">' +
                        '<span class="text-neon-green font-bold">' + series[seriesIndex][dataPointIndex].toLocaleString() + 'đ</span>' +
                        '</div>';
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
        chart.render();
    });
</script>
@endpush
