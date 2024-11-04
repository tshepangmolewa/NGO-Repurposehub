<?php
// Database connection
$host = "localhost"; // replace with your database host
$dbname = "repurposehub"; // replace with your database name
$username = "root"; // replace with your database username
$password = ""; // replace with your database password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL queries to fetch counts
$itemsNeededQuery = "SELECT COUNT(*) AS count FROM requests"; // replace with actual table name
$itemsDonatedQuery = "SELECT COUNT(*) AS count FROM donations"; // replace with actual table name
$organizationsQuery = "SELECT COUNT(*) AS count FROM ngos"; // replace with actual table name

// Execute queries and store results
$itemsNeededResult = $conn->query($itemsNeededQuery);
$itemsNeededCount = $itemsNeededResult->fetch_assoc()['count'];

$itemsDonatedResult = $conn->query($itemsDonatedQuery);
$itemsDonatedCount = $itemsDonatedResult->fetch_assoc()['count'];

$organizationsResult = $conn->query($organizationsQuery);
$organizationsCount = $organizationsResult->fetch_assoc()['count'];

$conn->close();
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>RepurposeHub</title>
        <!--box icons link-->
        <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

        <!--remix icons link-->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
       <!--https://youtu.be/wsyP12PjmdE?si=13k92vSmmfpmoYGN-->

        <!--google font icons link-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <style>
            *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    list-style: none;
    text-decoration: none;
    scroll-behavior: smooth;
    font-family: "Jost", sans-serif;
}
:root{
    --bg-color: #ffffff;
    --main-color: #20b2aa;
    --text-color: #000000;
    --other-color: #83868c;
    --second-color: #d9d9d9;

    --h1-font: 5.5rem;
    --h2-font: 2.8rem;
    --p-font: 1.1rem;
}

body{
    background: var(--bg-color);
    color: var(--text-color);
}

header{
    position: fixed;
    width: 100%;
    top: 0;
    right: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 10%;
    background-color: var(--bg-color);
    box-shadow: 0 2px 5px -2px #0000001a;
    transition: all .6s ease;
}

header h1{
    position: absolute;
    left: 0;
    padding: 20px;
    text-align: left;
    color: #20b2aa;
    font-size: 37px;
}

