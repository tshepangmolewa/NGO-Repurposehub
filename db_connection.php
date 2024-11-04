<?php
// Database credentials
$servername = "localhost";  // Usually 'localhost' if running on the same server
$username = "root";  // Your MySQL username
$password = "";  // Your MySQL password
$dbname = "repurposehub";  // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

