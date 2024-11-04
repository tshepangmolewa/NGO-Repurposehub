<?php 
// Fetch users from the database
$servername = "localhost";
$username = "root";
$password = ""; // Database password here
$dbname = "repurposehub"; // Database name here

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_id, username, user_type FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

if (isset($_POST['delete_user_id'])) {
    $delete_user_id = $_POST['delete_user_id'];

    // Delete any related records in the messages table first
    $delete_messages_sql = "DELETE FROM messages WHERE receiver_id = ?";
    $stmt_messages = $conn->prepare($delete_messages_sql);
    $stmt_messages->bind_param('i', $delete_user_id);
    $stmt_messages->execute();
    $stmt_messages->close();

    // Delete any related records in the donors table
    $delete_donors_sql = "DELETE FROM donors WHERE user_id = ?";
    $stmt_donors = $conn->prepare($delete_donors_sql);
    $stmt_donors->bind_param('i', $delete_user_id);
    $stmt_donors->execute();
    $stmt_donors->close();

    // Now delete the user from the users table
    $delete_sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_user_id);

    if ($stmt->execute()) {
        $message = "User deleted successfully.";
    } else {
        $error = "Failed to delete user.";
    }
    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Users</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #dd2f6e; color: white; }
        button { padding: 6px 12px; cursor: pointer; border: none; color: white; border-radius: 5px; }
        .view-btn { background-color: #5cba47; }
        .delete-btn { background-color: #d9534f; }
        header { display: flex; margin-left: -30px; justify-content: space-between; padding: 1rem 1rem; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); position: fixed; width: 97.5%; top: 0; z-index: 100; height: 50px; }
        header h2 { color: #222; }
        header label span { font-size: 1.7rem; padding-left: 1rem; }
    </style>
</head>
<body>

    <h2>User Management</h2>
    
    <table id="usersTable">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        <header>
            <h2><label for=""></label></h2>
            <div class="user-wrapper">
                <div><a href="admin.php"> >BACK </a></div>
            </div>
        </header>

        <!-- Display success or error message -->
        <?php if (isset($message)) echo "<p class='success-message'>$message</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

        <?php foreach ($users as $user) : ?>
            <tr data-id="<?= $user['user_id'] ?>">
                <td><?= $user['user_id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['user_type'] ?></td>
                <td>
                    <!-- Delete user form -->
                    <form method="post">
                        <input type="hidden" name="delete_user_id" value="<?= $user['user_id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Function to view user details
        function viewUser(userId) {
            alert("User details for ID: " + userId);
            // Additional logic can go here, e.g., opening a modal with user info.
        }

    
    </script>

</body>
</html>




    