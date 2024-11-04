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
$pdf->Cell(0, 10, 'Donations List', 0, 1, 'C');

// Fetch users from the database
$result = $conn->query("SELECT * FROM donations");

// Set font for the table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'Donation ID', 1);
$pdf->Cell(50, 10, 'Item Name', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(50, 10, 'Donation_date', 1);
$pdf->Cell(50, 10, 'Item Condition', 1);
$pdf->Cell(50, 10, 'Offer Duration', 1);
$pdf->Ln();

// Set font for the data
$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $row['donation_id'], 1);
    $pdf->Cell(50, 10, $row['item_name'], 1);
    $pdf->Cell(30, 10, $row['quantity'], 1);
    $pdf->Cell(50, 10, $row['donation_date'], 1);
    $pdf->Cell(50, 10, $row['item_condition'], 1); 
    $pdf->Cell(50, 10, $row['offer_duration'], 1);
    $pdf->Ln();
}

// Close the database connection
$conn->close();

// Output the PDF to the browser
$pdf->Output('D', 'Donations_list.pdf');
?>