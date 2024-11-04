<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Registration Form</title>
    <style>
        body, html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f7f8;
        }

        .container {
            width: 50%;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #4a4a4a;
        }

        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #0288d1;
        }

        button[type="submit"] {
            margin-top: 20px;
            padding: 12px 25px;
            background: #0288d1;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s ease;
            width: 100%;
        }

        button[type="submit"]:hover {
            background: #0277bd;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 10px;
            text-align: center;
        }

        .modal-content h2 {
            margin-bottom: 20px;
            color: #0288d1;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #0288d1;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .modal-content button:hover {
            background-color: #0277bd;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="registration-form" method="POST" action="">
            <h2>Donor details</h2>

            <label for="donorName">First Name <span>*</span></label>
            <input type="text" id="donorName" name="donorName" required>
            <div id="donorNameError" class="error-message">First name must contain only letters.</div>

            <label for="donorLast">Last Name <span>*</span></label>
            <input type="text" id="donorLast" name="donorlast" required>
            <div id="donorLastError" class="error-message">Last name must contain only letters.</div>

            <label for="donorEmail">Email <span>*</span></label>
            <input type="email" id="donorEmail" name="donorEmail" required>
            <div id="donorEmailError" class="error-message">Email must be in the format name@gmail.com, name@co.za, or name@ac.za.</div>

            <label for="donorPass">Password <span>*</span></label>
            <input type="password" id="donorPass" name="donorPass" required>
            <div id="donorPassError" class="error-message">Password must be at least 8 characters long, include a number and a special character.</div>

            <label for="number">Contact number <span>*</span></label>
            <input type="number" id="number" name="number" required>
            <div id="numberError" class="error-message">Contact number must be exactly 10 digits.</div>

            <label for="address">Address <span>*</span></label>
            <input type="text" id="address" name="address" required>
            <div id="addressError" class="error-message">Address must contain only letters and numbers.</div>


            <button type="submit">Submit</button>

            <a href="login.php"> >BACK</a>
        </form>

        <!-- Modal structure -->
        <div id="successModal" class="modal">
            <div class="modal-content">
                <h2>Registration Successful!</h2>
                <p id="successMessage"></p>
                <button onclick="closeModal()">OK</button>
            </div>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'C:/xampp/htdocs/Second attempt/db_connection.php';

            // Collect and sanitize input data
            $donorName = htmlspecialchars(trim($_POST['donorName']));
            $donorLast = htmlspecialchars(trim($_POST['donorlast']));
            $donorEmail = htmlspecialchars(trim($_POST['donorEmail']));
            $donorPass = htmlspecialchars(trim($_POST['donorPass']));
            $contactNumber = htmlspecialchars(trim($_POST['number']));
            $address = htmlspecialchars(trim($_POST['address']));

            // Hash the password
            $hashedPassword = password_hash($donorPass, PASSWORD_DEFAULT);

            // Insert into 'users' table first
            $sql_users = "INSERT INTO users (username, email, password, user_type) 
                          VALUES ('$donorEmail', '$donorEmail', '$hashedPassword', 'donor')";

            if (mysqli_query($conn, $sql_users)) {
                // Get the inserted user_id
                $user_id = mysqli_insert_id($conn);

                // Now insert into 'donors' table
                $sql_donors = "INSERT INTO donors (user_id, first_name, last_name, contact_number, address) 
                               VALUES ('$user_id', '$donorName', '$donorLast', '$contactNumber', '$address')";

                if (mysqli_query($conn, $sql_donors)) {
                    echo "<script>
                            document.getElementById('successMessage').textContent = 'Thank you for your support, $donorName.';
                            document.getElementById('successModal').style.display = 'flex';
                          </script>";
                } else {
                    echo "<p class='error'>Error inserting into donors: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "<p class='error'>Error inserting into users: " . mysqli_error($conn) . "</p>";
            }

            // Close the database connection
            mysqli_close($conn);
        }
        ?>

    </div>

    <script>

        // Form validation function
        document.getElementById("registration-form").addEventListener("submit", function(event) {
            // Regular expressions for validation
            const nameRegex = /^[A-Za-z]+$/;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|co\.za|ac\.za)$/;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            const contactNumberRegex = /^\d{10}$/;
            const addressRegex = /^[A-Za-z0-9\s]+$/;

            // Get field values
            const donorName = document.getElementById("donorName").value;
            const donorLast = document.getElementById("donorLast").value;
            const donorEmail = document.getElementById("donorEmail").value;
            const donorPass = document.getElementById("donorPass").value;
            const contactNumber = document.getElementById("number").value;
            const address = document.getElementById("address").value;

            // Error message elements
            const donorNameError = document.getElementById("donorNameError");
            const donorLastError = document.getElementById("donorLastError");
            const donorEmailError = document.getElementById("donorEmailError");
            const donorPassError = document.getElementById("donorPassError");
            const numberError = document.getElementById("numberError");
            const addressError = document.getElementById("addressError");

            let isValid = true;

            // Clear previous errors
            donorNameError.style.display = "none";
            donorLastError.style.display = "none";
            donorEmailError.style.display = "none";
            donorPassError.style.display = "none";
            numberError.style.display = "none";
            addressError.style.display = "none";

            // Validation checks and error message display
            if (!nameRegex.test(donorName)) {
                donorNameError.style.display = "block";
                isValid = false;
            }
            if (!nameRegex.test(donorLast)) {
                donorLastError.style.display = "block";
                isValid = false;
            }
            if (!emailRegex.test(donorEmail)) {
                donorEmailError.style.display = "block";
                isValid = false;
            }
            if (!passwordRegex.test(donorPass)) {
                donorPassError.style.display = "block";
                isValid = false;
            }
            if (!contactNumberRegex.test(contactNumber)) {
                numberError.style.display = "block";
                isValid = false;
            }
            if (!addressRegex.test(address)) {
                addressError.style.display = "block";
                isValid = false;
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });


        // Function to close the modal
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
            window.location.href = 'http://localhost/Second%20attempt/login.php';
        }
    </script>
</body>
</html>


