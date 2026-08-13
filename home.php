<?php
session_start();
include 'db.php';

if(!isset($_SESSION['member_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
    text-align: left;
}
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
    <div class="logo">Library Hub</div>

    <ul class="menu">
        <li style="color:white; font-weight:bold; font-size:24px;">
            <?php if(isset($_SESSION['member_name'])): ?>
                Welcome, <?php echo $_SESSION['member_name']; ?>
            <?php endif; ?>
        </li>

        <li><a href="home.php">Home</a></li>
        <li><a href="dashboard.php">Books</a></li>
        <li><a href="my_borrowings.php">My Borrowings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- ================= HERO SECTION ================= -->
<section class="hero">
    <h1>University Library Management System</h1>

    <div class="hero-content">
        <div class="text">
            <p>Browse the library's full catalog, borrow a book with one click,
            and keep track of everything you currently have checked out —
            all from one place. Each title has a single copy: once it's
            borrowed, it's marked unavailable for everyone else until it's
            returned.</p>
        </div>
    </div>
</section>

<!-- ================= SERVICES ================= -->
<section class="services">
    <div class="service-box">
        <i class="fas fa-book"></i>
        <h3>Wide Catalog</h3>
    </div>
    <div class="service-box">
        <i class="fas fa-bolt"></i>
        <h3>One-Click Borrowing</h3>
    </div>
    <div class="service-box">
        <i class="fas fa-clock-rotate-left"></i>
        <h3>Track Due Dates</h3>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-left">
            <h3>Library Hub</h3>
            <p>Independent University, Bangladesh</p>
        </div>
        <div class="footer-right">
            <h3>libraryhub</h3>
        </div>
    </div>
</footer>

</body>
</html>
