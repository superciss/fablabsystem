@extends('layouts.main')

@section('title', 'Purchases Management')

@section('content')
<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('staff.product.index') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 text-dark">Machine Product</h2>
                <p class="text-muted mb-0">Manage Machine Product</p>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <button class="btn btn-success d-flex align-items-center gap-2 shadow-sm" 
                    onclick="downloadAllMachines()">
                <i class="bi bi-download"></i> Download All
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

                                <button type="button" 
                                    class="btn btn-sm btn-outline-secondary w-100 mt-2" 
                                    onclick='downloadReceipt(@json($machine))'>
                                    <i class="bi bi-receipt"></i> Download
                                </button> 
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        @include('staff.machine.editmachine', ['machine'=>$machine])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
@include('staff.machine.addmachine')
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
      doc.line(5, 34,  170,  34);

      doc.setFontSize(10);
      doc.setFont("Helvetica", "bold");
      doc.text("CSPC-F-PEDS-01", pageWidth - 10, 35, { align: "right" });
  }

  // ✅ Single Receipt
  window.downloadReceipt = function(machine) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {
        drawHeader(doc, logoImage);

        const createdAt = new Date(machine.created_at);
        const formattedDate = createdAt.toLocaleDateString("en-PH", { timeZone: "Asia/Manila" });

        doc.autoTable({
            startY: 55,
            head: [['Machine Name', 'Brand', 'Property No.', 'Date Acquired', 'Status', 'Cost']],
            body: [[
              machine.machine_name,
              machine.brand,
              machine.property_no,
              formattedDate,
              machine.status,
              "" + parseFloat(machine.cost).toLocaleString()
            ]],
            theme: "plain",
            styles: {
              font: "Helvetica",
              fontSize: 10,
              lineColor: [0, 0, 0],
              lineWidth: 0.1
            },
            headStyles: {
              fontStyle: "normal",
              fillColor: false,
              textColor: [0, 0, 0]
            }
        });

        doc.save("receipt_" + machine.machine_name + ".pdf");
    };
  }

  // ✅ Download All
  window.downloadAllMachines = function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {
        drawHeader(doc, logoImage);

        const machines = @json($machines);

        const rows = machines.map(m => {
          const createdAt = new Date(m.created_at);
          const formattedDate = createdAt.toLocaleDateString("en-PH", { timeZone: "Asia/Manila" });
          return [
            m.machine_name,
            m.brand,
            m.property_no,
            formattedDate,
            m.status,
            "" + parseFloat(m.cost).toLocaleString()
          ];
        });

        doc.autoTable({
            startY: 55,
            head: [['Machine Name', 'Brand', 'Property No.', 'Date Acquired', 'Status', 'Cost']],
            body: rows,
            theme: "plain",
            styles: {
              font: "Helvetica",
              fontSize: 10,
              lineColor: [0, 0, 0],
              lineWidth: 0.1
            },
            headStyles: {
              fontStyle: "normal",
              fillColor: false,
              textColor: [0, 0, 0]
            }
        });

        doc.save("all_machines.pdf");
    };
  }
</script>
@endpush
