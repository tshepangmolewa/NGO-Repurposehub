<?php

session_start(); // Start the session

// Check if the user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit(); // Stop executing further
}

include 'C:\xampp\htdocs\Second attempt\db_connection.php'; // Include your database connection

// Your existing code follows here...

?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 170px;
            background-color: #2c3e50;
            color: #fff;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;
            padding: 20px;
            background: #34495e;
            margin: 0;
            font-size: 22px;
        }

        .sidebar img {
            display: block;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 10px auto;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }

        .sidebar ul li {
            padding: 15px 10px;
            text-align: left;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff;
            display: block;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
        }

        .sidebar ul li a:hover {
            padding-left: 20px;
        }

        .main-content {
            margin-left: 170px;
            padding: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            background: #fff;
        }

        table th, table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px; /* Smaller font for cells */
        }

        table th {
            background: #f4f4f4;
            font-weight: bold;
            font-size: 13px; /* Small and bold font for headers */
            padding: 10px 12px; /* Reduce padding to make the header slimmer */
            text-transform: none; /* Ensure no uppercase */
        }

        table tr:hover {
            background: #f1f1f1;
        }

        table td h4 {
            margin: 0;
            font-weight: normal;
        }

        /* Refined "Give" Button Style */
        table .btn {
            display: inline-block;
            padding: 10px 16px; /* Increase padding for better appearance */
            background: linear-gradient(to right, #16a085, #1abc9c); /* Gradient effect */
            color: white;
            border: none;
            border-radius: 30px; /* Rounder button */
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* Subtle shadow for depth */
        }

        table .btn:hover {
            background: linear-gradient(to right, #138f75, #16a085);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15); /* Enhance shadow on hover */
        }

        /* Notification Styles */
        .notification-section {
            margin-top: 40px; /* Space above the notification section */
        }

        .notification {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Welcome Donor</h2>
        <ul>
            
            <li><i class='bx bx-donate-heart'></i><a href="list_item.php">Donate an Item</a></li>
            <li><a href="my_donations.php">View My Donations</a></li>
            <li><i class='bx bx-log-out'></i><a href="homepage.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Thanks for wanting to help someone in need!</h1>
        <p>All the requests listed are from organisations reviewed by RepurposeHub as genuinely working with people in need.</p>
        <p>Donating exactly what's needed is a smart way to give. It'll make you feel great and might just change a life.</p>

        <!-- Items Needed Table with "Give" Button and Delivery Method Column -->
        <h2>Items Needed</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Recipient </th>
                    <th>Reason</th>
                    <th>Area</th>
                    <th>Delivery Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 // Start the session

                
                include 'C:\xampp\htdocs\Second attempt\db_connection.php'; // Include your database connection

                // Query to fetch needed items
                $sql = "SELECT request_id, item_name, recipient, reason, area, delivery_method FROM requests"; // Adjust the table name and fields as necessary
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['request_id']}</td>
                                <td>{$row['item_name']}</td>
                                <td>{$row['recipient']}</td>
                                <td>{$row['reason']}</td>
                                <td>{$row['area']}</td>
                                <td>{$row['delivery_method']}</td>
                                <td><a href='list_item.html' class='btn'>Give</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No items needed at the moment.</td></tr>";
                }

                // Check if the user_id is set in the session
                if (!isset($_SESSION['user_id'])) {
                    // Handle the case when the user is not logged in
                    // For example, redirect to the login page
                    header("Location: login.php");
                    exit(); // Stop executing the rest of the script
                }

                // Fetch notifications for the logged-in donor
                $donorId = $_SESSION['user_id']; // Assuming you have the donor's ID in the session
                $notificationSql = "SELECT item_name, message, created_at FROM notifications WHERE donor_id = ? ORDER BY created_at DESC";
                $notificationStmt = $conn->prepare($notificationSql);
                $notificationStmt->bind_param("i", $donorId);
                $notificationStmt->execute();
                $notificationResult = $notificationStmt->get_result();
                ?>

            </tbody>
        </table>

        <!-- Notification Section -->
        <div class="notification-section">
            <h2>Your Notifications</h2>
            <?php
            // Fetch notifications for the logged-in donor, including org_name and email from ngos table
            $donorId = $_SESSION['user_id']; // Assuming you have the donor's ID in the session
            $notificationSql = "
               
    SELECT notifications.item_name, notifications.message, notifications.created_at, 
           ngos.org_name, ngos.email 
    FROM notifications 
    LEFT JOIN ngos ON notifications.ngo_id = ngos.user_id 
    WHERE notifications.donor_id = ? 
    ORDER BY notifications.created_at DESC";

                
            $notificationStmt = $conn->prepare($notificationSql);
            $notificationStmt->bind_param("i", $donorId);
            $notificationStmt->execute();
            $notificationResult = $notificationStmt->get_result();

            if ($notificationResult->num_rows > 0) {
                while ($notification = $notificationResult->fetch_assoc()) {
                    echo '<div class="notification">';
                    echo '<strong>Item: ' . htmlspecialchars($notification['item_name']) . '</strong><br>';
                    echo htmlspecialchars($notification['message']) . '<br>';
                    echo 'Organization: ' . htmlspecialchars($notification['org_name']) . '<br>';
                    echo 'Email: ' . htmlspecialchars($notification['email']) . '<br>';
                    echo '<small>' . htmlspecialchars($notification['created_at']) . '</small>';
                    echo '</div>';
                }
            } else {
                echo '<p>No notifications.</p>';
            }
            $notificationStmt->close();
            $conn->close(); // Close the database connection
            ?>
        </div>
    </div>

</body>
</html>
