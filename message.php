<?php 
session_start();
include 'C:\xampp\htdocs\Second attempt\db_connection.php'; // Include your database connection

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo "<div style='text-align: center; margin-top: 50px;'>
            <h3>You must be logged in to send a message.</h3>
            <a href='Second attempt\login.php' style='font-size: 18px; color: blue; text-decoration: underline;'>Click here to login</a>
          </div>";
    exit(); // Stop further execution
}


// Fetch messages from the database
$query = "SELECT * FROM messages WHERE receiver_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $recipient_id = $_POST['recipient_id']; // Make sure to validate the recipient ID as well

    if (empty($message) || empty($recipient_id)) {
        echo "<script>alert('Message cannot be empty. Please try again.');</script>";
    } else {
        // Ensure user_id is not null
        $sender_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $recipient_id, $message);
        if ($stmt->execute()) {
            echo "<script>alert('Message sent successfully!');</script>";
        } else {
            echo "<script>alert('Error sending message. Please try again.');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging System</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar for conversation list */
        .sidebar {
            width: 25%;
            background: #ffffff;
            border-right: 1px solid #ddd;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            padding: 20px;
            background: #2c3e50;
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            border-bottom: 1px solid #ddd;
        }

        .conversation-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .conversation-list li {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background 0.3s;
        }

        .conversation-list li:hover {
            background: #f8f9fa;
        }

        .conversation-list li img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .conversation-list li .conversation-info {
            display: flex;
            flex-direction: column;
        }

        .conversation-list li .conversation-info .name {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        .conversation-list li .conversation-info .preview {
            font-size: 14px;
            color: #777;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Chat area */
        .chat-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background: #f4f7f8;
        }

        .chat-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            font-size: 20px;
            display: flex;
            align-items: center;
        }

        .chat-header img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background: #e8e8e8;
            display: flex;
            flex-direction: column;
        }

        .message-bubble {
            max-width: 60%;
            padding: 12px 18px;
            border-radius: 20px;
            position: relative;
            margin-bottom: 10px;
            font-size: 15px;
            line-height: 1.4;
            display: inline-block;
        }

        .message.sent {
            background: #d1e7fd;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
            border-bottom-left-radius: 20px;
            margin-left: 40px;
        }

        .message.received {
            background: #ffffff;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 20px;
            margin-right: 40px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
        }

        .message-bubble .timestamp {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        /* Styles for read receipts (ticks) */
        .timestamp .single-tick, .timestamp .double-tick {
            margin-left: 5px;
        }

        .timestamp .single-tick::before {
            content: '\2713'; /* Single checkmark */
            color: #666;
        }

        .timestamp .double-tick::before {
            content: '\2713 \2713'; /* Double checkmarks */
            color: #666;
        }

        .timestamp .double-tick.read::before {
            color: #0b93f6; /* Blue ticks for read messages */
        }

        .message-box {
            display: flex;
            border-top: 1px solid #ddd;
            padding: 15px;
            background: white;
            align-items: center;
        }

        .message-box textarea {
            width: calc(100% - 60px);
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px;
            outline: none;
            resize: none;
            font-size: 15px;
            box-shadow: inset 0px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .send-button {
            width: 50px;
            height: 50px;
            margin-left: 10px;
            background: #2c3e50;
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .send-button:hover {
            background: #0056b3;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 40%;
            }

            .send-button {
                width: 40px;
                height: 40px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: 200px;
            }

            .chat-area {
                height: calc(100% - 200px);
            }

            .send-button {
                width: 30px;
                height: 30px;
                font-size: 18px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar for Conversation List -->
        <div class="sidebar">
            <h2>Repurposehub Chats</h2>
            <ul class="conversation-list">
                <li>
                    <img src="https://via.placeholder.com/40" alt="User 1">
                    <div class="conversation-info">
                        <span class="name">NGO 1</span>
                        <span class="preview">Received your donation offer...</span>
                    </div>
                </li>
                <li>
                    <img src="https://via.placeholder.com/40" alt="User 2">
                    <div class="conversation-info">
                        <span class="name">NGO 2</span>
                        <span class="preview">Hello! Let's coordinate...</span>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="chat-header">
                <img src="https://via.placeholder.com/35" alt="Selected User">
                <span>NGO 1</span>
            </div>
            <div class="chat-messages">
                <?php foreach ($messages as $msg): ?>
                    <div class="message-bubble <?php echo $msg['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received'; ?>">
                        <p><?php echo htmlspecialchars($msg['message_content']); ?></p>
                        <div class="timestamp">
                            <span><?php echo date('H:i', strtotime($msg['date_sent'])); ?></span>
                            <?php if ($msg['sender_id'] == $_SESSION['user_id']): ?>
                                <span class="single-tick <?php echo $msg['read_receipt'] ? 'double-tick read' : 'double-tick'; ?>"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="message-box">
                <form method="POST" action="">
                    <textarea name="message" rows="1" placeholder="Type your message..."></textarea>
                    <input type="hidden" name="recipient_id" value="1"> <!-- Example recipient ID, change as needed -->
                    <button type="submit" class="send-button">→</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
