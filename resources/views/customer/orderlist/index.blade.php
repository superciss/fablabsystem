@extends('layouts.maincustomer')

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

    h2 {
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .product-img {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 10px;
        border: 1px solid #ddd;
        background: #fff;
    }
</style>

<div class="container mt-4">
    <h2 class="fw-bold mb-4">My Orders</h2>

   
    @if($orders->isEmpty())
        <div class="alert alert-info">You have no orders yet.</div>
    @else
        <div class="row g-4">
            @foreach($orders as $order)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 order-card"
                        @if($order->status !== 'cancelled')
                            data-bs-toggle="modal"
                            data-bs-target="#viewFullModal{{ $order->id }}"
                            style="cursor: pointer;"
                        @else
                            style="cursor: default;"
                        @endif>

                        <div class="card-body d-flex flex-column">
                        
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Order #{{ $order->order_number }}</h6>
                                
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge bg-info text-dark mb-1">{{ ucfirst($order->status) }}</span>
                                    @if($order->paid)
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-danger">Unpaid</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Details -->
                            <p class="small text-muted mb-1"><i class="bi bi-truck"></i> Delivery: 
                                <span class="fw-semibold">{{ ucfirst($order->delivery_type) }}</span>
                            </p>

                            <p class="small text-muted mb-1"><i class="bi bi-cash-coin"></i> Type: 
                                <span class="fw-semibold">{{ ucfirst($order->type_request ?? 'N/A') }}</span>
                            </p>

                            <p class="small text-muted mb-1"><i class="bi bi-calendar2-week"></i> Estimate Date: 
                                <span class="fw-semibold">
                                    {{ $order->estimate_date 
                                        ? \Carbon\Carbon::parse($order->estimate_date)->format('M d, Y') 
                                        : 'No Estimate Date' }}
                                </span>
                            </p>

                            <p class="small text-muted mb-1">
                                <i class="bi bi-calendar-event"></i> {{ $order->created_at->format('M d, Y h:i A') }}
                            </p>

                            <h6 class="text-danger fw-bold mt-2 mb-3">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </h6>

                            <!-- Items -->
                            <div class="order-items small flex-grow-1">
                                <p class="fw-semibold mb-1">Items:</p>
                                <ul class="list-unstyled mb-2">
                                    @foreach($order->orderItem as $item)
                                        <li class="d-flex align-items-center mb-2">
                                            <img src="{{ $item->product->image ?? '/images/no-image.png' }}" 
                                                 class="product-img" 
                                                 alt="{{ $item->product->name ?? 'No image' }}">
                                            <div>
                                                <span class="fw-semibold">{{ $item->product->name ?? 'Unknown Product' }}</span><br>
                                                <span class="text-muted">x{{ $item->quantity }} - ₱{{ number_format($item->price, 2) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto">
                                @if($order->status === 'pending')
                                    <form action="{{ route('customerorder.cancel', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger w-100 mb-2">
                                            <i class="bi bi-x-circle"></i> Cancel Order
                                        </button>
                                    </form>
                                @endif

                                @if($order->status === 'cancelled')
                                    <span class="text-muted small">No actions available</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ===========================
                    MODAL INCLUDED HERE
                ============================ --}}
                <div class="modal fade" id="viewFullModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white rounded-top-4">
                                <h5 class="modal-title fw-bold">Order #{{ $order->order_number }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body bg-light">
                                <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
                                <p><strong>Type Request:</strong> {{ ucfirst($order->type_request ?? 'N/A') }}</p>
                                <p><strong>Delivery:</strong> {{ ucfirst($order->delivery_type) }}</p>
                                <p><strong>Estimate:</strong> 
                                    {{ $order->estimate_date ? \Carbon\Carbon::parse($order->estimate_date)->format('M d, Y') : 'No Estimate Date' }}
                                </p>

                                <hr>

                                <h6 class="fw-bold">Ordered Items</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                      <thead class="table-primary">
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($order->orderItem as $item)
                                        <tr>
                                            <td><img src="{{ $item->product->image ?? '/images/no-image.png' }}" width="60" height="60" style="object-fit:cover;border-radius:8px"></td>
                                            <td>{{ $item->product->name }}</td>
                                            <td>x{{ $item->quantity }}</td>
                                            <td>₱{{ number_format($item->price, 2) }}</td>
                                            <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>

                                <hr>
                                <h5 class="fw-bold text-end">Total: ₱{{ number_format($order->total_amount, 2) }}</h5>
                            </div>

                            <div class="modal-footer">

                                {{-- Receipt Buttons --}}
                                @if($order->paid)
                                    <button class="btn btn-info" onclick='viewReceipt(@json($order), "official")'>
                                        View Official Receipt
                                    </button>
                                    <button class="btn btn-primary" onclick='downloadReceipt(@json($order), "official")'>
                                        Download Official Receipt
                                    </button>
                                @else
                                    <button class="btn btn-warning" onclick='viewReceipt(@json($order), "purchase")'>
                                        View Purchase Receipt
                                    </button>
                                    <button class="btn btn-secondary" onclick='downloadReceipt(@json($order), "purchase")'>
                                        Download Purchase Receipt
                                    </button>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                {{-- ===========================
                    END MODAL
                ============================ --}}

            @endforeach
        </div>
    @endif
</div>

{{-- ✅ jsPDF Script --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function generateReceipt(order, type, action = "download") {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const createdAt = new Date(order.created_at);
    const formattedDate = createdAt.toLocaleString("en-PH", {
        timeZone: "Asia/Manila",
        year: "numeric",
        month: "short",
        day: "2-digit"
    });

    const estimateDate = order.estimate_date 
        ? new Date(order.estimate_date).toLocaleDateString("en-PH", {
            year: "numeric",
            month: "short",
            day: "2-digit"
        })
        : "No Estimate Date";

    const typeRequest = order.type_request ? order.type_request.toUpperCase() : "N/A";

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {
        const pageWidth = doc.internal.pageSize.width;

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
        doc.line(5, 34, 205, 34);

        const isPurchase = type === "purchase";
         const receiptTitle = isPurchase ? "TRANSACTION SLIP" : "TRANSACTION SLIP";
       // const receiptTitle = isPurchase ? "PURCHASE RECEIPT" : "TRANSACTION SLIP";
        // const footerMessage = isPurchase
        //     ? "Thank you for your request!"
        //     : "Thank you for your purchase!";
        const fileNamePrefix = isPurchase ? "purchase_receipt_" : "official_receipt_";

        let y = 45;
        doc.setFontSize(14).setFont("Helvetica", "bold");
        doc.text(receiptTitle, 105, y, { align: "center" });

        y += 10;
        doc.setFontSize(12).setFont("Helvetica", "normal");
        doc.text("Product No: " + order.order_number, 20, y);
         doc.text("OR NO.______________", 150, y);
        // doc.text("Date: " + formattedDate, 150, y);

        // y += 10;
        // doc.text("OR NO.______________", 20, y);

        y += 10;
        doc.text("Customer: " + (order.user?.user_information?.fullname ?? order.user?.name ?? "N/A"), 20, y);
        y += 10;
        doc.text("Delivery Type: " + order.delivery_type, 20, y);

        // y += 1;
        // doc.text("OR No._____________", 150, y);

        y += 10;
       doc.text("Payment Status: " + (order.paid ? "PAID" : "UNPAID"), 150, y);
        y += 2;
        doc.text("Type Request: " + typeRequest, 20, y);
        y += 8;
        doc.text("Date: " + formattedDate, 20, y);
        y += 8;
        doc.text("Estimated Date: " + estimateDate, 20, y);

        y += 15;
        doc.setFont("Helvetica", "bold");
        doc.text("No.", 20, y);
        doc.text("Item Name", 35, y);
        doc.text("Qty", 130, y);
        doc.text("Price", 150, y);
        doc.text("Subtotal", 180, y);
        doc.line(20, y + 2, 200, y + 2);

        y += 8;
        doc.setFont("Helvetica", "normal");

        let total = 0;
        order.order_item.forEach((item, i) => {
            const price = Number(item.price);
            const subtotal = price * item.quantity;
            total += subtotal;
            doc.text(String(i + 1), 20, y);
            doc.text(item.product.name, 35, y);
            doc.text(String(item.quantity), 130, y);
            doc.text(price.toFixed(2), 150, y);
            doc.text(subtotal.toFixed(2), 180, y);
            y += 8;
        });

        y += 5;
        doc.line(120, y, 200, y);
        y += 8;
        doc.setFont("Helvetica", "bold");
        doc.text("Total Amount:", 130, y);
        doc.text(Number(order.total_amount).toFixed(2) + " PHP", 200, y, { align: "right" });

        y += 15;
        doc.setFont("Helvetica", "italic");
       // doc.text(footerMessage, 105, y, { align: "center" });

        // ✅ Handle View vs Download
        const pdfName = fileNamePrefix + order.order_number + ".pdf";
        if (action === "view") {
            const pdfBlob = doc.output("blob");
            const pdfUrl = URL.createObjectURL(pdfBlob);
            window.open(pdfUrl); // open in new tab
        } else {
            doc.save(pdfName);
        }
    };
}

// 🔹 Wrappers for clarity
function downloadReceipt(order, type) {
    generateReceipt(order, type, "download");
}

function viewReceipt(order, type) {
    generateReceipt(order, type, "view");
}
</script>
@endsection
