<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - RePurposeHub</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            text-align: center;
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            height: 50px;
        }

        h1 {
            margin-top: -10px;
            font-size: 40px;
        }

        .header-image {
            width: 100%;
            height: auto;
        }

        section {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            font-size: 35px;
        }

        .content-container {
            display: flex;
            align-items: center;
        }

        .section-image {
            width: 30%;
            height: auto;
            margin-top: 15px;
            border-radius: 5px;
            margin-top: -30px;
        }

        .content {
            width: 70%;
            padding: 10px;
            margin-top: -190px;
        }

        .reverse .content {
            order: -1;
        }

        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #2c3e50;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>ABOUT US - RePurposeHub</h1>
    </header>

    

    <section class="what-we-do reverse">
        <h2>What We Do</h2>
        <div class="content-container">
            <img src="images/number3.jpg" alt="Platform Screenshot" class="section-image">
            <div class="content">
                <p>We facilitate the exchange of reusable items. Donors can list items they wish to donate and NGOs can browse and claim these items to support their initiatives. We provide a seamless and efficient process that ensures resources are effectively utilized.</p>
            </div>
        </div>
    </section>

    <section class="mission">
        <h2>Our Mission</h2>
        <div class="content-container">
            <img src="images/welcoming.jpg" alt="Recycling Process" class="section-image">
            <div class="content">
                <p>Our mission is to reduce waste by connecting individuals and organizations who want to give reusable items to those in need. We believe that every item has the potential for a second life and we strive to ensure that essential resources reach those who require them most.</p>
            </div>
        </div>
    </section>

    <section class="values reverse">
        <h2>Our Values</h2>
        <div class="content-container">
            <img src="images/values.jpg" alt="Values Icons" class="section-image">
            <div class="content">
                <p>We are committed to sustainability, community support and transparency. Our values guide our operations and ensure that we are making a positive impact on the environment and the lives of those in need.</p>
            </div>
        </div>
    </section>

    <section class="get-involved ">
        <h2>Get Involved</h2>
        <div class="content-container">
            <img src="images/community.jpg" alt="Volunteers in Action" class="section-image">
            <div class="content">
                <p>Join us in our mission to repurpose and redistribute items that can help those in need. Whether you are a donor or an NGO, your participation makes a difference.</p>
            </div>
        </div>
    </section>

    <a href="homepage.php"> >BACK </a>

    <footer>
        <p>&copy; 2024 RePurposeHub. All rights reserved.</p>
    </footer>
</body>
</html>
