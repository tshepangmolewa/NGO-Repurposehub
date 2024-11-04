<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Organization Profile</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f8;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .progress-bar {
            width: 100%;
            background-color: #f1f1f1;
            margin: 20px 0;
        }

        .progress {
            width: 50%;
            height: 20px;
            background-color: #2c3e50;
        }

        label {
            margin-top: 15px;
            font-weight: bold;
            color: #333333;
        }

        input, textarea, select {
            width: 90%;
            padding: 12px 15px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 15px;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #2c3e50;
        }

        .logo-preview {
            margin-top: 20px;
            display: none;
        }

        .logo-preview img {
            max-width: 100px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        /* Classy Button Styles */
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        button[type="button"], button[type="submit"] {
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            background: linear-gradient(135deg, #2c3e50, #2c3e50);
            margin-top: 20px; /* Space between Next button and form */
        }

        button[type="button"]:before, button[type="submit"]:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 10%, transparent 10.01%);
            background-size: 20px 20px;
            z-index: -1;
            transition: all 0.5s ease;
        }

        button[type="button"]:hover, button[type="submit"]:hover {
            box-shadow: 0px 5px 15px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Profile</h1>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress" id="progress"></div>
        </div>

        <?php
        // Include the database connection page
        include 'C:\xampp\htdocs\Second attempt\db_connection.php';

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and validate inputs
            $orgName = mysqli_real_escape_string($conn, $_POST['org-name']);
            $orgAddress = mysqli_real_escape_string($conn, $_POST['org-address']);
            $orgEmail = mysqli_real_escape_string($conn, $_POST['org-email']);
            $orgPhone = mysqli_real_escape_string($conn, $_POST['org-phone']);

            // You can handle file upload here if needed

            // Example: Insert or update logic here
            $sql = "UPDATE organizations SET name='$orgName', address='$orgAddress', email='$orgEmail', phone='$orgPhone' WHERE id=1"; // Use a proper condition for your update
            if (mysqli_query($conn, $sql)) {
                echo "<p style='color:green;'>Profile updated successfully!</p>";
            } else {
                echo "<p style='color:red;'>Error updating profile: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>

        <form id="updateForm" method="POST" enctype="multipart/form-data">
            <!-- Step 1: Organization Details -->
            <div class="step active">
                <label for="org-name">Organization Name</label>
                <input type="text" id="org-name" name="org-name" required>

                <label for="org-address">Address</label>
                <input type="text" id="org-address" name="org-address" required>

                <label for="org-email">Contact Email</label>
                <input type="email" id="org-email" name="org-email" required>

                <label for="org-phone">Phone Number</label>
                <input type="tel" id="org-phone" name="org-phone" required>

                <button type="button" onclick="nextStep()">Next</button>
            </div>

            <!-- Step 2: Logo Upload -->
            <div class="step">
                <label for="org-logo">Upload Organization Logo</label>
                <input type="file" id="org-logo" name="org-logo" accept="image/*" onchange="previewLogo()">

                <div class="logo-preview" id="logo-preview">
                    <img id="logo-img" src="" alt="Logo Preview">
                </div>

                <!-- Button Group with Previous and Submit buttons on the same line -->
                <div class="btn-group">
                    <button type="button" onclick="prevStep()">Previous</button>
                    <button type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 0;

        function nextStep() {
            const steps = document.querySelectorAll('.step');
            if (currentStep < steps.length - 1) {
                steps[currentStep].classList.remove('active');
                currentStep++;
                steps[currentStep].classList.add('active');
                updateProgressBar();
            }
        }

        function prevStep() {
            const steps = document.querySelectorAll('.step');
            if (currentStep > 0) {
                steps[currentStep].classList.remove('active');
                currentStep--;
                steps[currentStep].classList.add('active');
                updateProgressBar();
            }
        }

        function updateProgressBar() {
            const progress = document.getElementById('progress');
            const stepCount = document.querySelectorAll('.step').length;
            progress.style.width = ((currentStep + 1) / stepCount) * 100 + '%';
        }

        // Function to preview the logo
        function previewLogo() {
            const file = document.getElementById('org-logo').files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const logoImg = document.getElementById('logo-img');
                    logoImg.src = e.target.result;
                    document.getElementById('logo-preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
