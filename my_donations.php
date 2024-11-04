<?php
session_start();
include 'C:\xampp\htdocs\Second attempt\db_connection.php'; // Include your database connection

// Fetch donor ID from session
$donor_id = $_SESSION['user_id'];

// Fetch donations from the database
$query = "SELECT * FROM donations WHERE donor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();
$donations = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Donations</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f8;
            width: 100%;
            height: 100%;
        }

        .container {
            width: 203vh;
            height: 100vh; /* Full-screen height */
            padding: 20px;
            background: #ffffff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Scrollable content */
        }

        h1 {
            text-align: left;
            color: #333;
            margin-bottom: 30px;
        }

        .donation-table {
            width: 100%;
            border-collapse: collapse;
        }

        .donation-table th, .donation-table td {
            text-align: left;
            padding: 15px; /* Default padding for table cells */
            border-bottom: 1px solid #ddd;
        }

        /* Adjusted styles for header cells */
        .donation-table th {
            background-color: #3498db;
            color: white;
            padding: 8px; /* Slimmer padding for header cells */
            font-weight: 600;
        }

        .donation-table tr:hover {
            background-color: #f2f2f2;
        }

        .donation-table td {
            font-size: 14px;
        }

        .status-label {
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
            font-size: 12px;
        }

        .status-available {
            background-color: #4CAF50; /* Green */
        }

        .status-claimed {
            background-color: #FFC107; /* Amber */
        }

        .status-delivered {
            background-color: #3498db; /* Blue */
        }

        .organization-label {
            margin-left: 10px;
            font-style: italic;
            color: #555;
            font-size: 12px;
        }

        .empty-message {
            text-align: center;
            color: #666;
            font-size: 16px;
            margin: 50px 0;
        }

        .dashboard-link {
            text-align: right;
            margin-top: 30px;
        }

        .dashboard-link a {
            color: #3498db;
            text-decoration: none;
            font-size: 16px;
        }

        .dashboard-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Donations</h1>

        <!-- Donation List Table -->
        <table class="donation-table">
            <thead>
                <tr>
                    <th style="height: 35px;">Donation ID</th> <!-- Slimmer header height -->
                    <th style="height: 35px;">Item Name</th>
                    <th style="height: 35px;">Category</th>
                    <th style="height: 35px;">Condition</th>
                    <th style="height: 35px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($donations)): ?>
                    <tr>
                        <td colspan="5" class="empty-message">You have not made any donations yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['donation_id']); ?></td>
                        <td><?php echo htmlspecialchars($donation['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($donation['category']); ?></td>
                        <td><?php echo htmlspecialchars($donation['item_condition']); ?></td>
                        <td>
                            <span class="status-label status-<?php echo strtolower(htmlspecialchars($donation['status'])); ?>">
                                <?php echo htmlspecialchars($donation['status']); ?>
                            </span>
                            <?php if ($donation['status'] === 'Claimed'): ?>
                                <span class="organization-label">by <?php echo htmlspecialchars($donation['organization_name']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard Link -->
        <div class="dashboard-link">
            <a href="donor_dashboard.php"> < Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
