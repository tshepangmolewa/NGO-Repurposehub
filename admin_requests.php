<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Requests</title>
    <style>
        /* Updated CSS */
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
            height: 50px;
        }

        header h2{
            color: #222;
        }

        header label span{
            font-size: 1.7rem;
            padding-left: 1rem;
        }
    </style>
</head>
<body>
    <h2>Requests</h2>

    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Item Name</th>
                <th>Requested Quantity</th>
                <th>Status</th>
                <th>Request Date</th>
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
        
            <?php
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = ""; // Your database password
            $dbname = "repurposehub"; // Your database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            

            // Fetch requests from the database
            $sql = "SELECT requests.request_id, requests.item_name, 
               requests.requested_quantity, requests.status, requests.request_date 
        FROM requests 
        LEFT JOIN ngos ON requests.ngo_id = ngos.user_id";

$result = $conn->query($sql);





            // Check if there are results
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['request_id'] . "</td>";
                    echo "<td>" . $row['item_name'] . "</td>";
                    echo "<td>" . $row['requested_quantity'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['request_date'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No requests found</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>

