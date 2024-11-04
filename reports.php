<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Reports</title>
  <!-- Include Chart.js for graphs -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    * {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f9;
  color: #333;
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
  font-size: 1.5em;
  margin-bottom: 1em;
  text-align: center;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar ul li {
  margin: 10px 0;
}

.sidebar ul li a {
  color: #fff;
  text-decoration: none;
  padding: 10px;
  display: block;
  border-radius: 4px;
  transition: background 0.3s;
}

.sidebar ul li a:hover {
  background-color: #575757;
}

.content {
  flex-grow: 1;
  padding: 20px;
}

.report-section {
  margin-bottom: 40px;
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.report-section h2 {
  margin-bottom: 20px;
}

.filters {
  margin-bottom: 15px;
  display: flex;
  gap: 10px;
}

.filters label {
  font-weight: bold;
}

button {
  background-color: #007BFF;
  color: #fff;
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
      <h2>Reports</h2>
      <ul>
        <li><a href="#user-activity">User Activity</a></li>
        <li><a href="#donations-requests">Donations & Requests</a></li>
        <li><a href="#messaging">Messaging</a></li>
        <li><a href="#engagement">Engagement</a></li>
        <li><a href="#environmental-impact">Environmental Impact</a></li>
        <li><a href="#feedback">Feedback & Issues</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <section id="user-activity" class="report-section">
        <h2>User Activity Report</h2>
        <div class="filters">
          <label for="date-range">Date Range:</label>
          <input type="date" id="start-date">
          <input type="date" id="end-date">
          <button onclick="exportReport('user-activity')">Export PDF</button>
        </div>
        <canvas id="user-activity-chart" width="300" height="200"></canvas>
      </section>

      <section id="donations-requests" class="report-section">
        <h2>Donations & Requests Report</h2>
        <div class="filters">
          <label for="date-range">Date Range:</label>
          <input type="date" id="start-date-2">
          <input type="date" id="end-date-2">
          <button onclick="exportReport('donations-requests')">Export CSV</button>
        </div>
        <canvas id="donations-chart" width="300" height="100"></canvas>
      </section>

      <!-- Additional sections follow the same structure -->
    </main>
  </div>

  <!-- Script for Chart.js Graphs -->
  <script>
    const userActivityChartCtx = document.getElementById('user-activity-chart').getContext('2d');
    new Chart(userActivityChartCtx, {
      type: 'bar',
      data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: 'New Registrations',
          data: [12, 19, 3, 5],
          backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
      }
    });

    const donationsChartCtx = document.getElementById('donations-chart').getContext('2d');
    new Chart(donationsChartCtx, {
      type: 'pie',
      data: {
        labels: ['Clothes', 'Books', 'Electronics', 'Furniture'],
        datasets: [{
          data: [30, 15, 20, 35],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
        }]
      }
    });

    function exportReport(section) {
      alert('Exporting ' + section + ' report');
      // Add export functionality here
    }
  </script>
</body>
</html>