.logo img{
    max-width: 180px;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.navlist{
    display: flex;
}

.navlist a{
    display: inline-block;
    font-size: var(--p-font);
    color: var(--text-color);
    margin: 0 30px;
    padding: 2px 0;
    border-bottom: 2px solid transparent;
    transition: all .6s ease;
}

.navlist a:hover{
    color: var(--main-color);
    border-bottom: 2px solid var(--main-color);
}
section{
    padding: 80px 10% 80px;
}
.hero{
    position: relative;
    height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    text-align: center;
    background: url(images/back1.jpg);
    background-size: cover;
    background-position: center;
}
.hero-text{
    position: absolute;
    top: 120px;
    left: 0;
    padding: 20px;
    text-align: left;
    max-width: 500px;
}

.hero-text h1{
    font-size: 28px;
}

.hero-text p{
    font-size: 16px;
    font-weight: 400;
}

.btn{
    display: inline-block;
    padding: 10px 30px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 20px;
    color: white;
    letter-spacing: 2px;
    background: #20b2aa;
}

main {
    text-align: center;
    padding: 2rem;
    background-color: #F5F5F5;
}

/*h1 {
    font-size: 2rem;
}*/

.about {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: space-around;
    padding: 20px; 
}

.info{
    text-align: center;
}

.counter {
    font-size: 50px;
    color: #20b2aa;
    font-weight: 450;
}

.info p{
    font-size: 18px;
    color: #505050;
    font-weight: 350;
}

.green-button {
    margin-top: 10px;
    background-color: transparent;
    color: #20b2aa;
    padding: 0.5rem 1rem;
    border-radius: 27px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.impact{
    padding-left: 20px;
    padding-right: 20px;
}

.impact h2{
    color: #20b2aa;
}

div.gallery {
    border: 1px solid #ccc;
  }
  
  div.gallery:hover {
    border: 1px solid #777;
  }
  
  div.gallery img {
    width: 100%;
    height: auto;
  }
  
  div.desc {
    padding: 15px;
    text-align: center;
  }
  
  * {
    box-sizing: border-box;
  }
  
  .responsive {
    padding: 0 6px;
    float: left;
    width: 24.99999%;
    
  }
  
  @media only screen and (max-width: 700px) {
    .responsive {
      width: 49.99999%;
      margin: 6px 0;
    }
  }
  
  @media only screen and (max-width: 500px) {
    .responsive {
      width: 100%;
    }
  }
  
  .clearfix:after {
    content: "";
    display: table;
    clear: both;
  }


.footer{
    display: flex;
    flex-wrap: wrap;
    margin-top: 5px;
   
    background-color: #20b2aa;
    padding: 30px 2%;
}

.ul{
    list-style: none;
}
.footer-col{
    width: 25%;
}

.footer-col h4{
    position: relative;
    margin-bottom: 30px;
    font-weight: bold;
    font-size: 16px;
    color: black;
    text-transform: capitalize;
}

.footer-col h4::before{
    content: '';
    position: absolute;
    left: 0;
    bottom: -6px;
    background-color: #20b2aa;
    height: 2px;
    width: 40px;
}

ul li:not(:last-child){
    margin-bottom: 8px;
}

ul li a{
    display: block;
    font-size: 14px;
    text-transform: capitalize;
    color: black;
    text-decoration: none;
    transition: 0.4s;
}

ul li a:hover{
    color: white;
    padding-left: 2px;
}

.links a{
    display: inline-block;
    height: 44px;
    width: 44px;
    color: white;
    background-color: rgba(40, 130, 214, 0.8);
    margin: 0 8px 8px 0;
    text-align: center;
    line-height: 44px;
    border-radius: 50%;
    transition: 0.4s;
}

.links a:hover{
    color: #4d4f55;
    background-color: white;
}

/* slider*/
.slider-container {
    position: absolute;
    bottom: 10px;
    width: 100%;
    display: flex;
    justify-content: center;
    background: none;  
}

.slider {
    display: flex;
    overflow: hidden;
    width: 100%;
    max-width: 1200px;
}

.card {
    min-width: 20%;
    padding: 0; 
    margin-right: 20px;
    border-radius: 10px;
    box-shadow: none; 
    text-align: center;
    transition: transform 0.3s ease-in-out;
}

.card img {
    width: 100%;
    height: 30vh; 
    object-fit: cover; 
    margin: 0;
}

.info a{
    color: #20b2aa;
}

.green-button {
    border: 2px solid #20b2aa;
}

 /* Styles for the modal background and content */
 .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }

        .hero {
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            color: #fff;
            background-color: #20b2aa;
            border: none;
            border-radius: 20px;
            text-decoration: none;
            cursor: pointer;
        }

        </style>
        
    </head>
    <body>
        <!--header-->
        <header style="padding: 8px 50%;">

            <h1>RepurposeHub</h1>
            
            <ul class="navlist">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="FAQ.php">FAQ</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>

                <div class="bx bx menu" id="menu-icon"></div>
            </div>
        </header>

        <!--hero section-->
        <section class="hero">
            <div class="hero-text">
                <h1>Every Item Tells a Story of Change.</h1>
                <p>Whether it's a cherished book, a gently used toy or a warm coat , your contribution can turn someone's day around.</p>
                <br>
                <a href="#" class="btn" id="seeWhatsNeededBtn">SEE WHAT'S NEEDED</a>     
            </div>

            <!-- Modal Structure -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <p>Please log in to view what's needed.</p>
    </div>
</div>



            <!-- Sliding cards container 
    <div class="slider-container">
        <div class="slider">

            <div class="card">
                <img src="image/help.jpg" alt="Story 1">
                <p>Homeless</p>
            </div>
            <div class="card">
                <img src="image/help2.jpg" alt="Story 2">
            </div>

            <div class="card">
                <img src="image/homeless1.jpg" alt="Story 4">
            </div>

            <div class="card">
                <img src="image/homeless3.jpg" alt="Story 4">
            </div>

            <div class="card">
                <img src="image/help.jpg" alt="Story 1">
                <p> </p>
            </div>
            <div class="card">
                <img src="image/help2.jpg" alt="Story 2">
            </div>

            <div class="card">
                <img src="image/homeless1.jpg" alt="Story 4">
            </div>

            <div class="card">
                <img src="image/homeless3.jpg" alt="Story 4">
            </div>

        </div>
    </div>  -->

        </section>

        <!--About section-->
        <main>
            <section class="about">

                <div class="info">
                   <div class="counter" id="itemsNeeded"><?php echo number_format($itemsNeededCount); ?></div>
                   <p>Items needed right now</p>
                   <button class="green-button"><a href="login.php">GIVE AN ITEM</a></button>
               </div>

               <div class="info">
                <div class="counter" id="itemsDonated"><?php echo number_format($itemsDonatedCount); ?></div>
                <p>Items donated to people in need</p>
                <button class="green-button"><a href="login.php">SEE YOUR IMPACT</a></button>
              </div>
               
              <div class="info">
                <div class="counter" id="organizations"><?php echo number_format($organizationsCount); ?></div>
                <p>Organizations we work with</p>
                <button class="green-button"><a href="organizationRegister.php">REGISTER NOW</a></button>
              </div>

            </section>
        </main>

        <!--Impact section-->
        <section class="impact">

            <h2>How your donations make an impact!!</h2>
            <br>
            
            <div class="responsive">
              <div class="gallery">
                  <img src="images/charity5.jpg" alt="Cinque Terre" width="600" height="400">
                </a>
                <div class="desc">Two little girls Lesego and Thato lived on the streets after losing their parents.
                    Donations helped them find shelter and education.
                    "I never thought I'd have a bed to sleep in or books to read." - Thato, 6 years old
                </div>
              </div>
            </div>
            
            
            <div class="responsive">
              <div class="gallery">
                  <img src="images/yes.jpg" alt="Forest" width="600" height="400">
                </a>
                <div class="desc">500 donated toys brought joy to children undergoing treatment.
                    Now kids can play and forget their illnesses.
                    "These toys distrated me from my pain" - Emily, 7
                </div>
              </div>
            </div>
            
            <div class="responsive">
              <div class="gallery">
                  <img src="images/charity2.jpg" alt="Northern Lights" width="600" height="400">
                </a>
                <div class="desc">Donated uniforms eliminated dress code related suspensions. After getting the uniform, the children attend school with pride.
                    <br> "I now feel equal to my peers. Thanks for the uniform." - Sam, 8 years old
                </div>
              </div>
            </div>
            
            <div class="responsive">
              <div class="gallery">
                  <img src="images/yes1.jpg" alt="Mountains" width="600" height="400">
                </a>
                <div class="desc">A donation of 100 backpacks filled with school supplies helped underprivileged learners start the school year with confidence.
                    "This bag helped me stay organized and focused. Thank you!" - Tebatso, 13 years old
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>
            
            <div style="padding:6px;">
              <p></p>
            </div>
            

        </section>

        <!--footer section-->
    
        <footer class="footer">
        <div class="footer-col">
            <h4>ABOUT US</h4>

        <ul>
            <li><a href="#"> > WHAT WE STAND FOR</a><li>
            <li><a href="#"> > OUR WORK IN DISASTER</a></li>
            <li><a href="#"> > GET INVOLVED</a></li>
            <li><a href="#"> > FUNDARAISE FOR REPURPOSEHUB</a></li>
            <li><a href="#"> > OUR SUPPORTERS</a></li>
            <li><a href="#"> > FAQS</a></li>
            <li><a href="#"> > GOVERNANCE</a></li>
            <li><a href="#"> > CONTACT US</a></li>
        </ul>
        </div>
            

        <div class="footer-col">
            <h4>NEWSLETTERS AND ALERTS</h4>
            <p>
                Get the latest RepurposeHub news and
                have a list of what is needed right
                now delivered weekly to your inbox.
            </p>
            <br>
            <button class="btn">SUBSCRIBE</button>
        </div>

        <div class="footer-col">
            <h4>CONNECT WITH US</h4>
            <div class="links">
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h1>REPURPOSEHUB</h1>
            <img src="" height="" width="">
        </div>

        </footer>
   
        <!--javascript-->
        <script>

          // Get elements
    const modal = document.getElementById("loginModal");
    const btn = document.getElementById("seeWhatsNeededBtn");
    const closeModal = document.getElementById("closeModal");

    // When the user clicks the button, open the modal
    btn.onclick = function(event) {
        event.preventDefault();
        modal.style.display = "block";
    };

    // When the user clicks on <span> (x), close the modal
    closeModal.onclick = function() {
        modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };  
           

      //for sliding images      
    const slider = document.querySelector('.slider');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 3;
        slider.scrollLeft = scrollLeft - walk;
    });

    // Auto-slide functionality
    let autoSlideIndex = 0;

    function autoSlide() {
        const cards = document.querySelectorAll('.card');
        if (autoSlideIndex >= cards.length) {
            autoSlideIndex = 0;
        }

        slider.scrollLeft = cards[autoSlideIndex].offsetLeft;
        autoSlideIndex++;
    }

    setInterval(autoSlide, 3000); // Change slide every 3 seconds


        </script>
    </body>
</html>