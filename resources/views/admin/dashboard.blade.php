{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.main')

@section('content')
<style>
/* General Page */
body {
    background: linear-gradient(135deg, #f8fafc, #e8edf5);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h1,h2,h6 {
    font-weight: 600;
    color: #1e293b;
}

/* Glass Card */
.glass-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(200,200,200,0.2);
    transition: all 0.3s ease-in-out;
}
.glass-card:hover {
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

/* KPI Cards */
.kpi-card {
    display: flex;
    align-items: center;
    padding: 20px;
    border-left: 4px solid;
    transition: transform 0.3s;
}
.kpi-card:hover { transform: scale(1.03); }
.kpi-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    border-radius: 50%;
    margin-right: 15px;
    box-shadow: 0 4px 12px rgba(253, 208, 5, 0.1);
}

/* Table Hover */
table.table-hover tbody tr:hover {
    background-color: rgba(59,130,246,0.08);
}

/* Badges */
.badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Charts */
.chart-card {
    height: 300px;
}
</style>

<div class="row">

    <h1 class="mt-4">DASHBOARD</h1>
    <p class="text-muted">Welcome to <strong>CSPC FabLab Inventory & Sales Management System</strong></p>

    <!-- KPI Cards -->
    <div class="row g-4">
        @php
        $kpis = [
            ['title'=>'Inventory', 'value'=>$totalInventory.' Items', 'icon'=>'fa-boxes', 'color'=>'primary'],
            ['title'=>'Users', 'value'=>$totalUsers.' Active', 'icon'=>'fa-users', 'color'=>'warning'],
            ['title'=>'Today Sales', 'value'=>'₱'.number_format($totalSaleDay,2), 'icon'=>'fa-cash-register', 'color'=>'success'],
            ['title'=>'Week Sales', 'value'=>'₱'.number_format($totalSaleWeek,2), 'icon'=>'fa-calendar-week', 'color'=>'info'],
            ['title'=>'Month Sales', 'value'=>'₱'.number_format($totalSaleMonth,2), 'icon'=>'fa-calendar', 'color'=>'primary'],
            ['title'=>'Year Sales', 'value'=>'₱'.number_format($totalSaleYear,2), 'icon'=>'fa-calendar-alt', 'color'=>'secondary'],
        ];
        @endphp

        @foreach($kpis as $kpi)
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="glass-card kpi-card border-left-{{ $kpi['color'] }}">
                <div class="kpi-icon bg-{{ $kpi['color'] }}">
                    <i class="fas {{ $kpi['icon'] }}"></i>
                </div>
                <div>
                    <h6 class="text-{{ $kpi['color'] }} mb-1">{{ $kpi['title'] }}</h6>
                    <p class="h6 fw-bold mb-0">{{ $kpi['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="row mt-4 g-2">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="glass-card p-3">
                <h6 class="mb-3 text-primary fw-bold">Revenue Performance</h6>
                <div class="chart-card">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="glass-card p-3">
                <h6 class="mb-3 text-primary fw-bold">Pipeline by Stage</h6>
                <div class="chart-card">
                    <canvas id="pipelineStageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <h2 class="mt-6">RECENT ORDERS</h2>
    <div class="glass-card p-3 mb-4">
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->order_number ?? 'ORD-'.$order->id }}</td>
                        <td>
                            @if($order->status=='completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($order->status=='pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td class="fw-bold">₱{{ number_format($order->total_amount,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inventory Status -->
    <!-- <h2 class="mt-4">INVENTORY STATUS</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="glass-card text-center p-4">
                <h6 class="text-danger">Low Stock Alert</h6>
                <p class="fs-1 fw-bold">{{ $lowStockCount }}</p>
                <p class="text-muted">Items running low</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass-card text-center p-4">
                <h6 class="text-primary">Digital Receipts</h6>
                <button class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#receiptModal">View Receipts</button>
                <p class="text-muted mt-2">Sample digital receipts</p>
            </div>
        </div>
    </div> -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // --- Revenue Trend Chart with Month-over-Month % Change ---
    const revenueCtx = document.getElementById('revenueTrendChart');
    if(revenueCtx){
        // Original revenue data from controller
        const revenueLabels = @json(array_keys($months));
        const revenueData = @json(array_values($months));

        // Calculate Month-over-Month % Change
        const revenueChange = revenueData.map((val, idx, arr) => {
            if(idx === 0) return 0;
            return ((val - arr[idx-1])/arr[idx-1]*100).toFixed(2);
        });

        new Chart(revenueCtx,{
            type:'line',
            data:{
                labels: revenueLabels,
                datasets:[
                    {
                        label:'Revenue (₱)',
                        data: revenueData,
                        backgroundColor:'rgba(78,115,223,0.1)',
                        borderColor:'rgba(78,115,223,1)',
                        borderWidth:2,
                        fill:true,
                        tension:0.3,
                        pointRadius:4,
                        pointBackgroundColor:'rgba(78,115,223,1)',
                        yAxisID:'y'
                    },
                    {
                        label:'Month % Change',
                        data: revenueChange,
                        borderColor:'rgba(28,200,138,1)',
                        backgroundColor:'rgba(28,200,138,0.1)',
                        fill:true,
                        tension:0.3,
                        pointRadius:4,
                        yAxisID:'y1'
                    }
                ]
            },
            options:{
                responsive:true,
                maintainAspectRatio:false,
                plugins:{legend:{display:true}},
                scales:{
                    y:{
                        beginAtZero:true,
                        position:'left',
                        title:{ display:true, text:'Revenue (₱)' }
                    },
                    y1:{
                        beginAtZero:true,
                        position:'right',
                        title:{ display:true, text:'% Change' },
                        grid:{ drawOnChartArea:false }
                    },
                    x:{ beginAtZero:false }
                }
            }
        });
    }

    // --- Pipeline by Stage Chart ---
    const pipelineCtx = document.getElementById('pipelineStageChart');
    if(pipelineCtx){
        new Chart(pipelineCtx,{
            type:'doughnut',
            data:{
                labels:['Lead','Prospect','Negotiation','Closed'],
                datasets:[{
                    label:'Pipeline Value',
                    data:[12000, 9000, 6000, 3000],
                    backgroundColor:['#4e73df','#1cc88a','#36b9cc','#f6c23e'],
                    hoverOffset:10
                }]
            },
            options:{
                responsive:true,
                maintainAspectRatio:false,
                plugins:{legend:{position:'bottom'}}
            }
        });
    }
});
</script>
@endsection
