<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM MEMBER WHERE Email='$email'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1){

        $member = mysqli_fetch_assoc($result);

        if(password_verify($password,$member['Password'])){

            $_SESSION['member_id'] = $member['Member_ID'];
            $_SESSION['member_name'] = $member['Full_Name'];

            header("Location: home.php");
            exit();
        }
    }else{

    	$error = "Invalid email or password!";
    	}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/loginstyle.css">
</head>

<body>

<!-- TOP HEADER -->
<div class="top-bar">
    Library Hub
</div>

<!-- LOGIN CENTER -->
<div class="login-container">

    <div class="login-box">

        <h2>Sign in to your account</h2>
        <p>Please enter your email and password to log in.</p>

        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

        <form method="POST">

            <input type="email" name="email" placeholder="Enter your email:" required>

            <div>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="login">Login &#8594;</button>

            <p style="text-align:center; margin-top:15px;">
                Don't have an account?
                <a href="register.php">Register</a>
            </p>

        </form>

    </div>

</div>

</body>
</html>
