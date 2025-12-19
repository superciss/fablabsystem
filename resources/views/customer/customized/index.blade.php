@extends('layouts.maincustomer')

@section('title', 'Customized Products')

@section('content')

<style>
    body {
        position: relative;
        min-height: 100vh;
        background: linear-gradient(135deg, rgba(18, 50, 80, 1), rgba(26, 62, 99, 1));
        overflow-x: hidden;
        opacity: 1;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('/images/logo.png') center/cover no-repeat;
        opacity: 0.25;
        filter: blur(1px);
        z-index: -1;
    }

    .dashboard-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .preview-img {
        width: 200px;
        height: 160px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        object-fit: cover;
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h2 class="dashboard-title">Customized Products</h2>
    </div>

    <div class="row g-4">
        @forelse($customizations as $custom)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card shadow-sm h-100" style="border-radius:16px">

                    {{-- IMAGE --}}
                    <img src="{{ $custom->front_image }}"
                         class="card-img-top"
                         style="height:180px; object-fit:cover; border-radius:16px 16px 0 0">

                    <div class="card-body">

                        {{-- DESCRIPTION --}}
                        <h6 class="fw-bold mb-1">
                            {{ Str::limit($custom->description, 60) }}
                        </h6>

                        {{-- STATUS --}}
                        <div class="mb-2">
                            @if($custom->approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning text-dark">Not Approved</span>
                            @endif
                        </div>

                        <div class="row small">
                            <div class="col-6 mb-2">
                                <span class="text-muted">Qty</span>
                                <div class="fw-semibold">{{ $custom->quantity }}</div>
                            </div>

                            <div class="col-6 mb-2">
                                <span class="text-muted">Date</span>
                                <div class="fw-semibold">
                                    {{ $custom->created_at->format('M d, Y') }}
                                </div>
                            </div>

                            <div class="col-6 mb-2">
                                <span class="text-muted">Total</span>
                                <div class="fw-bold text-primary">
                                    ₱{{ number_format($custom->total_price, 2) }}
                                </div>
                            </div>

                            <div class="col-6 mb-2">
                                <span class="text-muted">Partial</span>
                                <div class="fw-semibold">
                                    @if($custom->partial_amount)
                                        ₱{{ number_format($custom->partial_amount, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- PAYMENT STATUS --}}
                        <div class="mb-3">
                            @if($custom->payment_type === 'full')
                                <span class="text-success fw-semibold">Paid in Full</span>
                            @elseif($custom->payment_type === 'partial')
                                <span class="text-info fw-semibold">Partially Paid</span>
                            @else
                                <span class="text-danger fw-semibold">Not Paid</span>
                            @endif
                        </div>

                        {{-- ACTION --}}
                        @if($custom->approved && $custom->payment_type === null)
                            <button class="btn btn-success btn-sm w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentModal{{ $custom->id }}">
                                Pay Now
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PAYMENT MODAL -->
            <div class="modal fade" id="paymentModal{{ $custom->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Choose Payment Option</h5>
                        </div>

                        <div class="modal-body">
                            <p>Total Amount</p>
                            <h4 class="fw-bold mb-3">
                                ₱{{ number_format($custom->total_price, 2) }}
                            </h4>

                            {{-- PARTIAL --}}
                            <form action="{{ route('payment.pay') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customized_id" value="{{ $custom->id }}">
                                <input type="hidden" name="payment_type" value="partial">

                                <button class="btn btn-warning w-100 mb-2">
                                    Pay 50% — ₱{{ number_format($custom->total_price / 2, 2) }}
                                </button>
                            </form>

                            {{-- FULL --}}
                            <form action="{{ route('payment.pay') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customized_id" value="{{ $custom->id }}">
                                <input type="hidden" name="payment_type" value="full">

                                <button class="btn btn-success w-100">
                                    Pay Full — ₱{{ number_format($custom->total_price, 2) }}
                                </button>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- PAYMENT MODAL -->
        <div class="modal fade" id="paymentModal{{ $custom->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Choose Payment Option</h5>
                        <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <p class="mb-1">Total Amount</p>
                        <h4 class="fw-bold mb-3">
                            ₱ {{ number_format($custom->total_price, 2) }}
                        </h4>

                        {{-- PARTIAL PAYMENT --}}
                        <form action="{{ route('payment.order') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customized_id" value="{{ $custom->id }}">
                            <input type="hidden" name="payment_type" value="partial">

                            <button class="btn btn-warning w-100 mb-2">
                                Pay 50% — ₱ {{ number_format($custom->total_price / 2, 2) }}
                            </button>
                        </form>

                        {{-- FULL PAYMENT --}}
                        <form action="{{ route('payment.pay') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customized_id" value="{{ $custom->id }}">
                            <input type="hidden" name="payment_type" value="full">

                            <button class="btn btn-success w-100">
                                Pay Full — ₱ {{ number_format($custom->total_price, 2) }}
                            </button>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- END PAYMENT MODAL -->

        @empty
            <div class="col-12 text-center text-muted">
                No customizations found.
            </div>
        @endforelse
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>


@endsection
