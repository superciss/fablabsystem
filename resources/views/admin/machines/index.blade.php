@extends('layouts.main')

@section('title', 'Purchases Management')

@section('content')
<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 text-dark">Machine Product</h2>
                <p class="text-muted mb-0">Manage Machine Product</p>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <button class="btn btn-success d-flex align-items-center gap-2 shadow-sm" 
                    onclick="downloadAllData()">
                <i class="bi bi-download"></i> Download
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addmachineModal">
                <i class="bi bi-plus-circle"></i> Add Machine Product
            </button> 
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Machine Product</h5>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="machineTable">
                    <thead class="table-light">
                        <tr>
                            <th>Machine Name</th>
                            <th>Brand</th>
                            <th>Property No.</th>
                            <th>Date Acquired</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($machines as $machine)
                        <tr>
                            <td>{{ $machine->machine_name }}</td>
                            <td>{{ $machine->brand }}</td>
                            <td>{{ $machine->property_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($machine->created_at)->format('M d, Y') }}</td>
                            <td>{{ $machine->status }}</td>
                            <td class="text-success fw-bold">₱{{ number_format($machine->cost, 2) }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editmachineModal{{ $machine->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form id="delete-form-{{ $machine->id }}" action="{{ route('machines.destroy', $machine->id) }}" method="POST" class="d-inline-block">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-xs d-flex align-items-center gap-1 py-1" 
                                            onclick="confirmDelete('delete-form-{{ $machine->id }}')">
                                        <i class="bi bi-trash"></i> 
                                    </button>
                                </form> 

                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        @include('admin.machines.editmachine', ['machine'=>$machine])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
@include('admin.machines.addmachine')
@endsection

@push('scripts')
{{-- ✅ jsPDF & AutoTable --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>

<script>
  // ✅ Common header drawer
  function drawHeader(doc, logoImage) {
    const pageWidth = doc.internal.pageSize.width;

    doc.addImage(logoImage, 'PNG', 10, 9, 17, 17);

    doc.setFontSize(11);
    doc.setFont("Helvetica", "normal");
    doc.text("Republic of the Philippines", 30, 13);

    doc.setFontSize(11);
    doc.setFont("Helvetica", "bold");
    doc.text("CAMARINES SUR POLYTECHNIC COLLEGES", 30, 18);

    doc.setFontSize(11);
    doc.setFont("Helvetica", "normal");
    doc.text("Nabua, Camarines Sur", 30, 22);

    doc.setFontSize(11);
    doc.setFont("Helvetica", "bold");
    doc.text("PRODUCTION AND ENTREPRENEURIAL DEVELOPMENT SERVICES", 30, 26);

    doc.setDrawColor(0, 0, 255);
    doc.setLineWidth(1);
    doc.line(5, 34, 170, 34);

    doc.setFontSize(10);
    doc.setFont("Helvetica", "bold");
    doc.text("CSPC-F-PEDS-01", pageWidth - 10, 35, { align: "right" });
  }

  // ✅ Helper to print section titles
  function printSectionTitle(doc, title, subtitle, date, gapY) {
    doc.setFontSize(12);
    doc.setFont("Helvetica", "bold");
    doc.text(title, doc.internal.pageSize.width / 2, gapY, { align: "center" });

    if (subtitle) {
      doc.setFontSize(12);
      doc.text(subtitle, doc.internal.pageSize.width / 2, gapY + 6, { align: "center" });
    }

    if (date) {
      doc.setFontSize(11);
      doc.setFont("Helvetica", "normal");
      doc.text("As of " + date, doc.internal.pageSize.width / 2, gapY + 12, { align: "center" });
    }
  }

  // ✅ Combined Download with headers on every page
  window.downloadAllData = function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {
      const products = @json($products);

      // ✅ Group products by category
      const groupedProducts = {};
      products.forEach(p => {
        const cat = p.pro_category || "Uncategorized";
        if (!groupedProducts[cat]) groupedProducts[cat] = [];
        const consumed = p.consumed_units ?? 0;
        const stock = p.stock ?? 0;
        const available = stock - consumed;
        groupedProducts[cat].push([
          p.name,
          p.unit,
          stock,
          0,
          0,
          consumed,
          available,
          p.created_at // store date for later sorting
        ]);
      });

      let currentY = 45;

      // ✅ Loop through each category, create a section and table
      Object.keys(groupedProducts).forEach(category => {
        const items = groupedProducts[category];

        // ✅ Get latest created_at per category
        let latestCreatedAt = "";
        if (items.length > 0) {
          const sorted = [...items].sort((a, b) => new Date(b[7]) - new Date(a[7]));
          const latestDate = new Date(sorted[0][7]);
          latestCreatedAt = latestDate.toLocaleDateString("en-PH", {
            year: "numeric",
            month: "long",
            day: "numeric",
            timeZone: "Asia/Manila"
          });
        }

        // ✅ Title block
        printSectionTitle(
          doc,
          "INVENTORY OF MATERIALS",
          category,
          latestCreatedAt,
          currentY
        );

        // ✅ Remove created_at column before rendering
        const bodyRows = items.map(i => i.slice(0, 7));

        // ✅ Render table for this category
        doc.autoTable({
          startY: currentY + 20,
          head: [[
            'Item',
            'Unit',
            'No. of Units on Display',
            'No. of Sponsored Units',
            'No. of Damaged Units',
            'No. of Units Consumed',
            'Available Units for Production'
          ]],
          body: bodyRows,
          theme: "plain",
          styles: { font: "Helvetica", fontSize: 10, lineColor: [0,0,0], lineWidth: 0.1 },
          headStyles: { fontStyle: "normal", fillColor: false, textColor: [0,0,0] },
          margin: { top: 55 },
          didDrawPage: function () {
            drawHeader(doc, logoImage);
          }
        });

        currentY = doc.lastAutoTable.finalY + 20; // update Y for next category
      });

      // --- Machines ---
      const machines = @json($machines);
      const machineRows = machines.map(m => {
        const createdAt = new Date(m.created_at);
        const formattedDate = createdAt.toLocaleDateString("en-PH", { timeZone: "Asia/Manila" });
        return [
          m.machine_name,
          m.brand,
          m.property_no,
          formattedDate,
          m.status,
          "" + parseFloat(m.cost).toLocaleString(),
          m.created_at
        ];
      });

      // ✅ Get latest created_at from machines
      let latestCreatedAt = "";
      if (machines.length > 0) {
        const sorted = [...machines].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        const latestDate = new Date(sorted[0].created_at);
        latestCreatedAt = latestDate.toLocaleDateString("en-PH", {
          year: "numeric",
          month: "long",
          day: "numeric",
          timeZone: "Asia/Manila"
        });
      }

      // ✅ Title block before Machines
      if (machines.length > 0) {
        printSectionTitle(
          doc,
          "INVENTORY OF MACHINERY AND EQUIPMENT",
          null,
          latestCreatedAt,
          currentY
        );
      }

      // ✅ Machines Table
      const machineBody = machineRows.map(r => r.slice(0, 6));

      doc.autoTable({
        startY: currentY + 20,
        head: [['Machine Name', 'Brand', 'Property No.', 'Date Acquired', 'Status', 'Cost']],
        body: machineBody,
        theme: "plain",
        styles: { font: "Helvetica", fontSize: 10, lineColor: [0,0,0], lineWidth: 0.1 },
        headStyles: { fontStyle: "normal", fillColor: false, textColor: [0,0,0] },
        margin: { top: 55 },
        didDrawPage: function () {
          drawHeader(doc, logoImage);
        }
      });

      // ✅ Save combined file
      doc.save("all_data.pdf");
    };
  }
</script>
@endpush
