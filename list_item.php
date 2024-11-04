<?php
session_start();
include 'C:\xampp\htdocs\Second attempt\db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $item_name = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $condition = isset($_POST['condition']) ? trim($_POST['condition']) : '';
    $information = isset($_POST['description']) ? trim($_POST['description']) : '';
    $offer_duration = isset($_POST['offer']) ? trim($_POST['offer']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $drop_off = isset($_POST['drop-off']) ? 1 : 0;
    $post_courier = isset($_POST['post-courier']) ? 1 : 0;
    $collect = isset($_POST['collect']) ? 1 : 0;

    if (empty($item_name) || $quantity <= 0) {
        echo "<script>alert('Invalid item name or quantity. Please try again.');</script>";
        exit;
    }

    

    $donor_id = $_SESSION['user_id'] ?? null;
    if (empty($donor_id)) {
        echo "<script>alert('No donor ID found in session. Please log in.');</script>";
        exit;
    }

    // Handle file upload if a file was submitted
    $target_file = ''; // Default empty string in case no image is uploaded
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = __DIR__ . "/uploads/";  // Corrected path for uploads

        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                echo "<script>alert('Failed to create directory for uploads. Check permissions.');</script>";
                exit;
            }
        }

        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        
        if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            exit;
        }

        $target_file = "uploads/" . basename($_FILES["logo"]["name"]);
    }

    // Prepare the SQL statement with placeholders for each value
$query = "INSERT INTO donations 
(donor_id, item_name, quantity, category, item_condition, description, image_url, location, offer_duration, drop_off, post_courier, collect, date_donated)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($query);

// Bind the parameters
// Ensure you have 12 parameters to match the 12 placeholders
$stmt->bind_param("isissssssiii", $donor_id, $item_name, $quantity, $category, $condition, $information, $target_file, $location, $offer_duration, $drop_off, $post_courier, $collect);

// Execute the statement
if ($stmt->execute()) {
echo "<script>alert('Donation made successfully!');</script>";
} else {
echo "<script>alert('Error making donation: " . $stmt->error . "');</script>";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List an Item for Donation</title>
    <style>
        body, html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f7f8;
        }

        .container {
            width: 50%;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #4a4a4a;
        }

        input, textarea, select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #0288d1;
        }

        button[type="submit"] {
            margin-top: 20px;
            padding: 12px 25px;
            background: #0288d1;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s ease;
            width: 100%;
        }

        button[type="submit"]:hover {
            background: #0277bd;
        }

        .checkbox-option {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .checkbox-option input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }

        .checkbox-option label {
            font-size: 16px;
            color: #333;
        }

        p {
            font-size: 14px;
            color: #666;
            margin-left: 28px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>List an Item for Donation</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" required>

            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" min="1" required>

            <label for="category">Choose category</label>
            <select name="category" required>
                <option value=""></option>
                <option value="Baby and child - clothing and shoes">Baby and child - clothing and shoes</option>
                <option value="Books and stationery">Books and stationery</option>
                <option value="Disability aid">Disability aid</option>
                <option value="Teen and adult - clothing and shoes">Teen and adult - clothing and shoes</option>
                <option value="Toys and games">Toys and games</option>
            </select>

            <label for="condition">Condition</label>
            <select name="condition" required>
                <option value=""></option>
                <option value="New">New</option>
                <option value="Gently used">Gently used</option>
                <option value="Older but excellent">Older but excellent</option>
            </select>

            <label for="information">Any extra information</label>
            <textarea name="information" cols="50" rows="6"></textarea>

            <label for="offer">List your offer for</label>
            <select name="offer" required>
                <option value=""></option>
                <option value="Two weeks">Two weeks</option>
                <option value="Three weeks">Three weeks</option>
                <option value="One month">One month</option>
                <option value="Two months">Two months</option>
                <option value="Three months">Three months</option>
            </select>
            <p>We ask you to list your offer for at least two weeks to give organizations time to see your offer.</p>

            <label for="logo">Upload photo of your item</label>
            <input type="file" id="logo" name="logo" accept="image/*">

            <h2>How can it be delivered?</h2>

            <div class="checkbox-option">
                <input type="checkbox" name="drop-off" id="drop-off">
                <label for="drop-off">Drop off</label>
            </div>
            <p>I can drop off to an organization in my area</p>

            <div class="checkbox-option">
                <input type="checkbox" name="post-courier" id="post-courier">
                <label for="post-courier">Post / Courier</label>
            </div>
            <p>I can send South Africa-wide</p>

            <div class="checkbox-option">
                <input type="checkbox" name="collect" id="collect">
                <label for="collect">Collect</label>
            </div>
            <p>I need an organization to collect my donation</p>

            <button type="submit">Donate Item</button>
        </form>
        <a href="donor_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
