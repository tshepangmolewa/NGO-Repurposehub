<?php
// Start the session
session_start();

// Check if the user is logged in and is an admin
//if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    //header("Location: login.php");
    //exit();
//}

$servername = "localhost";
$username = "root";
$password = ""; // Database password here
$dbname = "repurposehub"; // Database name here

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch donations
$sql = "SELECT d.donation_id, u.username, d.item_name, d.description, d.quantity, d.donation_date, d.status 
        FROM donations d 
        JOIN users u ON d.donor_id = u.user_id 
        ORDER BY d.donation_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Donations Page</title>
    
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #dd2f6e;
            color: white;
        }
        button {
            padding: 6px 12px;
            cursor: pointer;
            border: none;
            color: white;
            border-radius: 5px;
        }
        .view-btn {
            background-color: #5cba47;
        }
        .delete-btn {
            background-color: #d9534f;
        }

        header{
            display: flex;
            margin-left: -30px;
            justify-content: space-between;
            padding: 1rem 1rem;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            position: fixed;  
            width: 97.5%;
            top: 0;
            z-index: 100;
            height: 60px;
        }

        header h2{
            color: #222;
        }

        header label span{
            font-size: 1.7rem;
            padding-left: 1rem;
        }
    </style><!-- Link to your CSS file -->
</head>
<body>
    <h1>Donations List</h1>

    <table>
        <thead>
            <tr>
                <th>Donation ID</th>
                <th>Donor Username</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Donation Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <header>
            <h2>
                <label for="">
                    
                </label>
                
            </h2>

            <div class="user-wrapper">
                <div>
                    <a href="admin.php"> >BACK </a>
                </div>
           
        </header>

            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['donation_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['donation_date']; ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No donations found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin.php">Back to Dashboard</a>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
