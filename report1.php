

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    display: flex;
}

.container {
    display: flex;
}

.sidebar {
    width: 250px;
    background-color: #333;
    color: #fff;
    padding: 20px;
}

.sidebar h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
}

.sidebar nav ul {
    list-style-type: none;
}

.sidebar nav ul li {
    margin: 15px 0;
}

.sidebar nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 18px;
}

.content {
    padding: 20px;
    width: 100%;
}

h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.export {
    margin-top: 15px;
}

.export button {
    padding: 10px 15px;
    background-color: #28a745;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

.export button:hover {
    background-color: #218838;
}
 
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <h2>Reports</h2>
            <nav>
                <ul>
                    <li><a href="#user-activity">User Activity</a></li>
                    <li><a href="#donations-requests">Donations & Requests</a></li>
                    <li><a href="#messaging">Messaging</a></li>
                    <li><a href="#engagement">User Engagement</a></li>
                    <li><a href="#environmental-impact">Environmental Impact</a></li>
                    <li><a href="#feedback">Feedback & Issues</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="content">
            <section id="user-activity">
                <h2>User Activity Report</h2>
                <?php
                // db_connection.php should already include your database connection code.
include 'db_connection.php';

// 1. Registration Data for Bar Chart
$registrationData = [];
$query = "SELECT user_type, COUNT(*) as count, DATE(registration_date) as reg_date 
          FROM users GROUP BY user_type, DATE(registration_date)";
$result = $conn->query($query);
while($row = $result->fetch_assoc()) {
    $registrationData[] = $row;
}

// 2. Active Users Data for Pie Chart
$activeUsersData = [];
$query = "SELECT user_type, COUNT(*) as active_count 
          FROM users WHERE last_login >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) 
          GROUP BY user_type";
$result = $conn->query($query);
while($row = $result->fetch_assoc()) {
    $activeUsersData[] = $row;
}
?>
                <canvas id="registrationChart"></canvas>
                <div class="export">
                    <button onclick="exportPDF('user-activity')">Download as PDF</button>
                </div>
            </section>

            <section id="donations-requests">
                <h2>Donations & Requests Report</h2>
                <canvas id="donationChart"></canvas>
                <div class="export">
                    <button onclick="exportPDF('donations-requests')">Download as PDF</button>
                </div>
            </section>

            <section id="messaging">
                <h2>Messaging Report</h2>
                <canvas id="messageChart"></canvas>
                <div class="export">
                    <button onclick="exportPDF('messaging')">Download as PDF</button>
                </div>
            </section>

            <section id="engagement">
                <h2>User Engagement Metrics</h2>
                <canvas id="churnChart"></canvas>
                <div class="export">
                    <button onclick="exportPDF('engagement')">Download as PDF</button>
                </div>
            </section>

            <section id="environmental-impact">
                <h2>Environmental Impact Report</h2>
                <canvas id="wasteReductionChart"></canvas>
                <div class="export">
                    <button onclick="exportPDF('environmental-impact')">Download as PDF</button>
                </div>
            </section>

            <section id="feedback">
                <h2>Feedback and Issue Tracking</h2>
                <table>
                    <tr><th>Issue</th><th>Priority</th><th>Status</th></tr>
                    <?php
                    // PHP to fetch and display common issues
                    include 'db_connection.php'; // Make sure this points to your database connection file
                    $sql = "SELECT issue, priority, status FROM feedback_issues";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['issue']}</td><td>{$row['priority']}</td><td>{$row['status']}</td></tr>";
                    }
                    ?>
                </table>
                <div class="export">
                    <button onclick="exportPDF('feedback')">Download as PDF</button>
                </div>
            </section>
        </main>
    </div>

    <!-- JavaScript for Charts -->
    <script>
        const registrationCtx = document.getElementById('registrationChart').getContext('2d');
        const donationCtx = document.getElementById('donationChart').getContext('2d');
        const messageCtx = document.getElementById('messageChart').getContext('2d');
        const churnCtx = document.getElementById('churnChart').getContext('2d');
        const wasteReductionCtx = document.getElementById('wasteReductionChart').getContext('2d');

        // Initialize charts
        new Chart(registrationCtx, { /* registration data */ });
        new Chart(donationCtx, { /* donation data */ });
        new Chart(messageCtx, { /* message data */ });
        new Chart(churnCtx, { /* churn data */ });
        new Chart(wasteReductionCtx, { /* waste reduction data */ });

        function exportPDF(sectionId) {
            // JS function to export the specific section as PDF
            // This would use a library like jsPDF
        }
    </script>
</body>
</html>
