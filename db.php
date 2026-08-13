<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "University_Library_Management_System"
);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

?>
