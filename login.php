<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password,$user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['fullname'];

            header("Location: home.php");
            exit();
        }
    }


    $error = "Invalid email or password!";
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
    E-Library
</div>

<!-- LOGIN CENTER -->
<div class="login-container">

    <div class="login-box">

        <h2>Sign in to your account</h2>
        <p>Please enter your Id and password to log in.</p>

        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

        <form method="POST">

            <input type="email" name="email" placeholder="Enter your gmail:" required>

            <div>
                <input type="password" name="password" placeholder="Pass" required>
                <a class="forgot" href="#">I forgot my password</a>
            </div>

            <button type="submit" name="login">Login ➜</button>

	<p style="text-align:center; margin-top:15px;">
   		 Don't have an account? 
    		<a href="register.php">Register</a>
	</p>

        </form>

    </div>

</div>

</body>
</html>