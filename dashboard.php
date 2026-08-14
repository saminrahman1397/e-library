<?php
session_start();
include 'db.php';

if(!isset($_SESSION['member_id'])){
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id'];

/* =======================
   BORROW A BOOK
======================= */
if(isset($_POST['borrow_book'])){
    $book_id = $_POST['book_id'];

    // does this member already have this exact book out?
    $activeCheck = mysqli_query($conn, "SELECT COUNT(*) AS c FROM BORROWING WHERE Book_ID='$book_id' AND Member_ID='$member_id' AND Return_Date IS NULL");
    $activeRow = mysqli_fetch_assoc($activeCheck);

    // how many copies are free right now?
    $check = mysqli_query($conn, "SELECT Available_Copies FROM BOOK WHERE Book_ID='$book_id'");
    $book = mysqli_fetch_assoc($check);

    if($activeRow['c'] > 0){
        $message = "You already have a copy of this book — return it before borrowing another.";
    } elseif($book && $book['Available_Copies'] > 0){

        // generate next Borrowing_ID (BR001, BR002, ...)
        $idResult = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(Borrowing_ID,3) AS UNSIGNED)) AS max_id FROM BORROWING");
        $idRow = mysqli_fetch_assoc($idResult);
        $next_number = ($idRow['max_id'] ?? 0) + 1;
        $borrowing_id = "BR" . str_pad($next_number, 3, "0", STR_PAD_LEFT);

        $sql = "INSERT INTO BORROWING (Borrowing_ID, Book_ID, Member_ID, Issue_Date, Due_Date)
                VALUES ('$borrowing_id', '$book_id', '$member_id', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY))";

        if(mysqli_query($conn, $sql)){
            // only decrement if a copy is still actually free (guards against a race)
            mysqli_query($conn, "UPDATE BOOK SET Available_Copies = Available_Copies - 1 WHERE Book_ID='$book_id' AND Available_Copies > 0");
            $message = "Book borrowed! Due back in 14 days.";
        } else {
            $message = "Could not borrow this book: " . mysqli_error($conn);
        }
    } else {
        $message = "Sorry, no copies of that book are available right now.";
    }
}

/* =======================
   FETCH BOOKS
======================= */
$result = mysqli_query($conn, "SELECT * FROM BOOK ORDER BY Book_ID");

// track which books this member currently has out, so we can
// disable the Borrow button for those even if copies remain
$myBorrowed = [];
$mb = mysqli_query($conn, "SELECT Book_ID FROM BORROWING WHERE Member_ID='$member_id' AND Return_Date IS NULL");
while($r = mysqli_fetch_assoc($mb)){
    $myBorrowed[] = $r['Book_ID'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

<h1 class="page-title">BOOKS</h1>

<?php if(isset($message)): ?>
    <p style="text-align:center; font-weight:bold; color:#2c7be5;"><?php echo $message; ?></p>
<?php endif; ?>

<!-- ================= BOOK GRID ================= -->
<div class="food-container">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

    <div class="food-card">

        <img src="images/<?php echo $row['Cover_Image'] ? $row['Cover_Image'] : 'placeholder.jpg'; ?>" alt="book cover">

        <h3><?php echo $row['Title']; ?></h3>
        <p><?php echo $row['Category']; ?> &middot; <?php echo $row['Edition']; ?></p>

        <?php if(in_array($row['Book_ID'], $myBorrowed)): ?>
            <p class="price" style="color:#2c7be5;">You have this borrowed</p>
            <button class="btn" disabled style="background:#ccc; cursor:not-allowed;">
                ALREADY BORROWED
            </button>
        <?php elseif($row['Available_Copies'] > 0): ?>
            <p class="price" style="color:green;"><?php echo $row['Available_Copies']; ?> of <?php echo $row['Total_Copies']; ?> available</p>
            <form method="POST">
                <input type="hidden" name="book_id" value="<?php echo $row['Book_ID']; ?>">
                <button class="btn" type="submit" name="borrow_book">
                    BORROW
                </button>
            </form>
        <?php else: ?>
            <p class="price" style="color:#999;">0 of <?php echo $row['Total_Copies']; ?> available</p>
            <button class="btn" disabled style="background:#ccc; cursor:not-allowed;">
                UNAVAILABLE
            </button>
        <?php endif; ?>

    </div>

<?php } ?>

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
