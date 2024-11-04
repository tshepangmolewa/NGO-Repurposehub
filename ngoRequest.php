<?php
session_start();
include 'db_connection.php';

// Assuming user_id is stored in the session when the NGO logs in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "User not logged in!";
    exit();
}

// Fetch ngo_id based on user_id
$sql = "SELECT ngo_id FROM ngos WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "NGO not found!";
    exit();
}

$ngo = $result->fetch_assoc();
$ngo_id = $ngo['ngo_id'];

$stmt->close();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $item_name = $_POST['item_name'] ?? null;
    $item_description = $_POST['item_description'] ?? null;
    $requested_quantity = $_POST['requested_quantity'] ?? null;
    $area = $_POST['area'] ?? null;
    $delivery_method = $_POST['delivery_method'] ?? null;
    $status = 'Pending';

    // Debugging output
    if (is_null($item_name) || is_null($item_description) || is_null($requested_quantity) || is_null($area) || is_null($delivery_method)) {
        echo "Form fields cannot be null.<br>";
        echo "item_name: $item_name<br>";
        echo "item_description: $item_description<br>";
        echo "requested_quantity: $requested_quantity<br>";
        echo "area: $area<br>";
        echo "delivery_method: $delivery_method<br>";
        exit();
    }

    // Set recipient and reason
    $recipient = $ngo_id; // or however you determine the recipient
    $reason = $item_description; // Assuming you want to save item_description as the reason

    // Proceed with insertion
    $sql = "INSERT INTO requests (ngo_id, item_name, item_description, requested_quantity, request_date, status, area, delivery_method, recipient, reason) 
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississsss", $ngo_id, $item_name, $item_description, $requested_quantity, $status, $area, $delivery_method, $recipient, $reason);

    if ($stmt->execute()) {
        echo "Request added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No data submitted.";
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .item {
            margin-bottom: 30px;
        }

        /* Mobile Responsive */
        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            button[type="submit"] {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Submit a Request</h1>
    
    <form method="POST" action="ngoRequest.php">
        <!-- NGO ID (hidden field, ideally dynamically populated for logged-in NGOs) -->
        <input type="hidden" name="ngo_id" value="123"> <!-- Example NGO ID -->

        <!-- Items (can be extended for multiple items) -->
        <div id="items">
            <div class="item">
                <label for="item_name">Item Name:</label>
                <input type="text" name="item_name" required><br>

                <label for="requested_quantity">Requested Quantity:</label>
                <input type="number" name="requested_quantity" required><br>

                <label for="item_description">Reason for Request:</label>
                <textarea name="item_description" required></textarea><br>
            </div>
        </div>

        <!-- Additional Fields -->
        <label for="area">Delivery Area:</label>
        <input type="text" name="area" required><br>

        <label for="delivery_method">Delivery Method:</label>
        <select name="delivery_method" required>
            <option value="Pick Up">Pick Up</option>
            <option value="Delivery">Delivery</option>
        </select><br>

        <button type="submit">Submit Request</button>
    </form>
</body>
</html>
