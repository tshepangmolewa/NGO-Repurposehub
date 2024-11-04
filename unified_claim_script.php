<?php
header('Content-Type: application/json');
include 'C:\xampp\htdocs\Second attempt\db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$item_name = $input['item_name'];

// Get donor email associated with the item
$sql = "SELECT u.email FROM users u JOIN donations d ON u.user_id = d.user_id WHERE d.item_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $item_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $donor_email = $row['email'];
    
    // Update the item status to "claimed" in donations table
    $update_sql = "UPDATE donations SET status = 'claimed' WHERE item_name = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("s", $item_name);
    if ($update_stmt->execute()) {
        // Send an email to the donor
        $subject = "Your item has been claimed";
        $message = "Hello,\n\nYour donated item, '$item_name', has been claimed by an NGO.\n\nThank you for your contribution!";
        $headers = "From: noreply@yourdomain.com";

        if (mail($donor_email, $subject, $message, $headers)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Email failed to send.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update donation status.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Item not found or donor email unavailable.']);
}

$conn->close();
?>
