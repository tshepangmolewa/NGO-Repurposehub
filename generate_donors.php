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
$pdf->Cell(0, 10, 'Donors List', 0, 1, 'C');

// Fetch users from the database
$result = $conn->query("SELECT * FROM donors");

// Set font for the table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'Donor ID', 1);
$pdf->Cell(50, 10, 'First Name', 1);
$pdf->Cell(50, 10, 'Last Name', 1);
$pdf->Cell(30, 10, 'Contact', 1);
$pdf->Cell(100, 10, 'Address', 1);
$pdf->Ln();

// Set font for the data
$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $row['donor_id'], 1);
    $pdf->Cell(50, 10, $row['first_name'], 1);
    $pdf->Cell(50, 10, $row['last_name'], 1);
    $pdf->Cell(30, 10, $row['contact_number'], 1);
    $pdf->Cell(100, 10, $row['address'], 1); 
    $pdf->Ln();
}

// Close the database connection
$conn->close();

// Output the PDF to the browser
$pdf->Output('D', 'Donors_list.pdf');
?>