<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Messaging System</title>
    <style>
        /* Your existing styles here */
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
        }

        .message.received {
            background: #ffffff;
            align-self: flex-start;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
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
        }

        .send-button:hover {
            background: #1a242f;
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
                <?php
                // Fetch conversations for the NGO
                
                include 'db_connection.php'; // Include your database connection
                

                $sql = "
                SELECT DISTINCT sender_id FROM messages 
                WHERE receiver_id = ? ORDER BY sent_at DESC";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<li>';
                        echo '<div class="conversation-info">';
                        echo '<span class="name">Donor ' . htmlspecialchars($row['sender_id']) . '</span>';
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo '<li>No conversations found.</li>';
                }
                ?>
            </ul>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="chat-header">
                <span>Chat with Donor</span>
            </div>
            <div class="chat-messages">
                <?php

include 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_text = $_POST['message'];
    $receiver_id = $_POST['receiver_id']; // Ensure this matches your input name

    // Prepare and execute the insert statement
    $sql = "INSERT INTO messages (sender_id, receiver_id, message_text, sent_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $_SESSION['user_id'], $receiver_id, $message_text);

    if ($stmt->execute()) {
        // Redirect back to the chat page after sending the message
        header("Location: ngoMessages.php?receiver_id=" . $receiver_id);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}



                // Display messages
                $sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiii", $_SESSION['user_id'], $receiver_id, $_SESSION['user_id']);
                $stmt->execute();
                $messages = $stmt->get_result();

                if ($messages->num_rows > 0) {
                    while ($message = $messages->fetch_assoc()) {
                        $class = ($message['sender_id'] == $_SESSION['user_id']) ? 'sent' : 'received';
                        echo '<div class="message-bubble message ' . $class . '">';
                        echo htmlspecialchars($message['message_text']);
                        echo '<span class="timestamp">' . htmlspecialchars($message['sent_at']) . '</span>';
                        echo '</div>';
                    }
                } else {
                    echo '<div>No messages found.</div>';
                }
                ?>
            </div>
            <div class="message-box">
                <form id="messageForm" action="ngoMessages.php" method="POST">
                    <textarea id="message" name="message" placeholder="Type your message..." required></textarea>
                    <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($receiver_id); ?>">
                    <button type="submit" class="send-button">➤</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>



