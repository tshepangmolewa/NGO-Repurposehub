<?php
session_start(); // Start the session
include 'db_connection.php'; // Include database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username'])); // This will now be the email
    $password = htmlspecialchars(trim($_POST['password']));
    $userType = htmlspecialchars(trim($_POST['userType']));

    // Prepare SQL to check for a matching user in the database based on userType
    $sql = "SELECT user_id, password FROM users WHERE username = '$username' AND user_type = '$userType'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the user exists
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password']; // The stored hashed password
        $user_id = $row['user_id'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, create a session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username; // Still using username for session, but it's email
            $_SESSION['user_type'] = $userType;

            // Redirect based on the user type
            if ($userType == "donor") {
                header("Location: donor_dashboard.php");
            } elseif ($userType == "ngo") {
                header("Location: ngo_dashboard.php");
            } elseif ($userType == "admin") {
                header("Location: adminDashboard.html");
            }
            exit();
        } else {
            // Invalid password
            echo "<p class='text-danger'>Invalid password. Please try again.</p>";
        }
    } else {
        // User not found
        echo "<p class='text-danger'>No account found with that username and user type.</p>"; // Update message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 18px sans-serif;
            background-color: #f7f7f7;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .wrapper {
            width: 100%;
            max-width: 480px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 44px;
            margin-bottom: 20px;
            font-weight: 400;
        }
        p {
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="text-left">Login</h2>
        <p class="text-left">Please fill in your credentials to login.</p>
        
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username (Email)</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="userType">User Type:</label>
                <select id="userType" name="userType" class="form-control" required>
                    <option value="" disabled selected>Select user type</option>
                    <option value="donor">Donor</option>
                    <option value="ngo">NGO</option>
                    <option value="admin">Admin</option>
                </select>
            </div>            

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <!-- Updated Registration Link -->
            <p class="text-left">Don't have an account? <a href="#" onclick="redirectToRegister()">Register now</a>.</p>
            <a href="homepage.php">HOME</a>
        </form> 
    </div>

    <script>
        function redirectToRegister() {
            const userType = document.getElementById("userType").value;

            if (userType) {
                // Define the URLs for each registration form
                const registrationURLs = {
                    "donor": "donorRegister.php",
                    "ngo": "organizationRegister.php",
                    "admin": "admin.html"
                };

                // Redirect to the corresponding registration page
                window.location.href = registrationURLs[userType];
            } else {
                alert("Please select a user type to register.");
            }
        }
    </script>
</body>
</html>
