<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Cool Cafe</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
/* ================= HERO ================= */
.hero {
    text-align: center;
    padding: 40px;
}

.hero h1 {
    font-size: 40px;
    margin-bottom: 20px;
}

.hero-content {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 50px;
}

.hero .text {
    max-width: 600px;
    font-size: 14px;
    color: #333;
}

.hero .image img {
    height: 250px;
}

/* ================= SERVICES ================= */
.services {
    display: flex;
    justify-content: space-around;
    padding: 50px;
    text-align: center;
}

.service-box i {
    font-size: 60px;
    margin-bottom: 15px;
}

</style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo">Cool Cafe</div>

    <ul class="menu">

        <li style="color:white; font-weight:bold; font-size:24px;">
            <?php if(isset($_SESSION['username'])): ?>
                Welcome, <?php echo $_SESSION['username']; ?>
            <?php endif; ?>
        </li>

        <li><a href="home.php">Home</a></li>
        <li><a href="dashboard.php">Foods</a></li>
        <li><a href="logout.php">Logout</a></li>

    </ul>
</div>


<!-- ================= HERO SECTION ================= -->
<section class="hero">

    <h1>Resturant Powered By Cool Cafe</h1>

    <div class="hero-content">
        <div class="text">
            <p>Cool Cafe is an innovative online food ordering platform designed to make dining convenient, fast, and enjoyable. It brings a wide variety of delicious meals right to your fingertips, allowing customers to browse, select, and order their favorite dishes from the comfort of their homes. Whether you crave a juicy burger, cheesy pizza, or flavorful fried rice, Cool Cafe offers a diverse menu to satisfy every taste.

The website is user-friendly, with a clean interface that helps customers easily explore categories, view food details, and add items to their cart. With a secure login system and smooth navigation, users can quickly place orders without any hassle. One of the key features of Cool Cafe is its efficient delivery service, ensuring that food reaches customers fresh and on time.

Cool Cafe also focuses on quality and customer satisfaction. Each dish is prepared with care, maintaining high standards of hygiene and taste. The platform aims to provide not just food, but a delightful experience for every user. With its combination of convenience, variety, and reliability, Cool Cafe is becoming a popular choice for online food ordering, making everyday meals easier and more enjoyab
            </p>
        </div>

        <div class="image">
            <img src="images/17964.jpg" alt="">
        </div>
    </div>

</section>


<!-- ================= SERVICES ================= -->
<section class="services">

    <div class="service-box">
        <i class="fas fa-utensils"></i>
        <h3>Variety of Dishes</h3>
    </div>

    <div class="service-box">
        <i class="fas fa-truck"></i>
        <h3>Free Delivery</h3>
    </div>

    <div class="service-box">
        <i class="fas fa-smile"></i>
        <h3>Excellent Quality</h3>
    </div>

</section>


<!-- ================= FOOTER ================= -->
<footer class="site-footer">

    <div class="footer-container">

        <div class="footer-left">
            <h3>Cool Cafe</h3>
            <p>Address: Mirpur-2, Dhaka, Bangladesh</p>
            <p>Phone: +880 1725895998</p>
            <p>Email: support@coolcafe.com</p>
        </div>

        <div class="footer-right">
            <h3>@coolcafe</h3>
        </div>

    </div>

</footer>

</body>
</html>
