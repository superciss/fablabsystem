@extends('layouts.maincustomer')

@section('title', 'My Customized Products')

@section('content')
<style>
body {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, rgba(18, 50, 80, 1), rgba(26, 62, 99, 1));
    overflow-x: hidden;
}
body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: url('/images/logo.png') center/cover no-repeat;
    opacity: .25;
    filter: blur(1px);
    z-index: -1;
}
.order-card {
    border-radius: 16px;
    background: #fff;
    transition: .25s;
}
.order-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,.15);
}
.order-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
}
.label { font-size: .8rem; color: #6c757d; }
.value { font-weight: 600; font-size: .9rem; }
.price { font-size: 1.05rem; font-weight: 700; color: #0d6efd; }
</style>

<div class="container mt-4">
    <h4 class="fw-bold mb-4">My Customized Orders</h4>

    <div class="row g-4">
        @forelse($customizedProducts as $custom)
        <div class="col-lg-4 col-md-6">
            <div class="card order-card shadow-sm">

                <div class="p-3">
                    <img src="{{ $custom->front_image ?? '/images/no-image.png' }}" class="order-img">
                </div>

                <div class="card-body pt-0">
                    <h6 class="fw-bold mb-2">
                        {{ $custom->product->name ?? 'N/A' }}
                    </h6>

                    <div class="row small">
                        <div class="col-6 mb-2">
                            <div class="label">User</div>
                            <div class="value">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="label">Qty</div>
                            <div class="value">{{ $custom->quantity }}</div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="label">Status</div>
                            <div class="value">{{ $custom->customized_status ?? 'Pending' }}</div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="label">Payment</div>
                            <div class="value">{{ $custom->payment_type ?? 'N/A' }}</div>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="label">Estimate Date</div>
                            <div class="value">
                                {{ $custom->estimate_date_custom ? \Carbon\Carbon::parse($custom->estimate_date_custom)->format('M d, Y') : 'No Estimate Date' }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="label">Total</div>
                            <div class="price">
                                PHP {{ number_format($custom->total_price, 2) }}
                            </div>
                        </div>

                        @if($custom->partial_amount)
                        <div class="text-end">
                            <div class="label">Partial Paid</div>
                            <div class="fw-semibold">
                                PHP {{ number_format($custom->partial_amount, 2) }}
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- DOWNLOAD RECEIPT --}}
                    @if($custom->payment_type)
                    <button class="btn btn-outline-primary btn-sm w-100 mt-3"
                        onclick="generateReceipt({{ $custom->id }})">
                        Download Receipt (PDF)
                    </button>
                    @endif
                </div>
            </div>

            {{-- 🔐 HIDDEN RECEIPT DATA (RAW NUMBERS ONLY) --}}
            <div id="receipt-data-{{ $custom->id }}"
                 data-id="{{ $custom->id }}"
                 data-user="{{ Auth::user()->name }}"
                data-date="  {{ \Carbon\Carbon::parse($custom->estimate_date_custom)->timezone('Asia/Manila')->format('M d, Y') }}"
                 data-product="{{ $custom->product->name ?? 'N/A' }}"
                 data-qty="{{ $custom->quantity }}"
                 data-payment="{{ $custom->payment_type ?? 'N/A' }}"
                 data-status="{{ $custom->approved ? 'Approved' : 'Pending' }}"
                 data-total="{{ $custom->total_price }}"
                 data-partial="{{ $custom->partial_amount ?? 0 }}">
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">
            No customized products found.
        </div>
        @endforelse
    </div>
</div>

{{-- jsPDF --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
function peso(n) {
    return Number(n).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function generateReceipt(id) {
    const el = document.getElementById('receipt-data-' + id);
    if (!el) return alert('Receipt data missing');

    const d = el.dataset;
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {

        const pageWidth = doc.internal.pageSize.width;
        const leftX = 20;
        const rightX = pageWidth / 2 + 5;

        // =========================
        // HEADER
        // =========================
        doc.addImage(logoImage, 'PNG', 10, 9, 17, 17);

        doc.setFontSize(11);
        doc.setFont("Helvetica", "normal");
        doc.text("Republic of the Philippines", 30, 13);

        doc.setFont("Helvetica", "bold");
        doc.text("CAMARINES SUR POLYTECHNIC COLLEGES", 30, 18);

        doc.setFont("Helvetica", "normal");
        doc.text("Nabua, Camarines Sur", 30, 22);

        doc.setFont("Helvetica", "bold");
        doc.text("PRODUCTION AND AUXILIARY SERVICES OFFICE", 30, 26);

        doc.setDrawColor(0, 0, 255);
        doc.line(5, 34, pageWidth - 5, 34);

        // =========================
        // TITLE
        // =========================
        doc.setFontSize(14);
        doc.setFont("Helvetica", "bold");
        doc.text("TRANSACTION SLIP", pageWidth / 2, 45, { align: "center" });

        // =========================
        // CONTENT (2 COLUMNS)
        // =========================
        let y = 58;
        doc.setFontSize(11);
        doc.setFont("Helvetica", "normal");

        // LEFT COLUMN
        doc.text(`Receipt No: ${d.id}`, leftX, y);
       // doc.text(`Date: ${d.date}`, rightX, y);
        y += 8;

        doc.text(`Customer Name: ${d.user}`, leftX, y);
        doc.text(`Estimated Date: ${d.date}`, rightX, y);
        y += 8;

        doc.text(`Product: ${d.product}`, leftX, y);
        doc.text(`Payment Type: ${d.payment}`, rightX, y);
        y += 8;

        doc.text(`Quantity: ${d.qty}`, leftX, y);
        doc.text(`Status: ${d.status}`, rightX, y);
        y += 12;

        // =========================
        // AMOUNTS
        // =========================
        doc.setFont("Helvetica", "bold");
        doc.text(`Total Amount: PHP ${peso(d.total)}`, leftX, y);
        y += 8;

        if (Number(d.partial) > 0) {
            doc.setFont("Helvetica", "normal");
            doc.text(`Partial Paid: PHP ${peso(d.partial)}`, leftX, y);
            y += 8;
        }

        // =========================
        // FOOTER
        // =========================
        y += 15;
        doc.setFontSize(10);
        doc.text(
            "This document serves as an official transaction slip.",
            pageWidth / 2,
            y,
            { align: "center" }
        );

        y += 6;
        doc.text(
            "Thank you for your transaction!",
            pageWidth / 2,
            y,
            { align: "center" }
        );

        doc.save(`transaction-slip-${d.id}.pdf`);
    };
}
</script>


@endsection
