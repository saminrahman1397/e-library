<?php
include 'db.php';

if(isset($_POST['register'])){

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $membership_type = $_POST['membership_type'];

    // generate the next Member_ID (M001, M002, ...) to match the existing scheme
    $idResult = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(Member_ID,2) AS UNSIGNED)) AS max_id FROM MEMBER");
    $idRow = mysqli_fetch_assoc($idResult);
    $next_number = ($idRow['max_id'] ?? 0) + 1;
    $member_id = "M" . str_pad($next_number, 3, "0", STR_PAD_LEFT);

    $sql = "INSERT INTO MEMBER (Member_ID, Full_Name, Email, Password, Membership_Type, Registration_Date, Status)
            VALUES ('$member_id', '$fullname', '$email', '$password', '$membership_type', CURDATE(), 'Active')";

    if(mysqli_query($conn,$sql)){
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/registerstyle.css">
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
    <h1>Library Hub</h1>
</div>

<!-- Center Card -->
<div class="auth-container">
    <div class="auth-box">
        <h2>Create your account</h2>
        <p>Please fill in the details to register</p>

        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <select name="membership_type" required>
                <option value="Student">Student</option>
                <option value="Faculty">Faculty</option>
            </select>

            <button type="submit" name="register">Register &#8594;</button>
        </form>

        <p class="bottom-text">
            Already have an account?
            <a href="login.php">Login</a>
        </p>
    </div>
</div>

</body>
</html>
