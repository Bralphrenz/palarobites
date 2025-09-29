<?php
$servername = "sql104.infinityfree.com";
$username = "if0_40051887";
$password = "ComradeArms8059";
$dbname = "if0_40051887_food_ordering";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
