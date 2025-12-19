@extends('layouts.main')

@section('content')
<style>
    /* Modern, professional UI styling */
    body {
        background: #f8fafc;
        font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }

    .dashboard-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .dashboard-button {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: #ffffff;
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .dashboard-button:hover {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Modern card design */
    .modern-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .modern-card:focus-within {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    .card-header {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.1;
        background: linear-gradient(to right, #1e293b, #334155);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .icon-circle:hover {
        transform: scale(1.1);
    }

    .bg-primary { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: #ffffff; }
    .bg-success { background: linear-gradient(135deg, #10b981, #34d399); color: #ffffff; }
    .bg-info { background: linear-gradient(135deg, #06b6d4, #22d3ee); color: #ffffff; }
    .bg-warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #ffffff; }

    .text-primary { background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-success { background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-info { background: linear-gradient(to right, #06b6d4, #22d3ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-warning { background: linear-gradient(to right, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* Modern table and list styles */
    .modern-table {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .modern-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
    }

    .modern-table thead th {
        padding: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.075em;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table tbody td {
        padding: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #1e293b;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.2s ease;
    }

    .modern-list-group {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .modern-list-group .list-group-item {
        border: none;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem;
        transition: all 0.2s ease;
    }

    .modern-list-group .list-group-item:hover {
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .modern-list-group .list-group-item:last-child {
        border-bottom: none;
    }

    .card-subtext {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.5rem;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin: 3rem 0 1.5rem;
        letter-spacing: -0.025em;
    }

    /* Chart container */
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.75rem;
        }
        .stat-value {
            font-size: 1.75rem;
        }
        .modern-card {
            padding: 1.25rem;
        }
        .icon-circle {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }
        .chart-container {
            height: 250px;
        }
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="dashboard-title">Sales Dashboard</h1>
        <!-- <a href="#" class="dashboard-button" data-bs-toggle="modal" data-bs-target="#posModal">
       <i class="fas fa-cash-register fa-sm me-2"></i> Point of Sale
</a>

 <a href="#" class="dashboard-button" data-bs-toggle="modal" data-bs-target="#onlineOrdersModal">
       <i class="fas fa-cash-register fa-sm me-2"></i> Pos Online
</a> -->

    </div>

    <!-- KPI Summary Row -->
    <div class="row">
        @php
            $kpis = [
                ['title' => 'Quarterly Revenue', 'value' => "₱" . number_format($quarterRevenue, 2), 'icon' => 'fas fa-dollar-sign', 'color' => 'primary', 'bg' => 'rgba(78, 115, 223, 0.1)'],
                ['title' => 'Deals Closed (MTD)', 'value' => $dealsClosed, 'icon' => 'fas fa-trophy', 'color' => 'success', 'bg' => 'rgba(28, 200, 138, 0.1)'],
                ['title' => 'Active Pipeline Value', 'value' => "₱" . number_format($activePipeline, 2), 'icon' => 'fas fa-funnel-dollar', 'color' => 'info', 'bg' => 'rgba(54, 185, 204, 0.1)'],
                ['title' => 'Win Rate (QTD)', 'value' => $winRate . '%', 'icon' => 'fas fa-percent', 'color' => 'warning', 'bg' => 'rgba(246, 194, 62, 0.1)'],
            ];
        @endphp

        @foreach($kpis as $kpi)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-{{ $kpi['color'] }} me-3">
                        <i class="{{ $kpi['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="card-header">{{ $kpi['title'] }}</div>
                        <div class="stat-value text-{{ $kpi['color'] }}">{{ $kpi['value'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- End KPI Row -->

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="modern-card">
                <div class="card-header">Revenue Performance</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                    <div class="card-subtext">
                        Monthly actual revenue vs. forecasted targets
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="modern-card">
                <div class="card-header">Pipeline by Stage</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pipelineStageChart"></canvas>
                    </div>
                    <div class="card-subtext">
                        Breakdown of opportunities by sales stage
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Charts Row -->

    <!-- Recent Activity & Top Performers Row -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-header">Recent Activity & Tasks</div>
                <div class="modern-list-group">
                    @foreach($recentActivity as $order)
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Order #{{ $order->order_number }} - {{ ucfirst($order->status) }}</h6>
                            <small class="card-subtext">{{ $order->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">Customer: {{ $order->user->name }}</p>
                        <small class="card-subtext">Total: ₱{{ number_format($order->total_amount, 2) }}</small>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="modern-card">
                <div class="card-header">Quarterly Leaderboard</div>
                <div class="modern-table">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Rep Name</th>
                                    <th>Deals Closed</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topPerformers as $rep)
                                <tr>
                                    <td>{{ $rep->name }}</td>
                                    <td>{{ $rep->deals_closed }}</td>
                                    <td>₱{{ number_format($rep->revenue, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-subtext">
                        Performance ranking based on quarterly revenue
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Activity Row -->
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueTrendChart');
    if(revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json(array_keys($monthlyRevenue)),
                datasets: [{
                    label: 'Revenue',
                    data: @json(array_values($monthlyRevenue)),
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: { backgroundColor: '#1e293b', titleColor: '#ffffff', bodyColor: '#ffffff' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                    x: { beginAtZero: false, grid: { display: false } }
                }
            }
        });
    }

    // Pipeline by Stage Chart
    const pipelineCtx = document.getElementById('pipelineStageChart');
    if(pipelineCtx) {
        new Chart(pipelineCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($pipelineStages)),
                datasets: [{
                    label: 'Pipeline Value',
                    data: @json(array_values($pipelineStages)),
                    backgroundColor: ['#3b82f6', '#10b981', '#06b6d4', '#f59e0b'],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 }, color: '#1e293b' } },
                    tooltip: { backgroundColor: '#1e293b', titleColor: '#ffffff', bodyColor: '#ffffff' }
                }
            }
        });
    }
});
</script>

@endsection
