@extends('layouts.main')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --light: #f8f9fa;
            --dark: #212529;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --border-radius: 12px;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--gray-800);
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1920px;
            margin: 0 auto;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-800);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border-left: 4px solid var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card.wholesale {
            border-left-color: var(--info);
        }

        .stat-card.finished {
            border-left-color: var(--success);
        }

        .stat-card.profit-yesterday {
            border-left-color: var(--warning);
        }

        .stat-card.profit-today {
            border-left-color: var(--danger);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-title {
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--gray-800);
        }

        .stat-meta {
            font-size: 13px;
            color: var(--gray-600);
            display: flex;
            align-items: center;
        }

        .stat-meta.warning {
            color: var(--danger);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 30px;
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .card-header {
            padding: 20px 24px;
            background: var(--primary);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h5 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .card-tabs {
            display: flex;
            gap: 8px;
        }

        .tab-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .tab-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .tab-btn.active {
            background: white;
            color: var(--primary);
        }

        .card-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 12px 16px;
            background: var(--gray-100);
            font-weight: 600;
            color: var(--gray-700);
        }

        .table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-200);
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #1159b8ff;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: rgba(76, 201, 240, 0.15);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(247, 37, 133, 0.15);
            color: var(--warning);
        }

        .badge-danger {
            background: rgba(230, 57, 70, 0.15);
            color: var(--danger);
        }

        .payment-summary {
            background: var(--gray-100);
            padding: 16px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .summary-label {
            color: var(--gray-600);
        }

        .summary-value {
            font-weight: 600;
        }

        .highlight {
            font-size: 18px;
            color: var(--primary);
        }

        .hidden {
            display: none;
        }

        .product-table {
            width: 100%;
        }

        .product-table th {
            background: var(--gray-100);
            padding: 12px 16px;
            font-weight: 600;
            color: var(--gray-700);
        }

        .product-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-200);
        }

        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-low {
            background: var(--danger);
        }

        .status-medium {
            background: var(--warning);
        }

        .status-high {
            background: var(--success);
        }

        /* DataTables adjustments */
        .dataTables_wrapper {
            width: 100%;
            margin-bottom: 20px;
        }

        .dataTables_length select {
            width: auto;
            margin-right: 10px;
        }

        .dataTables_filter input {
            width: auto;
            margin-left: 10px;
        }

        .card-body {
            overflow: auto;
        }

        .card {
            height: auto;
        }

        /* Additional styles for dashboard */
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .list-group-item {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding: 0.75rem 1rem;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .progress {
            height: 8px;
        }

        .kpi-card {
            padding: 1rem;
            min-height: 150px;
        }

        .kpi-card i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .kpi-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .kpi-card .label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .kpi-card .trend {
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }

        .kpi-primary { background: linear-gradient(135deg, rgba(0, 33, 179, 0.1), rgba(12, 7, 109, 0.1)); }
        .kpi-warning { background: linear-gradient(135deg, rgba(129, 112, 85, 0.1), rgba(63, 50, 21, 0.1)); }
        .kpi-success { background: linear-gradient(135deg, rgba(42, 87, 100, 0.1), rgba(33, 95, 66, 0.1)); }
        .kpi-danger { background: linear-gradient(135deg, rgba(73, 30, 49, 0.1), rgba(109, 25, 33, 0.1)); }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Staff Dashboard</h1>
            <div class="user-menu">
                <!-- User menu would go here -->
            </div>
        </div>

        <!-- Order KPIs -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Orders Today</div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $ordersToday }}</div>
                <div class="stat-meta">
                    <i class="fas {{ $orderTrends['today'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    <span>{{ abs($orderTrends['today']) }}% from yesterday</span>
                </div>
            </div>

            <div class="stat-card wholesale">
                <div class="stat-header">
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-icon" style="background: rgba(72, 149, 239, 0.1); color: var(--info);">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $ordersPending }}</div>
                <div class="stat-meta">
                    <i class="fas {{ $orderTrends['pending'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    <span>{{ abs($orderTrends['pending']) }}% from yesterday</span>
                </div>
            </div>

            <div class="stat-card finished">
                <div class="stat-header">
                    <div class="stat-title">Completed Orders</div>
                    <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $ordersCompleted }}</div>
                <div class="stat-meta">
                    <i class="fas {{ $orderTrends['completed'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    <span>{{ abs($orderTrends['completed']) }}% from yesterday</span>
                </div>
            </div>

            <div class="stat-card profit-yesterday">
                <div class="stat-header">
                    <div class="stat-title">Cancelled Orders</div>
                    <div class="stat-icon" style="background: rgba(247, 37, 133, 0.1); color: var(--warning);">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $ordersCancelled }}</div>
                <div class="stat-meta">
                    <i class="fas {{ $orderTrends['cancelled'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    <span>{{ abs($orderTrends['cancelled']) }}% from yesterday</span>
                </div>
            </div>
        </div>

        <!-- Finance KPIs -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Income Today</div>
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="stat-value">₱{{ number_format($incomeToday, 2) }}</div>
                <div class="stat-meta">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ $incomeProgress }}% of daily target</span>
                </div>
            </div>

            <div class="stat-card wholesale">
                <div class="stat-header">
                    <div class="stat-title">Total Cost</div>
                    <div class="stat-icon" style="background: rgba(72, 149, 239, 0.1); color: var(--info);">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="stat-value">₱{{ number_format($totalCost, 2) }}</div>
                <div class="stat-meta">
                    <i class="fas fa-percentage"></i>
                    <span>Cost to revenue ratio: {{ $costToRevenueRatio }}%</span>
                </div>
            </div>

            <div class="stat-card finished">
                <div class="stat-header">
                    <div class="stat-title">Best Selling Product</div>
                    <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $bestSelling->name ?? 'N/A' }}</div>
                <div class="stat-meta">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Sold: {{ $bestSelling->total_sold ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Profit Trend + Low Stock -->
        <div class="content-grid">
            <!-- Profit Trend Chart -->
            <div class="card">
                <div class="card-header">
                    <h5>Profit Trend (Monthly)</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="profitTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="card">
                <div class="card-header" style="background: var(--danger);">
                    <h5>Low Stock Products</h5>
                </div>
                <div class="card-body">
                    @forelse($lowStockProducts as $product)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-cube me-3 text-secondary"></i>
                            <div>
                                <div class="fw-medium">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                            </div>
                        </div>
                        <span class="badge bg-danger">{{ $product->stock }} left</span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fs-2 mb-2 text-success"></i>
                        <p class="mb-0">No low stock items</p>
                    </div>
                    @endforelse
                    @if($lowStockProducts->count() > 0)
                    <div style="margin-top: 20px;">
                        <a href="#" class="btn btn-sm btn-outline-danger" style="width: 100%;">
                            <i class="fas fa-plus-circle"></i> Restock Items
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <h5>Recent Orders</h5>
            </div>
            <div class="card-body">
                <table class="table display" id="recentOrdersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="background: rgba(67, 97, 238, 0.1); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $order->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: var(--gray-200); color: var(--gray-800);">
                                    <i class="fas fa-hashtag text-secondary"></i>
                                    {{ $order->order_number ?? 'ORD-'.$order->id }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(67, 97, 238, 0.1); color: var(--primary);">
                                    {{ $order->items_count ?? 1 }} items
                                </span>
                            </td>
                            <td>
                                @if($order->status == 'completed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @elseif($order->status == 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-hourglass-half"></i> Pending
                                    </span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">
                                        <i class="fas fa-sync-alt"></i> Processing
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-info-circle"></i> {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-frown fs-1 text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0">No recent orders found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTables
            $('#recentOrdersTable').DataTable({
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                searching: true,
                paging: true,
                info: true,
                responsive: true,
                language: {
                    paginate: {
                        previous: "&lt;",
                        next: "&gt;"
                    }
                }
            });

            // Profit Trend Chart
            const ctx = document.getElementById('profitTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($profitMonths),
                    datasets: [
                        {
                            label: 'Profit',
                            data: @json($profitAmounts),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.2)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#16a34a',
                            borderWidth: 2
                        },
                        {
                            label: 'Revenue',
                            data: @json($revenueAmounts),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            fill: false,
                            tension: 0.4,
                            borderDash: [5, 5],
                            borderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#2563eb'
                        },
                        {
                            label: 'Cost',
                            data: @json($costAmounts),
                            borderColor: '#dc2626',
                            backgroundColor: 'rgba(220, 38, 38, 0.1)',
                            fill: false,
                            tension: 0.4,
                            borderDash: [5, 5],
                            borderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#dc2626'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ₱' + new Intl.NumberFormat().format(context.raw);
                                }
                            }
                        }
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + new Intl.NumberFormat().format(value);
                                }
                            }
                        } 
                    }
                }
            });
        });
    </script>
@endpush