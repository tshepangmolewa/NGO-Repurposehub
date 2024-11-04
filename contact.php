<?php
// Contact form handling
$servername = "localhost";
$username = "root";
$password = ""; // Database password here
$dbname = "repurposehub"; // Database name here

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message_text = $_POST['message_text'];

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message_text)) {
        // Save to the database
        $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message_text, sent_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $subject, $message_text);

        if ($stmt->execute()) {
            $success_message = "Your message has been sent successfully!";
        } else {
            $error_message = "Failed to send your message. Please try again later.";
        }

        $stmt->close();
    } else {
        $error_message = "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RePurposeHub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .contact-container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            font-size: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        input, textarea, button {
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }
        button {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            margin-top: 15px;
        }
        button:hover {
            background-color: lightgray;
        }
        .message {
            margin-top: 15px;
            font-size: 0.9rem;
            color: green;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>

<div class="contact-container">
    <h2>Contact Us</h2>
    <?php if ($success_message): ?>
        <p class="message"><?= $success_message ?></p>
    <?php elseif ($error_message): ?>
        <p class="message error-message"><?= $error_message ?></p>
    <?php endif; ?>
    <form action="contact.php" method="POST">
        <label for="name">Your Name</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Your Email</label>
        <input type="email" id="email" name="email" required>

        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message_text">Message</label>
        <textarea id="message_text" name="message_text" rows="5" required></textarea>

        <button type="submit">Send Message</button>
    </form>
</div>

</body>
</html>
