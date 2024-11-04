<?php
$servername = "localhost";
$username = "root";
$password = ""; // Database password here
$dbname = "repurposehub"; // Database name here

$conn = new mysqli($servername, $username, $password, $dbname);
// Handle user deletion
if (isset($_POST['delete_user_id'])) {
    // Check if the admin is logged in
   /* if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php"); // Redirect to login page if not logged in
        exit;
    } */

    $delete_sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_user_id);

    if ($stmt->execute()) {
        $message = "User deleted successfully.";
    } else {
        $error = "Failed to delete user.";
    }
}


?>