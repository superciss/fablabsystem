document.getElementById('example').addEventListener('click', async function () {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: 'portrait' });

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {
        function drawHeader(pdf, logoImage) {
            const pageWidth = pdf.internal.pageSize.width;
            const logoWidth = 17;

            // Only CSPC Logo
            pdf.addImage(logoImage, 'PNG', 10, 9, 17, 17);

            // Main Header Text
             pdf.setFontSize(11);
            pdf.setFont("Helvetica", "normal");
            pdf.text("Rebulic of the Philippines", 30, 13);
            
            pdf.setFontSize(11);
            pdf.setFont("Helvetica", "bold");
            pdf.text("CAMARINES SUR POLYTECHNIC COLLEGES", 30, 18);

            pdf.setFontSize(11);
            pdf.setFont("Helvetica", "normal");
            pdf.text("Nabua, Camarines Sur", 30, 22);
            
             pdf.setFontSize(11);
            pdf.setFont("Helvetica", "bold");
            pdf.text("PRODUCTION AND ENTREPRENEURIAL DEVELOPMENT SERVICES", 30, 26);


            // Draw lines
            pdf.setDrawColor(0, 0, 255);
            pdf.setLineWidth(1);
            pdf.line(5, 31, 170, 31);  

            // Optional: file code
            pdf.setFontSize(10);
            pdf.setFont("Helvetica", "bold");
            pdf.text("CSPC-F-COL-37", pageWidth - 10, 32, { align: "right" });
        }

        // Draw headers on the first page
        drawHeader(pdf, logoImage);

        // Save PDF
        pdf.save('reciept.pdf');
    };
});
