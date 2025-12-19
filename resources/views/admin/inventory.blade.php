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

    .dashboard-subtitle {
        font-size: 1.125rem;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 2rem;
    }

    /* Enhanced modern card design with subtle gradients */
    .modern-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05), 0 4px 16px rgba(0, 0, 0, 0.05);
        padding: 1.75rem;
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
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.1;
        background: linear-gradient(to right, #1e293b, #334155);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .card-subtext {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    /* Refined icon styling with gradient backgrounds */
    .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: transform 0.3s ease;
    }

    .icon-wrapper:hover {
        transform: scale(1.1);
    }

    .icon-primary { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: #ffffff; }
    .icon-warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #ffffff; }
    .icon-danger { background: linear-gradient(135deg, #ef4444, #f87171); color: #ffffff; }
    .icon-success { background: linear-gradient(135deg, #10b981, #34d399); color: #ffffff; }

    .text-primary { background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-warning { background: linear-gradient(to right, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-danger { background: linear-gradient(to right, #ef4444, #f87171); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-success { background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* Enhanced table design with improved spacing */
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

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modernized badge styling with subtle animations */
    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .badge:hover {
        transform: translateY(-1px);
    }

    .badge-primary { background: #dbeafe; color: #1e40af; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin: 3rem 0 1.5rem;
        letter-spacing: -0.025em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.75rem;
        }
        .dashboard-subtitle {
            font-size: 1rem;
        }
        .stat-value {
            font-size: 1.75rem;
        }
        .modern-card {
            padding: 1.25rem;
        }
        .icon-wrapper {
            width: 48px;
            height: 48px;
            font-size: 1.5rem;
        }
    }
</style>
<div class="container-fluid px-4" style="margin-left: 20px;">
    <h1 class="dashboard-title">Inventory Management</h1>
    <p class="dashboard-subtitle">Real-time insights into stock levels and performance per category.</p>

    <div class="row">
        <!-- Raw Materials -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="card-header">Raw Materials</div>
                <p class="stat-value text-primary">{{ $rawStock }}</p>
                <p class="card-subtext">Low Stock: {{ $lowStockRaw }}</p>
            </div>
        </div>

        <!-- Wholesale -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="card-header">Wholesale</div>
                <p class="stat-value text-warning">{{ $wholesaleStock }}</p>
                <p class="card-subtext">Low Stock: {{ $lowStockWholesale }}</p>
            </div>
        </div>

        <!-- Finished Products -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="card-header">Finished Products</div>
                <p class="stat-value text-success">{{ $finishedStock }}</p>
                <p class="card-subtext">Low Stock: {{ $lowStockFinished }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card">
                <div class="card-header">Total Stock</div>
                <p class="stat-value text-primary">{{ $totalStock }}</p>
                <p class="card-subtext">Units in inventory</p>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card">
                <div class="card-header">Out of Stock</div>
                <p class="stat-value text-danger">{{ $outOfStockCount }}</p>
                <p class="card-subtext">Items unavailable</p>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card">
                <div class="card-header">Inventory Value</div>
                <p class="stat-value text-success">₱{{ number_format($totalRetailValue, 2) }}</p>
                <p class="card-subtext">Cost: ₱{{ number_format($totalCostValue, 2) }}</p>
            </div>
        </div>

        <!-- Profit -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card">
                <div class="card-header">Profit</div>
                <p class="stat-value text-success">₱{{ number_format($profit, 2) }}</p>
                <p class="card-subtext">Total earnings</p>
            </div>
        </div>
    </div>

    <!-- Recently Updated Products -->
    <h2 class="section-title">Recently Updated Products</h2>
    <div class="modern-table mb-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Daily Sales</th>
                        <th>Est. Days Until Out</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProducts as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge badge-primary">{{ $product->sku }}</span></td>
                            <td style="font-weight: 500;">{{ $product->name }}</td>
                            <td>
                                <span class="badge badge-success">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td>
                                @if($product->stock == 0)
                                    <span class="badge badge-danger">Out</span>
                                @elseif($product->stock < 5)
                                    <span class="badge badge-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge badge-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td style="font-weight: 500;">₱{{ number_format($product->price, 2) }}</td>
                            <td>{{ number_format($product->avgDailySales, 2) }}</td>
                            <td>
                                @if($product->estimatedDays === '∞')
                                    <span class="badge badge-primary">Stable</span>
                                @elseif($product->estimatedDays < 5)
                                    <span class="badge badge-danger">{{ $product->estimatedDays }} days</span>
                                @else
                                    <span class="badge badge-success">{{ $product->estimatedDays }} days</span>
                                @endif
                            </td>
                            <td class="card-subtext">{{ $product->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center card-subtext" style="padding: 2.5rem;">
                                No recent updates
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection