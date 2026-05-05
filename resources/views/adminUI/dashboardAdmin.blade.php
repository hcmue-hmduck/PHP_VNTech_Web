@extends('layouts.admin')

@section('title', 'Trung tâm Chỉ huy - VNTech')

@section('content')
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
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">18,990,000đ</h3>
        </div>
        <div class="mt-auto z-10">
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-display font-bold text-neon-green neon-text-glow tracking-tight">+12.5%</span>
                <span class="text-[9px] text-gray-500 uppercase tracking-tighter whitespace-nowrap overflow-hidden text-ellipsis">so với tháng trước</span>
            </div>
        </div>
    </div>

    <!-- Vũ khí (SP) -->
    <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
        <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
        <div class="flex justify-between items-start z-10">
            <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Vũ khí (SP)</p>
            <i data-lucide="swords" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <div class="z-10">
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">62</h3>
            <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Inventory units active</p>
        </div>
        <div class="mt-auto z-10">
            <div class="pt-2">
                <div class="flex justify-between items-center text-[9px] text-gray-500 uppercase mb-1.5 font-bold">
                    <span class="tracking-widest">Performance Rate</span>
                    <span>62%</span>
                </div>
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-neon-green neon-glow" style="width: 62%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tổng đơn -->
    <div class="glass-panel p-6 rounded-xl flex flex-col gap-4 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1">
        <div class="absolute top-0 right-0 w-24 h-24 bg-neon-green/5 -rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
        <div class="flex justify-between items-start z-10">
            <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase">Tổng đơn</p>
            <i data-lucide="list-ordered" class="size-5 text-neon-green opacity-40 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <div class="z-10">
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">2</h3>
            <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Active operations</p>
        </div>
        <div class="mt-auto z-10">
            <div class="pt-2">
                <div class="flex justify-between items-center text-[9px] text-gray-500 uppercase mb-1.5 font-bold">
                    <span class="tracking-widest">Performance Rate</span>
                    <span>15%</span>
                </div>
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-neon-green neon-glow" style="width: 15%"></div>
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
            <h3 class="text-2xl font-display font-bold text-white tracking-tight leading-tight">14</h3>
            <p class="text-[10px] text-gray-500 mt-1.5 uppercase font-medium tracking-wide">Cần xử lý ngay</p>
            <p class="text-neon-green text-[10px] font-bold mt-1 uppercase tracking-widest animate-pulse">Cần xử lý ngay</p>
        </div>
        <div class="mt-auto z-10">
            <div class="pt-2 flex items-center gap-2 text-[9px] text-neon-green/80 font-mono tracking-widest font-bold">
                <span class="animate-pulse">●</span> ACTION REQUIRED
            </div>
        </div>
    </div>
</div>

<!-- REVENUE CHART -->
<div class="glass-panel p-8 rounded-2xl flex flex-col gap-6 transition-all duration-700">
    <div class="flex justify-between items-center">
        <div>
            <h4 class="text-lg font-bold text-white uppercase tracking-wider font-display">Tín Hiệu Doanh Thu (7 Ngày)</h4>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Dòng tiền hệ thống thời gian thực</p>
        </div>
        <div class="flex gap-2 p-1 bg-surface-high rounded-lg border border-white/5">
            <button class="px-3 py-1 text-[10px] font-bold text-gray-500 hover:text-white uppercase transition-colors">WEEKLY</button>
            <button class="px-3 py-1 bg-neon-green text-black rounded text-[10px] font-bold uppercase">MONTHLY</button>
        </div>
    </div>
    
    <div class="w-full h-[350px] mt-4" id="revenueChart"></div>
</div>

<!-- LIVE DATA TABLE -->
<div class="glass-panel rounded-2xl overflow-hidden border border-white/5">
    <div class="p-6 border-b border-white/5 bg-surface-high/30 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="size-2 rounded-full bg-red-500 animate-pulse"></div>
            <h4 class="font-bold text-white uppercase tracking-widest text-sm font-display">Luồng Dữ Liệu Đơn Hàng (LIVE)</h4>
        </div>
        <span class="text-[10px] font-mono text-gray-500 uppercase tracking-tighter">NODE: HK-SERVER-09</span>
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
                @php
                    $orders = [
                        ['id' => '#VNT-9982', 'customer' => 'Nguyễn Hoàng Anh', 'amount' => '4,250,000đ', 'status' => 'HOÀN TẤT', 'time' => 'Vừa xong'],
                        ['id' => '#VNT-9981', 'customer' => 'Trần Minh Tâm', 'amount' => '12,800,000đ', 'status' => 'ĐANG XỬ LÝ', 'time' => '2 phút trước'],
                        ['id' => '#VNT-9980', 'customer' => 'Lê Quang Vinh', 'amount' => '1,590,000đ', 'status' => 'CHỜ DUYỆT', 'time' => '5 phút trước'],
                        ['id' => '#VNT-9979', 'customer' => 'Phạm Thúy Vy', 'amount' => '8,400,000đ', 'status' => 'HOÀN TẤT', 'time' => '12 phút trước'],
                        ['id' => '#VNT-9978', 'customer' => 'Hoàng Kim Ngân', 'amount' => '2,150,000đ', 'status' => 'ĐANG XỬ LÝ', 'time' => '18 phút trước'],
                    ];
                @endphp

                @foreach($orders as $order)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4 text-neon-green text-center">{{ $order['id'] }}</td>
                    <td class="px-6 py-4 text-white font-medium">{{ $order['customer'] }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $order['amount'] }}</td>
                    <td class="px-6 py-4">
                        @if($order['status'] === 'HOÀN TẤT')
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-neon-green/10 text-neon-green border-neon-green/20">
                                {{ $order['status'] }}
                            </span>
                        @elseif($order['status'] === 'ĐANG XỬ LÝ')
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-blue-500/10 text-blue-400 border-blue-500/20">
                                {{ $order['status'] }}
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded text-[9px] font-bold border bg-red-500/10 text-red-500 border-red-500/20 animate-pulse">
                                {{ $order['status'] }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-right group-hover:text-gray-300 transition-colors">{{ $order['time'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{
                name: 'Doanh thu',
                data: [4000000, 5500000, 3800000, 6500000, 4200000, 8900000, 12000000]
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
                categories: ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY', 'CHỦ NHẬT'],
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
