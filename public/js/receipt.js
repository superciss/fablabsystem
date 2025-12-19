$(function () {
    $('button[id^="generate_receipt_"]').on('click', function() {
        const id = this.id.replace('generate_receipt_', '');
        const data = ordersData[id];

        if (!data) return;

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Load logo and draw header
        const logoImage = new Image();
        logoImage.src = '/images/cspc.png';

        logoImage.onload = () => {

            function drawHeader(pdf, logoImage) {
                const pageWidth = pdf.internal.pageSize.width;

                // CSPC Logo
                pdf.addImage(logoImage, 'PNG', 10, 9, 17, 17);

                // Main Header Text
                pdf.setFontSize(11);
                pdf.setFont("Helvetica", "normal");
                pdf.text("Republic of the Philippines", 30, 13);

                pdf.setFontSize(11);
                pdf.setFont("Helvetica", "bold");
                pdf.text("CAMARINES SUR POLYTECHNIC COLLEGES", 30, 18);

                pdf.setFontSize(11);
                pdf.setFont("Helvetica", "normal");
                pdf.text("Nabua, Camarines Sur", 30, 22);

                pdf.setFontSize(11);
                pdf.setFont("Helvetica", "bold");
                pdf.text("PRODUCTION AND ENTREPRENEURIAL DEVELOPMENT SERVICES", 30, 26);

                // Draw top line
                pdf.setDrawColor(0, 0, 255);
                pdf.setLineWidth(1);
                pdf.line(5, 31, 170, 31);

                // Optional file code
                pdf.setFontSize(10);
                pdf.setFont("Helvetica", "bold");
                pdf.text("CSPC-F-PEDS-01", pageWidth - 10, 32, { align: "right" });
            }

            // Draw the header
            drawHeader(doc, logoImage);

            const pageWidth = doc.internal.pageSize.width;

            // Receipt title
            doc.setFontSize(16);
            doc.text("DELIVERY RECEIPT", 105, 42, null, null, "center");

            // Right-aligned order details (except delivered to which is left-aligned)
            const details = [
                { label: "NO.:", value: data.number, align: "right" },
                { label: "Date:", value: data.date, align: "right" },
                { label: "DELIVERED TO:", value: data.customer, align: "left" },
            ];

            let startY = 55;
            doc.setFontSize(12);
            doc.setFont("Helvetica", "normal");

            details.forEach(d => {
                if(d.align === "right") {
                    const labelX = pageWidth - 60;
                    const valueX = pageWidth - 14;
                    doc.text(d.label, labelX, startY);
                    doc.text(d.value, valueX, startY, { align: "right" });

                    // Draw underline
                    const textWidth = doc.getTextWidth(d.value);
                    doc.setLineWidth(0.5);
                    doc.line(valueX - textWidth, startY + 1, valueX, startY + 1);
                } else {
                    const labelX = 14;
                    const valueX = 47;
                    doc.text(d.label, labelX, startY);
                    doc.text(d.value, valueX, startY);

                    // Draw underline
                    const textWidth = doc.getTextWidth(d.value);
                    doc.setLineWidth(0.5);
                    doc.line(valueX, startY + 1, valueX + textWidth, startY + 1);
                }

                startY += 7;
            });

            // Items table with full borders
            const rows = data.items.map(i => [i.name, i.qty, `${i.price}`, `${i.subtotal}`]);
            doc.autoTable({
                head: [['Product', 'Qty', 'Price', 'Subtotal']],
                body: rows,
                startY: startY + 5,
                theme: 'plain', // plain to manually draw borders
                styles: {
                    fillColor: [255, 255, 255], // white background
                    textColor: [0, 0, 0],
                    lineWidth: 0.2,
                    lineColor: [0, 0, 0],
                    cellPadding: 3
                },
                headStyles: {
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    lineWidth: 0.2,
                    lineColor: [0, 0, 0],
                    fontStyle: 'bold'
                },
                didDrawCell: function (data) {
                    const { cell } = data;
                    // Draw border for every cell
                    doc.rect(cell.x, cell.y, cell.width, cell.height);
                },
            });

            // Draw bottom blue line at the bottom of the last page
            const pageHeight = doc.internal.pageSize.height;
            const marginBottom = 10; // adjust distance from bottom
            doc.setDrawColor(0, 0, 255); // blue color
            doc.setLineWidth(1);
            doc.line(5, pageHeight - marginBottom, 205, pageHeight - marginBottom);

            // Auto-print
            doc.autoPrint();
            window.open(doc.output('bloburl'), '_blank');
        };
    });
});
