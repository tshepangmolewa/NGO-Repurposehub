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
$pdf->Cell(0, 10, 'NGOs List', 0, 1, 'C');

// Fetch users from the database
$result = $conn->query("SELECT * FROM ngos");

// Set font for the table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'NGO ID', 1);
$pdf->Cell(50, 10, 'Organization Name', 1);
$pdf->Cell(50, 10, 'ZA Number', 1);
$pdf->Cell(60, 10, 'Email', 1);
$pdf->Cell(90, 10, 'Address', 1);
$pdf->Ln();

// Set font for the data
$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $row['ngo_id'], 1);
    $pdf->Cell(50, 10, $row['org_name'], 1);
    $pdf->Cell(50, 10, $row['za_number'], 1);
    $pdf->Cell(60, 10, $row['email'], 1);
    $pdf->Cell(90, 10, $row['address'], 1); 
    $pdf->Ln();
}

// Close the database connection
$conn->close();

// Output the PDF to the browser
$pdf->Output('D', 'NGOs_list.pdf');
?>