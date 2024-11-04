<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'C:/xampp/htdocs/Second attempt/db_connection.php';
    require 'path/to/twilio-sdk/src/Twilio/autoload.php';


    // Collect and sanitize form data
    $orgName = htmlspecialchars(trim($_POST['orgName']));
    $services = $_POST['services']; // Checkbox array
    $za = htmlspecialchars(trim($_POST['za']));
    $web = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);
    $donorPass = htmlspecialchars(trim($_POST['donorPass']));
    $num = htmlspecialchars(trim($_POST['num']));
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $workEmail = filter_input(INPUT_POST, 'workEmail', FILTER_SANITIZE_EMAIL);
    $CworkEmail = filter_input(INPUT_POST, 'CworkEmail', FILTER_SANITIZE_EMAIL);
    $position = htmlspecialchars(trim($_POST['position']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $postcode = htmlspecialchars(trim($_POST['postcode']));

    // Check if email field is captured properly
    if (empty($workEmail)) {
        die("Error: Email is empty or not captured correctly.");
    }

    // Validate email confirmation
    if ($workEmail !== $CworkEmail) {
        die("Error: Emails do not match.");
    }

    // Handle file uploads
    $orgImage = $_FILES['orgImage'];
    $logo = $_FILES['logo'];

    if ($orgImage['error'] !== UPLOAD_ERR_OK || $logo['error'] !== UPLOAD_ERR_OK) {
        die("Error: Image upload failed.");
    }

    // Validate file types and sizes (add your own validation logic here)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($orgImage['type'], $allowedTypes) || !in_array($logo['type'], $allowedTypes)) {
        die("Error: Only JPG, PNG, and GIF images are allowed.");
    }

    // Sanitize and move uploaded files
    $uploadDir = 'uploads/';
    $orgImageName = htmlspecialchars($orgImage['name']);
    $logoName = htmlspecialchars($logo['name']);
    move_uploaded_file($orgImage['tmp_name'], $uploadDir . $orgImageName);
    move_uploaded_file($logo['tmp_name'], $uploadDir . $logoName);

    // Check if email already exists in the `users` table
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $workEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Error: This email is already registered. Please use a different email.");
    }

    // Hash the password and store it in a variable
    $hashedPassword = password_hash($donorPass, PASSWORD_DEFAULT);

    // Insert into `users` table
    $userType = "NGO"; // Assuming NGO user type
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $workEmail, $workEmail, $hashedPassword, $userType);

    if ($stmt->execute()) {
        // Get the user_id from the last inserted row
        $user_id = $stmt->insert_id;
    } else {
        die("Error inserting into users table: " . $stmt->error);
    }

    // Insert into `ngos` table using the obtained user_id
    $stmt = $conn->prepare("INSERT INTO ngos (user_id, org_name, za_number, website, contact_number, first_name, last_name, email, position, phone, address, postal_code, org_image, logo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisissssisiss", $user_id, $orgName, $za, $web, $num, $firstName, $lastName, $workEmail, $position, $phone, $address, $postcode, $orgImageName, $logoName);

    if ($stmt->execute()) {
        echo "NGO registration successful.";
    } else {
        die("Error inserting into NGOs table: " . $stmt->error);
    }

    // Close connections
    $stmt->close();
    $conn->close();

    // Redirect or display success message
    header("Location: login.php"); // Redirect to the login page after registration
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Registration Form</title>
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

        h1, h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        .tab {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .tab div {
            width: 33%;
            padding: 15px;
            text-align: center;
            background-color: #f0f0f0;
            color: #555;
            border: 1px solid #ddd;
            border-bottom: none;
            cursor: pointer;
            border-radius: 8px 8px 0 0;
        }

        .tab .active {
            background-color: #0288d1;
            color: white;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #4a4a4a;
        }

        input, textarea, select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #0288d1;
        }

        button[type="button"], button[type="submit"] {
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
            width: 49%;
        }

        button[type="button"]:hover, button[type="submit"]:hover {
            background: #0277bd;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        /* Checkbox styling */
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            display: none;
        }

        .checkbox-group label {
            display: inline-block;
            padding: 10px 15px;
            background-color: #f0f0f0;
            margin-right: 10px;
            margin-bottom: 10px;
            border-radius: 20px;
            cursor: pointer;
            border: 1px solid #ddd;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .checkbox-group input[type="checkbox"]:checked + label {
            background-color: #0288d1;
            color: white;
            border-color: #0288d1;
        }

        /* Validation styling */
        input.invalid, textarea.invalid {
            border-color: red;
            background-color: #ffe6e6;
        }

        .tab div {
            pointer-events: none;
        }

    </style>
