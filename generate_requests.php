<?php
require('C:\xampp\htdocs\Second attempt\fpdf186\fpdf.php');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "repurposehub");

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a new PDF document
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Requests List', 0, 1, 'C');

// Fetch users from the database
$result = $conn->query("SELECT * FROM requests");

// Set font for the table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'Request ID', 1);
$pdf->Cell(50, 10, 'Item Name', 1);
$pdf->Cell(40, 10, 'Request Quantity', 1);
$pdf->Cell(50, 10, 'Request Date', 1);
$pdf->Cell(30, 10, 'Status', 1);
$pdf->Ln();

// Set font for the data
$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $row['request_id'], 1);
    $pdf->Cell(50, 10, $row['item_name'], 1);
    $pdf->Cell(40, 10, $row['requested_quantity'], 1);
    $pdf->Cell(50, 10, $row['request_date'], 1); 
    $pdf->Cell(30, 10, $row['status'], 1);
    $pdf->Ln();
}

// Close the database connection
$conn->close();

// Output the PDF to the browser
$pdf->Output('D', 'Requests_list.pdf');
?>