<?php
require_once('../../utils/tcpdf/tcpdf.php');

include '../../db.php';

$date = date('Y-m-d');
$sql = "SELECT * FROM attendance WHERE date = '$date'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("No attendance data found for today.");
}

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TRCAC');
$pdf->SetTitle('Attendance Report - ' . $date);
$pdf->SetSubject('Daily Attendance');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

$html = '
<h1 style="text-align:center;">TRCAC Attendance Report</h1>
<p style="text-align:center;"><strong>Date:</strong> ' . $date . '</p>
<table border="1" cellpadding="6" cellspacing="0" style="width:100%;">
    <thead>
        <tr style="background-color:#1e90ff;color:white;">
            <th>Roll No</th>
            <th>Student Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '
        <tr>
            <td>' . $row['roll_number'] . '</td>
            <td>' . $row['student_name'] . '</td>
            <td style="color:' . ($row['status'] == 'Present' ? 'green' : 'red') . ';">' . $row['status'] . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>
<br/>
<p><strong>Generated by:</strong> TRCAC Admin Panel</p>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('attendance_report_' . $date . '.pdf', 'D');
exit;