</head>
<body>

    <div class="container">
        <!-- Tab Navigation -->
        <div class="tab">
            <div class="active" id="tab-1">Organization details</div>
            <div id="tab-2">Your details</div>
            <div id="tab-3">Location details</div>
        </div>

        <!-- Form Steps -->
        <form id="registration-form" method="post" enctype="multipart/form-data">
            
            <!-- Step 1: Organization Details -->
            <div class="form-step active" id="step-1">
                <h2>Organisation details</h2>
            
                <label for="orgName">Organisation name <span>*</span></label>
                <input type="text" id="orgName" name="orgName" required pattern="^[a-zA-Z\s]+$" title="Organization name should only contain letters and spaces.">

                <label>Services provided by the organisation (choose all that applies) <span>*</span></label>
                <div class="checkbox-group">
                    <input type="checkbox" id="foster" name="services[]" value="Foster care">
                    <label for="foster">Foster care</label>

                    <input type="checkbox" id="disability" name="services[]" value="Disability support">
                    <label for="disability">Disability support</label>

                    <input type="checkbox" id="homeless" name="services[]" value="Homeless shelters">
                    <label for="homeless">Homeless shelters</label>

                    <input type="checkbox" id="Orphanage" name="services[]" value="Orphanage">
                    <label for="Orphanage">Orphanage care</label>

                    <input type="checkbox" id="education" name="services[]" value="Education programs">
                    <label for="education">Education programs</label>

                    <input type="checkbox" id="Elderly" name="services[]" value="Elderly services">
                    <label for="Elderly">Elderly care</label>
                </div>

                <label for="za">ZA number <span>*</span></label>
                <input type="text" id="za" name="za" required pattern="^\d+$" title="ZA number must be numeric.">

                <label for="web">Website</label>
                <input type="url" id="web" name="web" pattern="https?://.+">

                <label for="donorPass">Password <span>*</span></label>
                <input type="password" id="donorPass" name="donorPass" required minlength="6">

                <label for="num">Contact number <span>*</span></label>
                <input type="tel" id="num" name="num" required pattern="^\d{10}$" title="Contact number must be 10 digits.">

                <label for="orgImage">Upload registration certificate <span>*</span></label>
                <input type="file" id="orgImage" name="orgImage" required accept="image/*">

                <label for="logo">Upload logo <span>*</span></label>
                <input type="file" id="logo" name="logo" required accept="image/*">

                <button type="button" onclick="nextStep(1)">Next</button>
            </div>

            <!-- Step 2: Your Details -->
            <div class="form-step" id="step-2">
                <h2>Your details</h2>

                <label for="firstName">First name <span>*</span></label>
                <input type="text" id="firstName" name="firstName" required pattern="^[a-zA-Z]+$" title="First name should only contain letters.">

                <label for="lastName">Last name <span>*</span></label>
                <input type="text" id="lastName" name="lastName" required pattern="^[a-zA-Z]+$" title="Last name should only contain letters.">

                <label for="workEmail">Work email <span>*</span></label>
                <input type="email" id="workEmail" name="workEmail" required>

                <label for="CworkEmail">Confirm work email <span>*</span></label>
                <input type="email" id="CworkEmail" name="CworkEmail" required>

                <label for="position">Position in the organization <span>*</span></label>
                <input type="text" id="position" name="position" required pattern="^[a-zA-Z\s]+$" title="Position should only contain letters and spaces.">

                <label for="phone">Phone number <span>*</span></label>
                <input type="tel" id="phone" name="phone" required pattern="^\d{10}$" title="Phone number must be 10 digits.">

                <button type="button" onclick="prevStep(2)">Previous</button>
                <button type="button" onclick="nextStep(2)">Next</button>
            </div>

            <!-- Step 3: Location Details -->
            <div class="form-step" id="step-3">
                <h2>Location details</h2>

                <label for="address">Address <span>*</span></label>
                <textarea id="address" name="address" required></textarea>

                <label for="postcode">Postal code <span>*</span></label>
                <input type="text" id="postcode" name="postcode" required pattern="^\d{4}$" title="Postal code must be 4 digits.">

                <button type="button" onclick="prevStep(3)">Previous</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;

        function nextStep(step) {
            if (validateStep(step)) {
                const currentTab = document.getElementById(`step-${step}`);
                currentTab.classList.remove('active');
                currentStep++;
                const nextTab = document.getElementById(`step-${currentStep}`);
                nextTab.classList.add('active');
                updateTabs(currentStep);
            }
        }

        function prevStep(step) {
            const currentTab = document.getElementById(`step-${step}`);
            currentTab.classList.remove('active');
            currentStep--;
            const prevTab = document.getElementById(`step-${currentStep}`);
            prevTab.classList.add('active');
            updateTabs(currentStep);
        }

        function validateStep(step) {
            const inputs = document.querySelectorAll('#step-' + step + ' input[required], #step-' + step + ' textarea[required]');
            let valid = true;

            // Checkbox validation for Step 1
            if (step === 1) {
                const checkboxes = document.querySelectorAll('#step-1 .checkbox-group input[type="checkbox"]');
                const checkedCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
                if (checkedCount === 0) {
                    valid = false;
                    alert("Please select at least one service provided by the organisation.");
                }
            }

            // Validate inputs
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    valid = false;
                    input.classList.add('invalid');
                } else {
                    input.classList.remove('invalid');
                }
            });

            // Check email match for Step 2
            if (step === 2) {
                checkEmailMatch();
            }

            return valid;
        }

        function checkEmailMatch() {
            const email = document.getElementById('workEmail');
            const confirmEmail = document.getElementById('CworkEmail');
            if (email.value !== confirmEmail.value) {
                confirmEmail.setCustomValidity("Emails do not match");
                confirmEmail.classList.add('invalid');
            } else {
                confirmEmail.setCustomValidity("");
                confirmEmail.classList.remove('invalid');
            }
        }

        function updateTabs(step) {
            const tabs = document.querySelectorAll('.tab div');
            tabs.forEach((tab, index) => {
                tab.classList.remove('active');
                if (index === step - 1) {
                    tab.classList.add('active');
                }
            });
        }
    </script>
</body>
</html>
