<?php
// Database connection (update credentials accordingly)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "repurposehub"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch counts from each table
$donorCount = $conn->query("SELECT COUNT(*) as count FROM donors")->fetch_assoc()['count'];
$ngoCount = $conn->query("SELECT COUNT(*) as count FROM ngos")->fetch_assoc()['count'];
$totalDonations = $conn->query("SELECT COUNT(*) as count FROM donations")->fetch_assoc()['count'];
$requestCount = $conn->query("SELECT COUNT(*) as count FROM requests")->fetch_assoc()['count'];
$usersCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];


$conn->close();
?>


<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <style>
        :root {
            --main-color: #DD2F6E;
            --color-dark: #1D2231;
            --text-grey: #8390A2;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            list-style-type: none;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            width: 270px;
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            background: var(--color-dark);
            z-index: 100;
        }

        .sidebar-brand {
            height: 56px;
            padding: 1rem 0rem 1rem 2rem;
            color: #fff;
        }

        .sidebar-brand span {
            display: inline-block;
            padding-right: 1rem;
        }

        .sidebar-menu{
            margin-top: 1rem;
        }

        .sidebar-menu ul {
            padding-left: 0;
        }

        .sidebar-menu li {
            width: 100%;
            margin-bottom: 1.3rem; /* Space between items */
            padding-left: 25px; /* Padding for indentation */
            margin-left: 0; /* No extra margin */
        }

        .sidebar-menu a {
            padding-left: 1rem;
            display: block;
            color: #fff;
            font-size: 1.1rem;
        }

        .sidebar-menu a span:first-child {
            font-size: 1.5rem;
            padding-right: 1rem;
        }

        .sidebar-menu a.active{
            background-color: #fff;
            padding-top: 1rem;
            padding-bottom: 1rem;
            color: var(--color-dark);
            border-radius: 30px 0px 0px 30px;
            
        }

        header{
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            position: fixed;
            
            width: 100%;
            top: 0;
            z-index: 100;
        }

        header h2{
            color: #222;
        }

        header label span{
            font-size: 1.7rem;
            padding-left: 1rem;
        }

        .main-content {
            margin-left: 270px;
        }

        .search-wrapper{
            border: 1px solid #f0f0f0;
            border-radius: 30px;
            height: 50px;
            display: flex;
            align-items: center;
            overflow-x: hidden;
        }

        .search-wrapper span{
            display: inline-block;
            padding: 0rem 1rem;
            font-size: 1.5rem;
        }

        .search-wrapper input{
            height: 100%;
            padding: .5rem;
            border: none;
            outline: none;
        }

        .user-wrapper{
            display: flex;
            align-items: center;
        }

        .user-wrapper img{
            border-radius: 50%;
            margin-right: 1rem;
        }

        .user-wrapper h4{
            margin-bottom: 0rem !important;
        }

        .user-wrapper small{
            display: inline-block;
            color: var(--text-grey);
            margin-top: -3px !important;
        }

        main{
            margin-top: 75px;
            padding: 2rem 1.5rem;
            background: #f1f5f9;
            min-height: calc(100vh - 90px);
        }

        .cards {
            display: grid;
            gap: 2rem; /* Space between cards */
            grid-template-columns: repeat(4, 1fr);
            margin-top: 1rem;
        }

        .card-single {
            display: flex;
            justify-content: space-between;
            background: #fff;
            padding: 2rem;
            border-radius: 2px;
        }

        .card-single div:last-child span{
            font-size: 3rem;
            color: var(--color-dark);
        }

        .card-single div:first-child span{
            color: var(--text-grey);
        }

        .card-single:last-child{
            background: var(--color-dark);
        }

        .card-single:last-child h1,
        .card-single:last-child div:first-child span,
        .card-single:last-child div:last-child span{
            color: #fff;
        }

        .card{
            background: #fff;
            border-radius: 5px;
        }

        .card-header,
        .card-body{
            padding: 1rem;
        }

        .card-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-header button{
            background: var(--color-dark);
            border-radius: 10px;
            color: #fff;
            font-size: .8rem;
            padding: .5rem 1rem;
            border: 1px solid var(--color-dark);
        }

        table{
            border-collapse: collapse;
        }

        thead tr{
            border: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        thead td{
           font-weight: 700;
        }

        td{
            padding: .5rem 1rem;
            font-size: .9rem;
            color: #222;
        }

        .recent-grid{
            margin-top: 3.5rem;
            display: grid;
            grid-gap: 2rem;
            grid-template-columns: 67% auto;
        }

        td .status{
            display: inline-block;
            height: 20px;
            width: 10px;
            border-radius: 50%;
            margin-right: 1rem;
        }

        tr td:last-child{
            display: flex;
            align-items: center;
        }

        .status.purple{
            background: rebeccapurple;
        }

        .status.pink{
            background: deeppink;
        }

        .status.orange{
            background: orangered;
        }

        .table-responsive{
            width: 100%;
            overflow-x: auto;
        }

        .customer{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .5rem 1rem;
        }

        .info{
            display: flex;
            align-items: center;
        }

        .info img{
            border-radius: 50%;
            margin-right: 1rem;
        }

        .info h4{
            font-size: .8rem;
            font-weight: 700;
            color: #222;
        }

        .info small{
            font-weight: 600;
            color: var(--text-grey);
        }

        .sidebar-menu .active {
    background-color: #fff;
    padding-top: 1rem;
    padding-bottom: 1rem;
    color: var(--color-dark);
    border-radius: 30px 0px 0px 30px;
    transition: background-color 0.3s ease, padding 0.3s ease;
}


    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>RePurposeHub</h2>
        </div>

        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="admin.php" class="active"><span class="las la-igloo"></span><span>Dashboard</span></a>
                </li>
                <li>
                    <a href="users.php"><span class="las la-users"></span><span>Users</span></a>
                </li>
                <li>
                    <a href="admin_donation.php"><span class="las la-receipt"></span><span>Donations</span></a>
                </li>
                <li>
                    <a href="admin_requests.php"><span class="las la-user-circle"></span><span>Requests</span></a>
                </li>
                <li>
                    <a href="reports_dashboard.php"><span class="las la-clipboard-list"></span><span>Reports & Analytics</span></a>
                </li>
                <li>
                    <a href="login.php"><span class=""></span><span>logout</span></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h2>
                <label for="">
                    
                </label>
                Dashboard Overview
            </h2>    

            <div class="user-wrapper">
                <img src="img/2.jpg" width="40px" height="40px" alt="">
                <div>
                    <h4>Tshepang Molewa</h4>
                    <small>Admin</small>
                </div>
            </div>
        </header>

        <main>
            <div class="cards">
            <div class="card-single">
    <div>
        <h1><?php echo $donorCount; ?></h1>
        <span>Donors</span>
    </div>
    <div>
        <span class="las la-users"></span>
    </div>
</div>

<div class="card-single">
    <div>
        <h1><?php echo $ngoCount; ?></h1>
        <span>NGOs</span>
    </div>
    <div>
        <span class="las la-clipboard-list"></span>
    </div>
</div>

<div class="card-single">
    <div>
        <h1><?php echo $totalDonations; ?></h1>
        <span>Total Donations</span>
    </div>
    <div>
        <span class="las la-shopping-bag"></span>
    </div>
</div>

<div class="card-single">
    <div>
        <h1><?php echo $requestCount; ?></h1>
        <span>Requests</span>
    </div>
    <div>
        <span class="lab la-google-wallet"></span>
    </div>
</div>

<div class="card-single">
    <div>
        <h1><?php echo $usersCount; ?></h1>
        <span>Users</span>
    </div>
        <span class="las la-clipboard-list"></span>
</div>
</div>

            
            
            </div>
        </main>
  

    <script>
        // Example users data (this could be fetched from the server)
        const users = [
            { id: 1, username: 'johndoe', userType: 'Donor' },
            { id: 2, username: 'janedoe', userType: 'NGO' },
            { id: 3, username: 'adminuser', userType: 'Admin' }
        ];

        // Function to load users into the table
        function loadUsers() {
            const tableBody = document.getElementById('userTableBody');
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.userType}</td>
                    <td>
                        <button onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Function to delete a user
        function deleteUser(userId) {
            const confirmDelete = confirm('Are you sure you want to delete this user?');
            if (confirmDelete) {
                // Here you would typically send a request to the server to delete the user
                // For now, we'll just remove it from the users array and refresh the table
                const index = users.findIndex(user => user.id === userId);
                if (index > -1) {
                    users.splice(index, 1);
                    refreshUserTable();
                }
            }
        }

        // Function to refresh the user table
        function refreshUserTable() {
            const tableBody = document.getElementById('userTableBody');
            tableBody.innerHTML = ''; // Clear the table
            loadUsers(); // Reload users
        }

        // Function to generate a report
        function generateReport() {
            alert('Report generated!'); // Placeholder for actual report generation logic
        }

        // Load users when the page loads
        window.onload = loadUsers;

        // JavaScript to handle active link highlighting
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', function(event) {
        // Prevent default behavior
        event.preventDefault();

        // Remove active class from all links
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.classList.remove('active');
        });

        // Add active class only to the clicked link
        this.classList.add('active');

        // Navigate to the link's URL
        window.location.href = this.getAttribute('href');
    });
});



    </script>
</body>
</html>




