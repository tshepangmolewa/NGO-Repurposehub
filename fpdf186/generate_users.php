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
$pdf->Cell(0, 10, 'Users List', 0, 1, 'C');

// Fetch users from the database
$result = $conn->query("SELECT * FROM users");

// Set font for the table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'User ID', 1);
$pdf->Cell(60, 10, 'Email', 1);
$pdf->Cell(40, 10, 'User Type', 1);
$pdf->Cell(50, 10, 'Creation Date', 1);
$pdf->Ln();

// Set font for the data
$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $row['user_id'], 1);
    $pdf->Cell(60, 10, $row['email'], 1);
    $pdf->Cell(40, 10, $row['user_type'], 1);
    $pdf->Cell(50, 10, $row['created_at'], 1); 
    $pdf->Ln();
}

// Close the database connection
$conn->close();

// Output the PDF to the browser
$pdf->Output('D', 'Users_list.pdf');
?>