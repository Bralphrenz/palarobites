<?php
include 'db_connect.php';
$users = [];
$q = $conn->query("SELECT user_id, first_name, last_name, email FROM user_info WHERE role='user' ORDER BY first_name");
while($row = $q->fetch_assoc()) $users[] = $row;
header('Content-Type: application/json');
echo json_encode($users);