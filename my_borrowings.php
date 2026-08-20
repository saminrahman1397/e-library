<?php
session_start();
include 'db.php';

if(!isset($_SESSION['member_id'])){
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id'];

/* =======================
   RETURN A BOOK
======================= */
if(isset($_POST['return_book'])){
    $borrowing_id = $_POST['borrowing_id'];
    $book_id = $_POST['book_id'];

    mysqli_query($conn, "UPDATE BORROWING SET Return_Date = CURDATE() WHERE Borrowing_ID = '$borrowing_id'");
    mysqli_query($conn, "UPDATE BOOK SET Available_Copies = LEAST(Total_Copies, Available_Copies + 1) WHERE Book_ID = '$book_id'");

    // the DB trigger trg_create_fine_on_late_return may have just created a fine — check
    $fineCheck = mysqli_query($conn, "SELECT Fine_Amount FROM FINE WHERE Borrowing_ID = '$borrowing_id'");
    if($fineRow = mysqli_fetch_assoc($fineCheck)){
        $message = "Book returned — it was late, so a fine of ৳{$fineRow['Fine_Amount']} has been added to your account.";
    } else {
        $message = "Book returned on time. Thanks!";
    }
}

/* =======================
   FETCH THIS MEMBER'S CURRENT BORROWINGS
======================= */
$sql = "SELECT br.Borrowing_ID, b.Book_ID, b.Title, br.Issue_Date, br.Due_Date
        FROM BORROWING br
        JOIN BOOK b ON br.Book_ID = b.Book_ID
        WHERE br.Member_ID = '$member_id' AND br.Return_Date IS NULL
        ORDER BY br.Due_Date";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Borrowings</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo">Library Hub</div>

    <ul class="menu">
        <li style="color:white; font-weight:bold; font-size:24px;">
            <?php if(isset($_SESSION['member_name'])): ?>
                Hi, <?php echo $_SESSION['member_name']; ?>
            <?php endif; ?>
        </li>
        <li><a href="home.php">Home</a></li>
        <li><a href="dashboard.php">Books</a></li>
        <li><a href="my_borrowings.php">My Borrowings</a></li>
        <li><a href="my_fines.php">My Fines</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<h1 class="page-title">MY BORROWINGS</h1>

<?php if(isset($message)): ?>
    <p style="text-align:center; font-weight:bold; color:#2c7be5;"><?php echo $message; ?></p>
<?php endif; ?>

<div class="cart-wrapper">
<table class="cart-table">
    <tr>
        <th>Book</th>
        <th>Issue Date</th>
        <th>Due Date</th>
        <th>Action</th>
    </tr>

    <?php if(mysqli_num_rows($result) == 0): ?>
        <tr><td colspan="4">You haven't borrowed any books yet.</td></tr>
    <?php endif; ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo $row['Title']; ?></td>
        <td><?php echo $row['Issue_Date']; ?></td>
        <td><?php echo $row['Due_Date']; ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="borrowing_id" value="<?php echo $row['Borrowing_ID']; ?>">
                <input type="hidden" name="book_id" value="<?php echo $row['Book_ID']; ?>">
                <button class="btn" type="submit" name="return_book">Return</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

<!-- ================= FOOTER ================= -->
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-left">
            <h3>Library Hub</h3>
            <p>Independent University, Bangladesh</p>
        </div>
        <div class="footer-right">
            <h3>@libraryhub</h3>
        </div>
    </div>
</footer>

</body>
</html>
