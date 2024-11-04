<?php
// Include the database connection
include 'C:\xampp\htdocs\Second attempt\db_connection.php';

// Handle the claim action if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON payload
    $data = json_decode(file_get_contents("php://input"), true);
    $itemName = $data['item_name'] ?? '';

    // Handle the claim action
    if ($itemName) {
        $stmt = $conn->prepare("UPDATE donations SET claimed = 1 WHERE item_name = ?");
        $stmt->bind_param("s", $itemName);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $donorStmt = $conn->prepare("SELECT donor_id FROM donations WHERE item_name = ?");
            $donorStmt->bind_param("s", $itemName);
            $donorStmt->execute();
            $donorStmt->bind_result($donorId);
            $donorStmt->fetch();
            $donorStmt->close();

            // Insert a notification for the donor
            $message = "Your item '$itemName' has been claimed.";
            $notificationStmt = $conn->prepare("INSERT INTO notifications (donor_id, item_name, message) VALUES (?, ?, ?)");
            $notificationStmt->bind_param("iss", $donorId, $itemName, $message);
            $notificationStmt->execute();
            $notificationStmt->close();


            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();

    $conn->close();
    exit;  // Stop further PHP execution
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        /* Your existing CSS styles here */
        /* Minimal CSS for layout */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            height: 100vh;
            padding: 20px;
        }
        .sidebar h2, .sidebar ul, .sidebar li {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .profile-image img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 16px;
        }
        .sidebar ul li a:hover {
            text-decoration: underline;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .dashboard-sections {
            display: flex;
            gap: 20px;
        }
        .section {
            flex: 1;
            background: #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .section h3 {
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .item-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .item-card {
            width: 200px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        
        .item-card img {
            width: 100%;
            height: 150px;
            border-radius: 5px;
            object-fit: cover;
        }
        .item-card h4 {
            margin: 10px 0;
        }
        .item-card p {
            font-size: 14px;
            color: #333;
        }
        
        /* Search Bar Styles */
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 20px; /* Add top margin for spacing */
        }
        .search-container h2 {
            margin: 0;
        }
        .search-bar {
            padding: 12px 20px;
            width: 350px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .search-bar:focus {
            outline: none;
            border: 1px solid #3498db;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .search-bar::placeholder {
            color: #999;
            font-style: italic;
        }

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Welcome NGO</h2>
        <div class="profile-image">
            <img src="ngo_logo.png" alt="NGO Logo">
        </div>
        <ul>
            <li><a href="#">Dashboard Home</a></li>
            <li><a href="ngo_dashboard.php">View Available Items</a></li>
            <li><a href="ngoRequest.php">Request Items</a></li>
            <!-- <li><a href="#">Claimed Items</a></li> -->
            <li><a href="update_profile.php">Profile Settings</a></li>
            <li><a href="homepage.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <div class="search-container">
            <h2>Available Donations</h2>
            <input type="text" class="search-bar" id="search-bar" placeholder="Search for items by name or category..." onkeyup="searchItems()">
        </div>

        <div class="item-list" id="item-list">
            <?php
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch available items and their claim status
            $sql = "SELECT item_name, category, location, image_url, claimed FROM donations"; // Adjust the query as needed
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $claimed = $row['claimed'] ? "true" : "false";
                    echo '<div class="item-card" data-name="' . htmlspecialchars($row['item_name']) . '" data-category="' . htmlspecialchars($row['category']) . '">';
                    echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['item_name']) . '">';
                    echo '<h4>' . htmlspecialchars($row['item_name']) . '</h4>';
                    echo '<p>Category: ' . htmlspecialchars($row['category']) . '</p>';
                    echo '<p>Location: ' . htmlspecialchars($row['location']) . '</p>';
                    echo '<button class="btn" onclick="claimItem(this, \'' . htmlspecialchars($row['item_name']) . '\')" ' . ($claimed == "true" ? 'disabled style="background-color: #7f8c8d;"' : '') . '>' . ($claimed == "true" ? 'Claimed' : 'Claim Item') . '</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No available items found.</p>';
            }
            
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function claimItem(button, itemName) {
            button.innerText = "Claimed";
            button.style.backgroundColor = "#7f8c8d";
            button.disabled = true;

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ item_name: itemName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Item successfully claimed.");
                } else {
                    console.error("Error claiming item.");
                    alert("Error claiming the item.");
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function searchItems() {
            const query = document.getElementById('search-bar').value.toLowerCase();
            const items = document.querySelectorAll('.item-card');
            items.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                const category = item.getAttribute('data-category').toLowerCase();
                item.style.display = name.includes(query) || category.includes(query) ? "block" : "none";
            });
        }
    </script>
</body>
</html>





