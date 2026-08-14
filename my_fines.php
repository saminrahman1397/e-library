<?php
session_start();
include 'db.php';

if(!isset($_SESSION['member_id'])){
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id'];

/* =======================
   MARK A FINE PAID
======================= */
if(isset($_POST['pay_fine'])){
    $fine_id = $_POST['fine_id'];

    mysqli_query($conn, "UPDATE FINE SET Payment_Status='Paid', Payment_Date=CURDATE() WHERE Fine_ID='$fine_id'");
    $message = "Fine marked as paid.";
}

/* =======================
   FETCH THIS MEMBER'S FINES
======================= */
$sql = "SELECT f.Fine_ID, b.Title, f.Overdue_Days, f.Daily_Fine_Rate, f.Fine_Amount, f.Payment_Status, f.Payment_Date
        FROM FINE f
        JOIN BORROWING br ON f.Borrowing_ID = br.Borrowing_ID
        JOIN BOOK b ON br.Book_ID = b.Book_ID
        WHERE br.Member_ID = '$member_id'
        ORDER BY f.Payment_Status DESC, f.Fine_ID DESC";
$result = mysqli_query($conn, $sql);

// running total of unpaid fines
$totalResult = mysqli_query($conn, "
    SELECT SUM(f.Fine_Amount) AS total_due
    FROM FINE f
    JOIN BORROWING br ON f.Borrowing_ID = br.Borrowing_ID
    WHERE br.Member_ID = '$member_id' AND f.Payment_Status = 'Unpaid'
");
$totalRow = mysqli_fetch_assoc($totalResult);
$total_due = $totalRow['total_due'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Fines</title>
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

<h1 class="page-title">MY FINES</h1>

<?php if(isset($message)): ?>
    <p style="text-align:center; font-weight:bold; color:#2c7be5;"><?php echo $message; ?></p>
<?php endif; ?>

<p style="text-align:center; font-size:18px;">
    <?php if($total_due > 0): ?>
        Total unpaid: <strong style="color:#c0392b;">৳<?php echo $total_due; ?></strong>
    <?php else: ?>
        <span style="color:green;">No unpaid fines. Nice.</span>
    <?php endif; ?>
</p>

<div class="cart-wrapper">
<table class="cart-table">
    <tr>
        <th>Book</th>
        <th>Days Late</th>
        <th>Rate/Day</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php if(mysqli_num_rows($result) == 0): ?>
        <tr><td colspan="6">No fines on record.</td></tr>
    <?php endif; ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo $row['Title']; ?></td>
        <td><?php echo $row['Overdue_Days']; ?></td>
        <td>৳<?php echo $row['Daily_Fine_Rate']; ?></td>
        <td>৳<?php echo $row['Fine_Amount']; ?></td>
        <td><?php echo $row['Payment_Status']; ?></td>
        <td>
            <?php if($row['Payment_Status'] == 'Unpaid'): ?>
                <form method="POST">
                    <input type="hidden" name="fine_id" value="<?php echo $row['Fine_ID']; ?>">
                    <button class="btn" type="submit" name="pay_fine">Mark Paid</button>
                </form>
            <?php else: ?>
                Paid <?php echo $row['Payment_Date']; ?>
            <?php endif; ?>
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
