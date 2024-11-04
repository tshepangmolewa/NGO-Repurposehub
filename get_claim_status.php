<?php
header('Content-Type: application/json');
include 'C:\xampp\htdocs\Second attempt\db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$item_name = $input['item_name'];

// Retrieve claim status from donations table
$sql = "SELECT status FROM donations WHERE item_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $item_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['claimed' => $row['status'] === 'claimed']);
} else {
    echo json_encode(['claimed' => false]);
}

$conn->close();
?>
