@extends('layouts.main')

@section('title', 'Sale')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
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
            overflow: auto; /* Allow card to scroll if content is too large */
        }

        .card {
            height: auto; /* Let card height adjust to content */
        }

    </style>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Sales Management</h1>
            <div class="user-menu">
                <!-- User menu would go here -->
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Raw Material Stock</div>
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $rawStock }}</div>
                <div class="stat-meta {{ $lowStockRaw > 0 ? 'warning' : '' }}">
                    <i class="fas {{ $lowStockRaw > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                    <span>{{ $lowStockRaw }} low stock items</span>
                </div>
            </div>

            <div class="stat-card wholesale">
                <div class="stat-header">
                    <div class="stat-title">Wholesale Stock</div>
                    <div class="stat-icon" style="background: rgba(72, 149, 239, 0.1); color: var(--info);">
                        <i class="fas fa-pallet"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $wholesaleStock }}</div>
                <div class="stat-meta {{ $lowStockWholesale > 0 ? 'warning' : '' }}">
                    <i class="fas {{ $lowStockWholesale > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                    <span>{{ $lowStockWholesale }} low stock items</span>
                </div>
            </div>

            <div class="stat-card finished">
                <div class="stat-header">
                    <div class="stat-title">Finished Products</div>
                    <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $finishedStock }}</div>
                <div class="stat-meta {{ $lowStockFinished > 0 ? 'warning' : '' }}">
                    <i class="fas {{ $lowStockFinished > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                    <span>{{ $lowStockFinished }} low stock items</span>
                </div>
            </div>

            <div class="stat-card profit-yesterday">
                <div class="stat-header">
                    <div class="stat-title">Profit Yesterday</div>
                    <div class="stat-icon" style="background: rgba(247, 37, 133, 0.1); color: var(--warning);">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="stat-value">₱{{ number_format($profitYesterday,2) }}</div>
                <div class="stat-meta">
                    <i class="fas fa-calendar-day"></i>
                    <span>Previous day</span>
                </div>
            </div>

            <div class="stat-card profit-today">
                <div class="stat-header">
                    <div class="stat-title">Profit Today</div>
                    <div class="stat-icon" style="background: rgba(230, 57, 70, 0.1); color: var(--danger);">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div class="stat-value">₱{{ number_format($profitToday,2) }}</div>
                <div class="stat-meta">
                    <i class="fas fa-chart-line"></i>
                    <span>Today's earnings</span>
                </div>
            </div>
        </div>

        <!-- POS and Completed Payments -->
        <div class="content-grid">
            <!-- POS Card -->
            <div class="card">
                <div class="card-header">
                    <h5>Point of Sale</h5>
                    <div class="card-tabs">
                        <button class="tab-btn active" id="directOrderBtn">Direct Order</button>
                        <button class="tab-btn" id="onlineOrderBtn">Online Orders</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Direct Order Form -->
                    <div id="directOrderForm">
                    <form action="{{ route('sale.store') }}" method="POST" id="posForm">
                        @csrf
                        <table class="table display" id="directOrderTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div>{{ $product->name }}</div>
                                        <div class="badge {{ $product->stock > 20 ? 'badge-success' : ($product->stock > 5 ? 'badge-warning' : 'badge-danger') }}">
                                            Stock: {{ $product->stock }}
                                        </div>
                                    </td>
                                    <td>₱<span class="price">{{ number_format($product->price,2) }}</span></td>
                                    <td>
                                        <input type="number" name="items[{{ $product->id }}][quantity]" min="0" value="0" class="form-control qty" data-price="{{ $product->price }}" style="max-width: 80px;">
                                        <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                    </td>
                                    <td>₱<span class="subtotal">0.00</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="payment-summary">
                            <div class="summary-row">
                                <span class="summary-label">Total:</span>
                                <span class="summary-value" id="totalAmount">₱0.00</span>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Cash Received:</label>
                                <input type="number" id="cashInput" name="cash" step="0.01" value="0" class="form-control" required>
                            </div>
                            
                            <div class="summary-row highlight">
                                <span class="summary-label">Change:</span>
                                <span class="summary-value" id="changeOutput">₱0.00</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success" style="margin-top: 20px; width: 100%;">
                            <i class="fas fa-check-circle"></i> Complete Payment
                        </button>
                    </form>
                </div>

                    <!-- Online Orders Form -->
                    <div id="onlineOrderForm" class="hidden">
                        @php
                            $onlineOrders = $completedOrders->filter(fn($order) => 
                                $order->orderitem->count() > 0 && (($order->amount_paid ?? 0) - $order->orderitem->sum(fn($item) => $item->quantity * $item->price)) < 0
                            );
                        @endphp

                        @if($onlineOrders->count() > 0)
                        <form action="{{ route('sale.onlinepay') }}" method="POST">
                            @csrf
                            <table class="table display" id="onlineOrderTable">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Products</th>
                                        <th>Total</th>
                                        <th>Cash</th>
                                        <th>Change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($onlineOrders as $order)
                                    @php
                                        $total = $order->orderitem->sum(fn($item) => $item->quantity * $item->price);
                                        $paid = $order->amount_paid ?? 0;
                                        $change = $paid - $total;
                                        if ($change >= 0) continue;
                                    @endphp
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                                        <td>
                                            <ul class="mb-0">
                                                @foreach($order->orderitem as $item)
                                                    <li>{{ $item->product->name ?? 'Product Deleted' }} x {{ $item->quantity }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>₱{{ number_format($total,2) }}</td>
                                        <td>
                                            <input type="number" name="cash[{{ $order->id }}]" step="0.01" value="{{ $paid }}" class="form-control onlineCash" data-total="{{ $total }}" style="max-width: 120px;">
                                        </td>
                                        <td class="change">₱{{ number_format(max(0, $change),2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-money-bill-wave"></i> Process Payments
                            </button>
                        </form>
                        @else
                        <div style="text-align: center; padding: 30px;">
                            <i class="fas fa-check-circle" style="font-size: 48px; color: var(--success); margin-bottom: 16px;"></i>
                            <p>No online orders pending payment.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Completed Payments Card -->
            <div class="card">
                <div class="card-header" style="background: var(--success);">
                    <h5>Completed Payments</h5>
                </div>
                <div class="card-body">
                    @php
                        $paidOrders = $completedOrders->filter(fn($order) => ($order->amount_paid ?? 0) > 0);
                    @endphp

                    @if($paidOrders->count() > 0)
                        <table class="table display" id="completedTable">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Change</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paidOrders as $order)
                                @php
                                    $total = $order->orderitem->sum(fn($item) => $item->quantity * $item->price);
                                    $paid = $order->amount_paid ?? 0;
                                    $change = $order->change_due ?? ($paid - $total);
                                @endphp
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>₱{{ number_format($total,2) }}</td>
                                    <td>₱{{ number_format($paid,2) }}</td>
                                    <td>₱{{ number_format($change,2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="text-align: center; padding: 30px;">
                            <i class="fas fa-receipt" style="font-size: 48px; color: var(--gray-400); margin-bottom: 16px;"></i>
                            <p>No completed payments yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Products Card -->
        <div class="card">
            <div class="card-header" style="background: var(--gray-700);">
                <h5>Recently Updated Products</h5>
            </div>
            <div class="card-body">
                <table class="product-table display" id="recentTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Avg Daily Sales</th>
                            <th>Estimated Days Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProducts as $product)
                        <tr>
                            <td>
                                <span class="status-indicator {{ $product->stock > 20 ? 'status-high' : ($product->stock > 5 ? 'status-medium' : 'status-low') }}"></span>
                                {{ $product->name }}
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ number_format($product->avgDailySales,2) }}</td>
                            <td>
                                <span class="badge {{ $product->estimatedDays > 14 ? 'badge-success' : ($product->estimatedDays > 7 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $product->estimatedDays }} days
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const directBtn = document.getElementById('directOrderBtn');
            const onlineBtn = document.getElementById('onlineOrderBtn');
            const directForm = document.getElementById('directOrderForm');
            const onlineForm = document.getElementById('onlineOrderForm');

            function showForm(form) {
                if (form === 'direct') {
                    directForm.classList.remove('hidden');
                    onlineForm.classList.add('hidden');
                    directBtn.classList.add('active');
                    onlineBtn.classList.remove('active');
                } else {
                    directForm.classList.add('hidden');
                    onlineForm.classList.remove('hidden');
                    directBtn.classList.remove('active');
                    onlineBtn.classList.add('active');
                }
                localStorage.setItem('posActiveForm', form);
            }

            const lastForm = localStorage.getItem('posActiveForm') || 'direct';
            showForm(lastForm);

            directBtn.addEventListener('click', () => showForm('direct'));
            onlineBtn.addEventListener('click', () => showForm('online'));

           const dataTableOptions = {
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                searching: true,
                paging: true,
                info: true,
                responsive: true,
                language: {
                    paginate: {
                        previous: "&lt;",
                        next: "&gt;"
                    }
                },
                pagingType: 'simple_numbers', // Use simple pagination with numbers
                drawCallback: function() {
                    $('.dataTables_paginate .pagination').addClass('custom-pagination');
                }
            };

            $('#directOrderTable').DataTable({
                ...dataTableOptions,
                columnDefs: [
                    { orderable: false, targets: [2, 3] }
                ]
            });

            $('#onlineOrderTable').DataTable({
                ...dataTableOptions,
                columnDefs: [
                    { orderable: false, targets: [4, 5] }
                ]
            });

            $('#completedTable').DataTable(dataTableOptions);

            $('#recentTable').DataTable(dataTableOptions);


            document.querySelectorAll('.onlineCash').forEach(input => {
                input.addEventListener('focus', function() {
                    if (parseFloat(this.value) === 0) this.value = '';
                });
                input.addEventListener('blur', function() {
                    if (this.value === '') this.value = '0.00';
                });
                input.addEventListener('input', function() {
                    const total = parseFloat(this.dataset.total) || 0;
                    const paid = parseFloat(this.value) || 0;
                    const change = paid - total;
                    this.closest('tr').querySelector('.change').textContent = `₱${change.toFixed(2)}`;
                });
            });

            // POS calculation logic
            const qtyInputs = document.querySelectorAll('.qty');
            const totalAmount = document.getElementById('totalAmount');
            const cashInput = document.getElementById('cashInput');
            const changeOutput = document.getElementById('changeOutput');

            function calculateTotals() {
                let total = 0;
                qtyInputs.forEach(input => {
                    const qty = parseFloat(input.value) || 0;
                    const price = parseFloat(input.dataset.price) || 0;
                    const subtotal = qty * price;
                    input.closest('tr').querySelector('.subtotal').textContent = subtotal.toFixed(2);
                    total += subtotal;
                });
                totalAmount.textContent = `₱${total.toFixed(2)}`;
                calculateChange();
            }

            function calculateChange() {
                const total = parseFloat(totalAmount.textContent.replace('₱','')) || 0;
                const cash = parseFloat(cashInput.value) || 0;
                const change = cash - total;
                changeOutput.textContent = `₱${change.toFixed(2)}`;
            }

            qtyInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (parseFloat(this.value) === 0) this.value = '';
                });
                input.addEventListener('blur', function() {
                    if (this.value === '') this.value = '0';
                    calculateTotals();
                });
                input.addEventListener('input', calculateTotals);
            });

            cashInput.addEventListener('focus', function() {
                if (parseFloat(this.value) === 0) this.value = '';
            });
            cashInput.addEventListener('blur', function() {
                if (this.value === '') this.value = '0.00';
                calculateChange();
            });
            cashInput.addEventListener('input', calculateChange);
        });
    </script>
@endpush